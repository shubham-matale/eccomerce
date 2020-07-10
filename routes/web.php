<?php

// Route::redirect('/', '/login');
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/home', 'HomeController@index')->name('home');


Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');

    Route::resource('permissions', 'PermissionsController');

    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');

    Route::resource('roles', 'RolesController');

    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');

    Route::resource('users', 'UsersController');

    Route::delete('productsCategory/destroy', 'ProductsController@massDestroy')->name('productsCategory.massDestroy');

    Route::resource('productsCategory', 'ProductCategoryController');

    Route::delete('productSubCategory/destroy', 'ProductSubCategoryController@massDestroy')->name('productsSubCategory.massDestroy');

    Route::resource('productsSubCategory', 'ProductSubCategoryController');

    Route::delete('products/destroy', 'ProductCategoryController@massDestroy')->name('products.massDestroy');

    Route::resource('products', 'ProductsController');
});
Route::redirect('/home', 'admin/');
