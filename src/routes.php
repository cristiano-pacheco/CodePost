<?php

Route::group([

    'prefix' => 'admin/posts',
    'as' => 'admin.posts.',
    'namespace' => 'CodePress\CodePost\Controllers',
    'middleware' => ['web']

], function () {

    Route::get('', 'AdminPostsController@index')
        ->name('index');

    Route::get('/create', 'AdminPostsController@create')
        ->name('create');

    Route::post('/store', 'AdminPostsController@store')
        ->name('store');

    Route::get('/{id}/edit/', 'AdminPostsController@edit')
        ->name('edit');

    Route::post('/{id}/update', 'AdminPostsController@update')
        ->name('update');

    Route::get('/{id}/delete/', 'AdminPostsController@delete')
        ->name('delete');

});