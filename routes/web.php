<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// view listing
Route::get('/', function () {
    return view('listing',[
    'heading'=>'data list',
    'listing'=> [
            [
            'id'=>1,
            'title'=>'aaa',
            'data'=>'aaaaaaaaaaa aaaaaaaaaaa'
            ],
            [
            'id'=>2,
            'title'=>'aaa',
            'data'=>'aaaaaaaaaaa aaaaaaaaaaa'
            ],
            [
            'id'=>3,
            'title'=>'aaa',
            'data'=>'aaaaaaaaaaa aaaaaaaaaaa'
            ]
        ]
    ]);
});

// single listing
Route::get('/listing/{id}', function ($id) {
    return view('listing-detail',[
    'heading'=>'data list detail',
    'value'=> [
            'id'=>1,
            'title'=>'aaa',
            'data'=>'aaaaaaaaaaa aaaaaaaaaaa'
        ]
    ]);
});
