<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Home']);
});

Route::get('/users', function () {
    return response()->json(['message' => 'List of users']);
});

Route::post('/users', function () {
    return response()->json(['message' => 'User created']);
});

Route::get('/products', function () {
    return response()->json(['message' => 'List of products']);
});

Route::put('/products/{id}', function ($id) {
    return response()->json(['message' => "Product {$id} updated"]);
});

Route::delete('/products/{id}', function ($id) {
    return response()->json(['message' => "Product {$id} deleted"]);
});