<?php

Route::group(['middleware' => 'web', 'prefix' => 'firstmodule', 'namespace' => 'Modules\FirstModule\Http\Controllers'], function()
{
    Route::get('/first-module', 'FirstModuleController@index');
});
