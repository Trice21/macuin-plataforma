<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', function () {
    return view('auth.login');
});

// Registro
Route::get('/registro', function () {
    return view('auth.registro');
});

// Dashboard externo
Route::get('/dashboard', function () {
    return view('dashboard.index');
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