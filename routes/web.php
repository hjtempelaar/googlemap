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

Route::get('/','App\Http\Controllers\HeatmapController@index');
Route::get('/g50','App\Http\Controllers\G50HeatmapController@index');
Route::get('/festival','App\Http\Controllers\HeatmapController@festival');
Route::get('getfestivalheatmap','App\Http\Controllers\HeatmapController@festivalHeatMap');
Route::get('getg50heatmap','App\Http\Controllers\G50HeatmapController@g50HeatMap');


