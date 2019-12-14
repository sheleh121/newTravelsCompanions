<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', 'Auth\RegisterController@create');
Route::post('/forgot', 'Auth\ForgotPasswordController@forgot');
Route::post('/login', 'Auth\LoginController@login');


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'Auth\LoginController@logout');
    Route::put('/register/confirm', 'Auth\ConfirmController@confirm');
    Route::get('/register/confirm/sms', 'Auth\ConfirmController@getSms');
    Route::get('/user', 'Auth\ConfirmController@user');
    Route::get('/user/info', 'Users\UserShowController@currentUser');
});


Route::middleware('auth:api', 'locked')->group(function () {
    Route::get('/users', 'Users\UserShowController@users');
    Route::get('/users/{user_id}', 'Users\UserShowController@User');
    Route::get('/users/{user_id}/avatar/small', 'Images\ImageShowController@userAvatar');
    Route::get('/users/{user_id}/travels/all', 'Users\UserTravelsController@all');
    Route::get('/users/{user_id}/travels/author', 'Users\UserTravelsController@author');

    Route::put('/user/location', 'Users\UserEditController@location');
    Route::put('/user/fio', 'Users\UserEditController@fio');
    Route::put('/user/phone', 'Users\UserEditController@phone');
    Route::post('/user/avatar', 'Users\UserEditController@avatar');
    Route::put('/user/email', 'Users\UserEditController@email');
    Route::put('/user/password', 'Auth\ResetPasswordController@reset');

    Route::get('/chat/rooms/', 'Chat\ChatRoomController@getForUser');
    Route::get('/chat/messages/room{room_id}', 'Chat\ChatMessageController@get');
    Route::get('/chat/messages/noseen', 'Chat\ChatMessageController@noSeen');
    Route::post('/chat/messages/', 'Chat\ChatMessageController@send');
    Route::post('/chat/messages/seen', 'Chat\ChatMessageController@seen');
    Route::post('/chat/rooms/new', 'Chat\ChatRoomController@dialogNew');

    Route::post('/notices/{notice_id}/seen', 'NoticeController@seen');
    Route::get('/notices', 'NoticeController@get');

    Route::get('/travels/{travel_id}/users/', 'Travels\TravelShowController@travelAllUsers');

    Route::post('/travels', 'Travels\NewController@create');
    Route::put('/travels/{travel_id}', 'Travels\EditController@Edit');
    Route::put('/travels/{travel_id}/success', 'Travels\EditController@success');
    Route::put('/travels/{travel_id}/invite', 'Travels\TravelUserController@invite');
    Route::put('/travels/{travel_id}/claim', 'Travels\TravelUserController@update');
    Route::post('/travels/{travel_id}/claim', 'Travels\TravelUserController@claim');

    Route::put('/travels/{travel_id}/images/decision', 'Images\ImageEditController@UpdateDecision');
    Route::post('/travels/{travel_id}/images', 'Images\ImageEditController@AddForTravel');

    Route::delete('/images/{image_id}', 'Images\ImageEditController@Delete');
});

//////////////////////////////////////////-НЕАВТОРИЗОВАННЫЕ-////////////////////////////////////////////////////////
Route::get('/travels', 'Travels\TravelShowController@travels');

Route::get('/travels/categories', 'Travels\TravelShowController@Categories');
Route::get('/travels/types', 'Travels\TravelShowController@Types');
Route::get('/travels/{travel_id}', 'Travels\TravelShowController@travel');

Route::get('/travels/{travel_id}/images', 'Images\ImageShowController@TravelImages');
Route::get('/travels/{travel_id}/images/{image_id}/small', 'Images\ImageShowController@TravelImageSmall');
Route::get('/travels/{travel_id}/images/{image_id}/full', 'Images\ImageShowController@TravelImageFull');

Route::get('/location/countries', 'Locations\LocationMainController@GetCountry');
Route::get('/location/region', 'Locations\LocationMainController@GetRegion');
Route::get('/location/cities', 'Locations\LocationMainController@GetCities');









