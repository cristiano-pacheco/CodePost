<?php

Route::group([

    'prefix' => 'admin/posts',
    'as' => 'admin.posts.',
    'namespace' => 'CodePress\CodePost\Controllers',
    'middleware' => ['web']

], function () {



});