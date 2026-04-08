<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Autopart;

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

use Illuminate\Support\Facades\Http;

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Comunicarse con el API centralizado para obtener el JWT
    $response = Http::asForm()->post(env('API_URL', 'http://macuin-api:8000') . '/auth/login', [
        'username' => $request->email,
        'password' => $request->password,
    ]);

    if ($response->successful()) {
        $data = $response->json();
        
        // Almacenar el JWT token que devolvió FastAPI en la sesión
        $request->session()->put('jwt_token', $data['access_token']);
        
        // Autenticar internamente al usuario en Laravel para mantener compatibles las vistas
        $user = User::where('email', $request->email)->first();
        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Las credenciales proporcionadas no coinciden o fueron rechazadas por la red (JWT).',
    ])->onlyInput('email');
});

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Dashboard externo
Route::get('/dashboard', function () {
    $user = Auth::user();
    $activeOrders = Order::where('user_id', $user->id)
        ->whereIn('status', ['PENDING', 'PROCESSING', 'SHIPPED'])
        ->latest()
        ->get();
    
    $recentOrders = Order::where('user_id', $user->id)
        ->latest()
        ->limit(5)
        ->get();

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
Route::get('/pedidos', function () {
    $user = Auth::user();
    $orders = Order::where('user_id', $user->id)->latest()->get();
    return view('pedidos.historial', ['orders' => $orders]);
})->middleware('auth');

//Crear pedido externo
Route::get('/pedidos/crear', function () {
    $cartItems = \App\Models\CartItem::with('autopart')->where('user_id', auth()->id())->get();
    
    if ($cartItems->isEmpty()) {
        return redirect('/carrito');
    }

    $subtotal = $cartItems->sum(function($item) {
        return $item->autopart->price * $item->quantity;
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

// Detalle de pedido externo
Route::get('/pedidos/{id}', function (string $id) {
    $user = Auth::user();
    $order = Order::with('items.autopart')
        ->where('user_id', $user->id)
        ->where('id', $id)
        ->firstOrFail();
    return view('pedidos.detalle', ['order' => $order]);
})->middleware('auth');

//Catálogo externo
Route::get('/catalogo', function (Request $request) {
    $query = Autopart::query();

    if ($request->filled('q')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'ilike', '%' . $request->q . '%')
              ->orWhere('description', 'ilike', '%' . $request->q . '%');
        });
    }

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    $autoparts = $query->get();
    
    return view('catalogo.index', [
        'autoparts' => $autoparts,
        'activeSearch' => $request->q,
        'activeCategory' => $request->category
    ]);
})->middleware('auth');

// Detalle de autoparte
Route::get('/catalogo/{id}', function (string $id) {
    $autopart = Autopart::findOrFail($id);
    return view('catalogo.detalle', ['autopart' => $autopart]);
})->middleware('auth');

// Funcionalidad Carrito
Route::post('/carrito/agregar', function (Request $request) {
    $request->validate([
        'autopart_id' => 'required|integer',
        'quantity' => 'required|integer|min:1'
    ]);
    
    $item = \App\Models\CartItem::where('user_id', auth()->id())
        ->where('autopart_id', $request->autopart_id)
        ->first();
        
    if ($item) {
        $item->quantity += $request->quantity;
        $item->save();
    } else {
        \App\Models\CartItem::create([
            'user_id' => auth()->id(),
            'autopart_id' => $request->autopart_id,
            'quantity' => $request->quantity
        ]);
    }
    
    return response()->json([
        'success' => true,
        'cartCount' => \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity')
    ]);
})->middleware('auth');

Route::get('/carrito', function () {
    $cartItems = \App\Models\CartItem::with('autopart')->where('user_id', auth()->id())->get();
    
    $subtotal = $cartItems->sum(function($item) {
        return $item->autopart->price * $item->quantity;
    });

    return view('carrito.index', [
        'cartItems' => $cartItems,
        'subtotal' => $subtotal
    ]);
})->middleware('auth');

Route::post('/carrito/eliminar', function (Request $request) {
    $request->validate(['id' => 'required|integer']);
    \App\Models\CartItem::where('id', $request->id)->where('user_id', auth()->id())->delete();
    return response()->json(['success' => true]);
})->middleware('auth');

