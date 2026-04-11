<?php

use App\MacuinApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Login
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }

    return view('auth.login');
})->name('login');

// Registro (vista + alta vía API central)
Route::get('/registro', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }

    return view('auth.registro');
})->name('register');

Route::post('/registro', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:100'],
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
            'confirmed',
        ],
        'terms' => ['accepted'],
        'phone' => ['nullable', 'string', 'max:30'],
    ], [
        'password.min' => 'Longitud mínima: Se recomienda que la contraseña tenga al menos 8 caracteres.',
        'password.regex' => 'Diversidad de caracteres: debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ]);

    $apiUrl = MacuinApi::url();
    $response = Http::timeout(15)->asJson()->post($apiUrl.'/auth/register', [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password'],
        'role' => 'client',
    ]);

    if ($response->successful()) {
        return redirect('/login')->with('success', 'Cuenta creada. Inicia sesión con tu correo y contraseña.');
    }

    if ($response->status() === 400) {
        $detail = $response->json('detail');
        $message = is_string($detail)
            ? $detail
            : 'El correo ya está registrado o los datos no son válidos.';

        return back()->withErrors(['email' => $message])->withInput($request->except('password', 'password_confirmation'));
    }

    return back()->withErrors([
        'register' => 'No se pudo completar el registro. Verifica que la API esté disponible e intenta de nuevo.',
    ])->withInput($request->except('password', 'password_confirmation'));
})->name('register.submit');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $response = Http::asForm()->post(MacuinApi::url().'/auth/login', [
        'username' => $request->email,
        'password' => $request->password,
    ]);

    if ($response->successful()) {
        $data = $response->json();
        $token = $data['access_token'];
        $request->session()->put('jwt_token', $token);

        $me = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/auth/me');
        if (! $me->successful()) {
            $request->session()->forget('jwt_token');

            return back()->withErrors([
                'email' => 'No se pudo obtener el perfil desde la API.',
            ])->onlyInput('email');
        }

        $u = $me->json();
        $user = new \Illuminate\Auth\GenericUser([
            'id' => $u['id'],
            'name' => $u['name'],
            'email' => $u['email'],
            'role' => $u['role'] ?? null,
            'password' => '',
            'remember_token' => null,
        ]);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales proporcionadas no coinciden o fueron rechazadas por la red (JWT).',
    ])->onlyInput('email');
});

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->forget('jwt_token');
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// Dashboard externo
Route::get('/dashboard', function (Request $request) {
    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return redirect('/login');
    }

    $ordersRes = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/orders/');
    $ordersPayload = $ordersRes->successful() ? ($ordersRes->json() ?? []) : [];

    $orders = collect($ordersPayload)->map(fn (array $row) => MacuinApi::orderFromApi($row));

    $activeOrders = $orders->filter(fn ($o) => in_array(strtolower((string) $o->status), ['pending', 'processing', 'shipped'], true))->values();

    $recentOrders = $orders->sortByDesc(fn ($o) => $o->created_at->timestamp)->take(5)->values();

    return view('dashboard.index', ['activeOrders' => $activeOrders, 'recentOrders' => $recentOrders]);
})->middleware('auth');

// Perfil externo
Route::get('/perfil', function () {
    $user = Auth::user();

    return view('perfil.index', ['user' => $user]);
})->middleware('auth');

// Configuración del perfil
Route::get('/perfil/configuracion', function () {
    $user = Auth::user();

    return view('perfil.configuracion', ['user' => $user]);
})->middleware('auth');

// Historial de pedidos
Route::get('/pedidos', function (Request $request) {
    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return redirect('/login');
    }

    $res = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/orders/');
    $orders = $res->successful()
        ? collect($res->json() ?? [])->map(fn (array $row) => MacuinApi::orderFromApi($row))
        : collect();

    return view('pedidos.historial', ['orders' => $orders]);
})->middleware('auth');

// Crear pedido externo
Route::get('/pedidos/crear', function (Request $request) {
    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return redirect('/login');
    }

    $cartRes = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/cart/');
    if (! $cartRes->successful()) {
        return redirect('/carrito')->withErrors(['cart' => 'No se pudo cargar el carrito.']);
    }

    $cartItems = MacuinApi::cartItemsFromApi($cartRes->json());
    if ($cartItems->isEmpty()) {
        return redirect('/carrito');
    }

    $subtotal = $cartItems->sum(function ($item) {
        return (float) $item->autopart->price * (int) $item->quantity;
    });

    $taxes = $subtotal * 0.16;
    $shipping = 0.0;
    $total = $subtotal + $taxes + $shipping;

    return view('pedidos.crear', [
        'cartItems' => $cartItems,
        'subtotal' => $subtotal,
        'taxes' => $taxes,
        'shipping' => $shipping,
        'total' => $total,
    ]);
})->middleware('auth');

// Crear pedido vía API (FastAPI) con JWT (el carrito se vacía en la API)
Route::post('/pedidos/finalizar', function (Request $request) {
    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return redirect('/login')->withErrors([
            'email' => 'Tu sesión expiró o falta el token de API. Vuelve a iniciar sesión.',
        ]);
    }

    $cartRes = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/cart/');
    if (! $cartRes->successful() || empty($cartRes->json())) {
        return redirect('/carrito')->withErrors(['cart' => 'Tu carrito está vacío.']);
    }

    $items = collect($cartRes->json())->map(fn ($row) => [
        'autopart_id' => (int) $row['autopart_id'],
        'quantity' => (int) $row['quantity'],
    ])->values()->all();

    $response = Http::timeout(45)
        ->withToken($token)
        ->acceptJson()
        ->post(MacuinApi::url().'/orders/', ['items' => $items]);

    if ($response->successful()) {
        $data = $response->json();
        $orderId = $data['id'] ?? null;
        if ($orderId) {
            return redirect('/pedidos/'.$orderId)->with('success', 'Pedido creado correctamente.');
        }

        return redirect('/pedidos')->with('success', 'Pedido creado correctamente.');
    }

    $detail = $response->json('detail');
    $message = 'No se pudo crear el pedido.';
    if (is_string($detail)) {
        $message = $detail;
    } elseif (is_array($detail)) {
        $flattened = collect($detail)->map(function ($d) {
            if (is_string($d)) {
                return $d;
            }
            if (is_array($d)) {
                return $d['msg'] ?? $d['message'] ?? json_encode($d);
            }

            return null;
        })->filter()->first();
        $message = $flattened ?: $message;
    }

    return back()->withErrors(['order' => $message]);
})->middleware('auth');

// Detalle de pedido externo
Route::get('/pedidos/{id}', function (Request $request, string $id) {
    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return redirect('/login');
    }

    $res = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/orders/'.$id);
    if ($res->status() === 404) {
        abort(404);
    }
    if (! $res->successful()) {
        abort(404);
    }

    $order = MacuinApi::orderFromApi($res->json());

    return view('pedidos.detalle', ['order' => $order]);
})->middleware('auth');

// Catálogo externo
Route::get('/catalogo', function (Request $request) {
    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return redirect('/login');
    }

    $params = array_filter([
        'q' => $request->q,
        'category' => $request->category,
    ], fn ($v) => $v !== null && $v !== '');

    $res = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/autoparts/', $params);
    $autoparts = $res->successful()
        ? collect($res->json())->map(fn ($row) => json_decode(json_encode($row)))
        : collect();

    $cartRes = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/cart/');
    $cartCount = $cartRes->successful()
        ? collect($cartRes->json())->sum('quantity')
        : 0;

    return view('catalogo.index', [
        'autoparts' => $autoparts,
        'activeSearch' => $request->q,
        'activeCategory' => $request->category,
        'cartCount' => $cartCount,
    ]);
})->middleware('auth');

// Detalle de autoparte
Route::get('/catalogo/{id}', function (Request $request, string $id) {
    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return redirect('/login');
    }

    $res = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/autoparts/'.$id);
    if (! $res->successful()) {
        abort(404);
    }

    $autopart = json_decode(json_encode($res->json()));

    return view('catalogo.detalle', ['autopart' => $autopart]);
})->middleware('auth');

// Funcionalidad Carrito
Route::post('/carrito/agregar', function (Request $request) {
    $request->validate([
        'autopart_id' => 'required|integer',
        'quantity' => 'required|integer|min:1',
    ]);

    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
    }

    $res = Http::withToken($token)->acceptJson()->post(MacuinApi::url().'/cart/items', [
        'autopart_id' => (int) $request->autopart_id,
        'quantity' => (int) $request->quantity,
    ]);

    if (! $res->successful()) {
        $msg = $res->json('detail');
        $msg = is_string($msg) ? $msg : 'Error al agregar al carrito';

        return response()->json(['success' => false, 'message' => $msg], $res->status());
    }

    $cartRes = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/cart/');
    $cartCount = $cartRes->successful()
        ? collect($cartRes->json())->sum('quantity')
        : 0;

    return response()->json([
        'success' => true,
        'cartCount' => $cartCount,
    ]);
})->middleware('auth');

Route::get('/carrito', function (Request $request) {
    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return redirect('/login');
    }

    $cartRes = Http::withToken($token)->acceptJson()->get(MacuinApi::url().'/cart/');
    if (! $cartRes->successful()) {
        return redirect('/catalogo')->withErrors(['cart' => 'No se pudo cargar el carrito.']);
    }

    $cartItems = MacuinApi::cartItemsFromApi($cartRes->json());

    $subtotal = $cartItems->sum(function ($item) {
        return (float) $item->autopart->price * (int) $item->quantity;
    });

    return view('carrito.index', [
        'cartItems' => $cartItems,
        'subtotal' => $subtotal,
    ]);
})->middleware('auth');

Route::post('/carrito/eliminar', function (Request $request) {
    $request->validate(['id' => 'required|integer']);

    $token = $request->session()->get('jwt_token');
    if (! $token) {
        return response()->json(['success' => false], 401);
    }

    $res = Http::withToken($token)->delete(MacuinApi::url().'/cart/items/'.$request->id);

    if (! $res->successful()) {
        return response()->json(['success' => false, 'message' => 'No se pudo eliminar'], $res->status());
    }

    return response()->json(['success' => true]);
})->middleware('auth');
