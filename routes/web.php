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

    //yadi/customized products

    Route::resource('yadiProducts', 'CustomizedProductController');

    Route::post('yadiProducts/addVariable', 'CustomizedProductController@variableCreate')->name('yadiProducts.addVariable');

    Route::get('yadiProducts/editVariable/{id}/edit','CustomizedProductController@variableEdit')->name('yadiProducts.editVariable');

    Route::put('yadiProducts/updateVariable/{productVariable}','CustomizedProductController@variableUpdate')->name('yadiProducts.updateVariable');

    Route::delete('yadiProducts/variableDestroy/{id}', 'CustomizedProductController@variableDestroy')->name('yadiProducts.variableDestroy');

    Route::post('yadiProducts/addImage', 'CustomizedProductController@imageCreate')->name('yadiProducts.addImage');

    Route::delete('yadiProducts/ImageDestroy/{id}', 'CustomizedProductController@imageDestroy')->name('yadiProducts.imageDestroy');

    Route::get('yadiProducts/addFormula/{id}/addFormula','CustomizedProductController@showFormula')->name('yadiProducts.addFormula');

    Route::post('yadiProducts/saveFormula', 'CustomizedProductController@saveFormula')->name('yadiProducts.saveFormula');

    Route::delete('yadiProducts/deleteIngradientFromFormula/{id}', 'CustomizedProductController@deleteIngradientFromFormula')->name('yadiProducts.deleteIngradientFromFormula');
    //product Image
    Route::post('products/addImage', 'ProductsController@imageCreate')->name('products.addImage');

    Route::delete('products/ImageDestroy/{id}', 'ProductsController@imageDestroy')->name('products.imageDestroy');

    //product
    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');

    Route::resource('products', 'ProductsController');


    //banner
    Route::resource('banners', 'BannersController');

    //Orders

    Route::resource('orders', 'OrderController');

    Route::post('orders/assignDeliveryBoy/{id}', 'OrderController@assignDeliveryBoy')->name('orders.assignDeliveryBoy');

    //Coupons

    Route::delete('coupons/destroy', 'CouponCodeController@massDestroy')->name('coupons.massDestroy');

    Route::resource('coupons', 'CouponCodeController');

    //Tickets

    Route::get('tickets','TicketsController@index')->name('tickets.index');

    Route::get('tickets/assignToMe/{id}','TicketsController@assignToMe')->name('tickets.assign');

    Route::get('tickets/closeTicket/{id}','TicketsController@close')->name('tickets.close');

    Route::get('tickets/openTicket/{id}','TicketsController@open')->name('tickets.open');

    Route::post('tickets/addMessage','TicketsController@addMessage')->name('tickets.addMessage');

    Route::get('tickets/getMessage/{id}','TicketsController@getMessage')->name('tickets.getMessage');

});
Route::redirect('/home', 'admin/');

Route::any ( 'sendemail', function () {

        $data [] = '';
    Mail::send ( 'email', $data, function ($message) {

//        $message->from ( 'donotreply@demo.com', 'Just Laravel' );

        $message->to ( "mataleshubham@gmail.com"  )->subject ( 'Just Laravel demo email using SendGrid' );
    } );
//    return Redirect::back ()->withErrors ( [
//        'Your email has been sent successfully'
//    ] );
} );
