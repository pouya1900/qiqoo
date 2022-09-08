<?php
Route::middleware( [ 'adminGuest' ] )->group( function () {
	Route::get( '/login', 'Auth\LoginController@showLoginForm' )->name( 'login' );
	Route::post( '/login', 'Auth\LoginController@login' );
	Route::get( '/', 'Auth\LoginController@index' )->name( 'index' );
} );

Route::middleware( [ 'adminAuth' ] )->group( function () {
	$actions = [ 'active', 'trash', 'delete', 'publish' ];
	foreach ( $actions as $action ) {
		Route::get( "/$action/{model_type}/{id}", "AjaxTableOptionsController@$action" );
	}

	Route::get( 'dashboard', 'Auth\LoginController@dashboard' )->name( 'dashboard' );


	Route::post( 'upload-image', 'ImageUploaderController@upload_image' )->name( 'upload_image' );
	Route::post( 'upload-video', 'ImageUploaderController@upload_video' )->name( 'upload_video' );


	Route::middleware( [ 'hasPermission:ads.*' ] )->group( function () {
		Route::get( 'ads/trash/{ads}', 'AdsController@trash' )->name( 'ads.trash' );
		Route::get( 'ads/publish/{ads}', 'AdsController@publish' )->name( 'ads.publish' );
		Route::get( 'ads/trashed', 'AdsController@trashed' )->name( 'ads.trashed' );
		Route::get( 'ads/unpublished', 'AdsController@unPublished' )->name( 'ads.unpublished' );
		Route::resource( 'ads', 'AdsController', [ 'only' => [ 'index', 'show' ] ] );

		Route::get( 'app-view/trash/{appView}', 'AppViewController@trash' )->name( 'app-view.trash' );
		Route::get( 'app-view/publish/{appView}', 'AppViewController@publish' )->name( 'app-view.publish' );
		Route::resource( 'app-view', 'AppViewController', [ 'except' => [ 'destroy' ] ] );
		Route::resource( 'app-view-type', 'AppViewTypeController', [ 'except' => [ 'destroy' ] ] );


	} );

	Route::middleware( [ 'hasPermission:adsAttribute.*' ] )->group( function () {
		Route::get( 'ads-attribute/trashed', 'AdsAttributeDescriptionController@trashed' )->name( 'ads-attribute.trashed' );
		Route::get( 'ads-attribute/delete/{adsAttributeDescription}', 'AdsAttributeDescriptionController@doDelete' )->name( 'ads-attribute.delete' );
		Route::resource( 'ads-attribute', 'AdsAttributeDescriptionController', [ 'except' => [ 'destroy' ] ] );
	} );

	Route::middleware( [ 'hasPermission:unit.*' ] )->group( function () {
		Route::get( 'unit/delete/{unit}', 'UnitController@doDelete' )->name( 'unit.delete' );
		Route::resource( 'unit', 'UnitController', [ 'except' => [ 'destroy' ] ] );
	} );

	Route::middleware( [ 'hasPermission:category.*' ] )->group( function () {
		Route::get( 'category/delete/{category}', 'AdsCategoryController@doDelete' )->name( 'category.delete' );
		Route::resource( 'category', 'AdsCategoryController', [ 'except' => [ 'destroy' ] ] );
	} );

	Route::middleware( [ 'hasPermission:blog.*' ] )->group( function () {
		Route::get( 'blog/trash/{blog}', 'BlogController@trash' )->name( 'blog.trash' );
		Route::get( 'blog/publish/{blog}', 'BlogController@publish' )->name( 'blog.publish' );
		Route::get( 'blog/trashed', 'BlogController@trashed' )->name( 'blog.trashed' );
		Route::get( 'blog/unpublished', 'BlogController@unPublished' )->name( 'blog.unpublished' );
		Route::resource( 'blog', 'BlogController', [ 'except' => [ 'destroy' ] ] );
	} );

	Route::middleware( [ 'hasPermission:blogCategory.*' ] )->group( function () {
		Route::get( 'blog-category/delete/{category}', 'BlogCategoryController@doDelete' )->name( 'blog-category.delete' );
		Route::resource( 'blog-category', 'BlogCategoryController', [ 'except' => [ 'destroy' ] ] );
	} );


	Route::middleware( [ 'hasPermission:blog.*' ] )->group( function () {
		Route::resource( 'city', 'CityController', [ 'except' => [ 'destroy' ] ] );
	} );



	Route::middleware( [ 'hasPermission:comment.*' ] )->group( function () {
		Route::get( 'comments/delete/{comment}', 'CommentController@doDelete' )->name( 'comment.delete' );
		Route::get( 'comment/publish/{comment}', 'CommentController@reversePublishState' )->name( 'comment.reversePublishState' );
		Route::resource( 'comment', 'CommentController', [ 'only' => [ 'index', 'show' ] ] );
	} );

	Route::middleware( [ 'hasPermission:payment.*' ] )->group( function () {
		Route::get( 'payment/delete/{id}', 'PaymentController@doDelete' )->name( 'payment.delete' );
		Route::resource( 'payment', 'PaymentController', [ 'except' => [ 'destroy' ] ] );
	} );

	Route::middleware( [ 'hasPermission:support.*' ] )->group( function () {
		Route::get( '/support/delete/{support}', 'SupportController@doDelete' )->name( 'support.delete' );
		Route::resource( '/support', 'SupportController', [ 'only' => [ 'index', 'show' ] ] );
	} );

	Route::middleware( [ 'hasPermission:role.*' ] )->group( function () {
		Route::get( 'role/delete/{role}', 'RoleController@doDelete' )->name( 'role.delete' );
		Route::resource( 'role', 'RoleController', [ 'except' => [ 'destroy' ] ] );
	} );

	Route::middleware( [ 'hasPermission:report.*' ] )->group( function () {
		Route::get( 'report/delete/{report}', 'ReportController@doDelete' )->name( 'report.delete' );
		Route::resource( 'report', 'ReportController', [ 'only' => [ 'index', 'show' ] ] );
	} );

	Route::get( '/profile/{id}', 'UsersController@showProfile' )->name( 'profile' );
	Route::middleware( [ 'hasPermission:user.*' ] )->group( function () {
		Route::get( '/user-all/{role?}', 'UsersController@getAllUsers' )->name( 'user.all' );
		Route::get( '/user-trashed/{role?}', 'UsersController@trashed' )->name( 'user.trashed' );
		Route::get( '/user/trash/{id}', 'UsersController@trash' )->name( 'user.trash' );
		Route::resource( '/user', 'UsersController', [ 'except' => [ 'index', 'delete' ] ] );
	} );

	Route::get( '/logout', 'Auth\LoginController@logout' )->name( 'logout' );

	Route::get( '/logout-url', function () {
		return redirect( 'https://qiqooapp.com' );
	} )->name( 'logoutUrl' );
} );
