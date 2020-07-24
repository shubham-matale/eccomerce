<?php

Route::group(['prefix' => 'v1', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin'], function () {
    Route::apiResource('permissions', 'PermissionsApiController');

    Route::apiResource('roles', 'RolesApiController');

    Route::apiResource('users', 'UsersApiController');

//    Route::apiResource('products', 'ProductsApiController');
});

Route::group(['prefix' => 'v1', 'as' => 'users.', 'namespace' => 'Api\V1\User'], function () {
    Route::apiResource('productCategory','ProductCategoryController');
    Route::apiResource('products', 'ProductsApiController');

    //customer
    Route::get('customer/isNewUser','CustomerApiController@isNewUser')->name('customer.isNewUser');

    Route::post('customer/createNewUser','CustomerApiController@createNewUser')->name('customer.createNewUser');

    Route::get('customer/getAddress','CustomerApiController@getAddress')->name('customer.getAddress');

    Route::post('customer/createAddress','CustomerApiController@createAddress')->name('customer.createAddress');

    Route::post('customer/getRate','OrderController@getRate')->name('customer.getRate');

    Route::post('customer/placeOrder','OrderController@placeOrder')->name('customer.placeOrder');


});
