<?php

Route::group(['prefix' => 'v1', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin'], function () {
    Route::apiResource('permissions', 'PermissionsApiController');

    Route::apiResource('roles', 'RolesApiController');

    Route::apiResource('users', 'UsersApiController');

    Route::post('login','DeliveryBoyController@login');

    Route::get('getOrdersToDeliver','DeliveryBoyController@getOrders')->middleware(['jwt.verify']);

    Route::get('pastOrdersDelivered','DeliveryBoyController@pastOrdersDelivered')->middleware(['jwt.verify']);

    Route::get('deliverOrder','DeliveryBoyController@deliverOrder')->middleware(['jwt.verify']);

    Route::post('changePassword','DeliveryBoyController@changePassword')->middleware(['jwt.verify']);

//    Route::apiResource('products', 'ProductsApiController');
});

Route::group(['middleware'=>'cors','prefix' => 'v1', 'as' => 'users.', 'namespace' => 'Api\V1\User'], function () {
    Route::apiResource('productCategory','ProductCategoryController');
    Route::apiResource('products', 'ProductsApiController');

    //customer
    Route::get('customer/getCustomProducts','ProductsApiController@getCustomProducts')->name('customer.getCustomProducts');

    Route::get('customer/isNewUser','CustomerApiController@isNewUser')->name('customer.isNewUser');

    Route::post('customer/createNewUser','CustomerApiController@createNewUser')->name('customer.createNewUser');

    Route::get('customer/getAddress','CustomerApiController@getAddress')->name('customer.getAddress');

    Route::post('customer/createAddress','CustomerApiController@createAddress')->name('customer.createAddress');

    Route::post('customer/getRate','OrderController@getRate')->name('customer.getRate');

    Route::post('customer/placeOrder','OrderController@placeOrder')->name('customer.placeOrder');

    Route::post('customer/checkPaymentStatus','OrderController@checkPaymentStatus')->name('customer.checkPaymentStatus');

    Route::get('customer/getAllOrders','OrderController@getAllOrders')->name('customer.getAllOrders');

    Route::get('customer/getInvoice','OrderController@getInvoice')->name('customer.getInvoice');

    Route::post('customer/createTicket','TicketController@addTicket');

    Route::post('customer/createCustomTicket','TicketController@createCustomTicket');

    Route::post('customer/addTicketMessage','TicketController@addMessage');

    Route::get('customer/getAllTickets','TicketController@getAllTickets');

    Route::get('customer/getAllTicketMessage','TicketController@getAllTicketMessage');


});
