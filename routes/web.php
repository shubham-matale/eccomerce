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

    //products Variable

    Route::post('products/addVariable', 'ProductsController@variableCreate')->name('products.addVariable');

    Route::get('products/editVariable/{id}/edit','ProductsController@variableEdit')->name('products.editVariable');

    Route::put('products/updateVariable/{productVariable}','ProductsController@variableUpdate')->name('products.updateVariable');

    Route::delete('products/variableDestroy/{id}', 'ProductsController@variableDestroy')->name('products.variableDestroy');

    //product Image
    Route::post('products/addImage', 'ProductsController@imageCreate')->name('products.addImage');

    Route::delete('products/ImageDestroy/{id}', 'ProductsController@imageDestroy')->name('products.imageDestroy');

    //product
    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');

    Route::resource('products', 'ProductsController');

    //Coupons

    Route::delete('coupons/destroy', 'CouponCodeController@massDestroy')->name('coupons.massDestroy');

    Route::resource('coupons', 'CouponCodeController');

});
Route::redirect('/home', 'admin/');
