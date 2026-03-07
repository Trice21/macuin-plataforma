<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    return redirect('/dashboard');
});

// Recuperar contraseña
Route::get('/recuperar-password', function () {
    return view('auth.recuperar-password');
});

// Registro
Route::get('/registro', function () {
    return view('auth.registro');
});

// Dashboard externo
Route::get('/dashboard', function () {
    return view('dashboard.index');
});

// Perfil externo
Route::get('/perfil', function () {
    return view('perfil.index');
});

// Configuración del perfil
Route::get('/perfil/configuracion', function () {
    return view('perfil.configuracion');
});

// Historial de pedidos
Route::get('/pedidos', function () {
    return view('pedidos.historial');
});

//Crear pedido externo
Route::get('/pedidos/crear', function () {
    return view('pedidos.crear');
});

// Detalle de pedido externo
Route::get('/pedidos/{id}', function (string $id) {
    return view('pedidos.detalle', ['id' => $id]);
});
//Catálogo externo
Route::get('/catalogo', function () {
    return view('catalogo.index');
});

// Detalle de autoparte
Route::get('/catalogo/{id}', function (string $id) {
    return view('catalogo.detalle', ['id' => $id]);
});
