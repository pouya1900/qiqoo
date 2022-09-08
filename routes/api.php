<?php
Route::group( [ 'prefix' => '/general', 'namespace' => 'General' ], function () {
	Route::get( '/country', 'CountryController@getAllCountriesByCitites' );
	Route::post( '/upload-image', 'ImageController@store' )->middleware( [ 'jwtAuth' ] );
	Route::get( '/delete-image/{image}', 'ImageController@delete' )->middleware( [ 'jwtAuth' ] );
} );

Route::fallback( 'Controller@fallback' );

Route::group( [ 'prefix' => '/blog-category', 'namespace' => 'Blog' ], function () {
	Route::get( '/blog', 'BlogController@index' );
	Route::get( '/blog/{blog}', 'BlogController@show' );
	Route::get( '/category', 'BlogController@getCategories' );
	Route::get( '/category/{blogCategory}', 'BlogController@getByCategory' );

	Route::post( '/comment/{blog}', 'BlogController@addComment' );
	// ->middleware( [ 'hasPermission:loginUser.*' ] ) //;
} );

Route::group( [ 'prefix' => '/ads-category', 'namespace' => 'Ads' ], function () {
	Route::post( '/ads', 'AdsController@index' )->middleware( [ 'optionalJwtAuth' ] );
	Route::get( '/ads/{ads}', 'AdsController@show' )->middleware( [ 'optionalJwtAuth' ] );

//	Route::middleware( [ 'hasPermission:loginUser.*' ] )->group( function () {

		Route::post( '/store_ads', 'AdsController@store' )->middleware( [ 'jwtAuth' ] );
		Route::put( '/ads/{ads}', 'AdsController@update' )->middleware( [ 'jwtAuth' ] );
		Route::post( '/comment/{ads}', 'AdsController@addComment' );
		Route::post( '/score/{ads}', 'AdsController@addScore' )->middleware( [ 'jwtAuth' ] );

//	} );


	Route::post( '/report/{ads}', 'AdsController@addReport' );
	Route::post( '/bookmark/add/{ads}', 'AdsController@addBookmark' )->middleware( [ 'jwtAuth' ] );
	Route::get( '/category', 'AdsController@getCategories' );
	Route::get( '/attribute/{adsCategory}', 'AdsController@getAttributesByCategory' )->middleware( [ 'jwtAuth' ] );
	Route::get( '/report-type', 'AdsController@getReportTypes' );
	Route::post( '/bookmark/remove/{ads}', 'AdsController@removeBookmark' )->middleware( [ 'jwtAuth' ] );
	Route::get( '/bookmark', 'AdsController@showBookmarks' )->middleware( [ 'jwtAuth' ] );
} );

Route::group( [ 'prefix' => '/home', 'namespace' => 'Home' ], function () {

	Route::get( '/', 'HomeController@index' )->middleware( [ 'jwtAuth' ] );
	Route::get( '/{app_view}', 'HomeController@show' )->middleware( [ 'jwtAuth' ] );
} );

Route::group( [ 'prefix' => '/support', 'namespace' => 'support' ], function () {
	Route::post( '/store', 'SupportController@store' );
} );

Route::group( [ 'prefix' => '/comment', 'namespace' => 'Comment' ], function () {
	Route::get( '/{type}/{id}', 'CommentController@index' );
} );

Route::group( [ 'prefix' => '/auth', 'namespace' => 'Auth' ], function () {
	Route::post( 'send-otp', 'AuthController@sendOtp' );
	Route::post( 'login', 'AuthController@login' );
	Route::post( 'guest', 'AuthController@guest' );

	Route::group( [ 'middleware' => 'jwtAuth' ], function () {
		Route::get( 'logout', 'AuthController@logout' );
	} );
} );

Route::group( [ 'middleware' => [ 'jwtAuth' ] ], function () {
	Route::group( [ 'prefix' => '/profile', 'namespace' => 'Profile' ], function () {
		Route::get( '/', 'UsersController@showProfile' );
		Route::get( '/ads', 'UsersController@showAds' );
		Route::put( '/', 'UsersController@updateProfile' );
		Route::post( '/', 'UsersController@createProfile' );
	} );
} );


