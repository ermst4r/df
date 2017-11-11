<?php

Route::group(['middleware' => 'web', 'prefix' => 'categorytable', 'namespace' => 'Modules\Category\Http\Controllers'], function()
{
    //Route::resource('/', 'CategoryController');
    Route::post('/store', 'CategoryController@store')->name('categorytable.store');
    Route::get('/create', 'CategoryController@create')->name('categorytable.create');

});
