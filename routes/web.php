<?php

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

Route::view('upload', 'upload.chunks')->name('upload.chunks.form');

Route::post('upload-chunks', 'UploadChunksController@uploadFile')->name('upload.chunks.handler');
