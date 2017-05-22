<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\Http\Controllers\Admin;

Route::get('/forgot','ForgotPassword@index');
Route::post('/forgot/email','ForgotPassword@checkEmail');
Route::get('/forgot/attemp/{email}/{token}','ForgotPassword@attemp');
Route::post('/forgot/password/reset','ForgotPassword@reset');
Route::get('/ops/{permission}','DontGetPermission@index');

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/', 'HomeController@index');

Route::group(['middleware'=>'permission:base-admin','prefix'=>'admin'],function(){
	Route::get('/','Admin\AdminController@index');
	Route::get('/user','Admin\AdminUser@list_data');
	Route::get('/user/get','Admin\AdminUser@get_user');

	Route::group(['middleware'=> 'permission:open-comment'],function(){
		Route::get('/comment/get','Admin\AdminCommentController@index');
		Route::get('/comment/delete/{comment}','Admin\AdminCommentController@delete_comment');
		Route::get('/comment/restore/{comment}','Admin\AdminCommentController@restore_comment');
		Route::get('/comment/prepare_edit/{comment}','Admin\AdminCommentController@prepare_edit');
		Route::get('/comment/edit/{comment}','Admin\AdminCommentController@edit');

		Route::get('/comment/get/{page?}/{total?}','Admin\AdminCommentController@index');
		
	});

	Route::group(['middleware'=>'permission:open-comic'],function(){
		Route::get('/comic','Admin\AdminComicController@index');
		Route::get('/comic/create','Admin\AdminComicController@create')->middleware('permission:create-comic');

		Route::post('/comic/insert','Admin\AdminComicController@insert')->middleware('permission:create-comic');
		Route::get('/comic/search/{is_publish?}/{current_page?}/{total?}','Admin\AdminComicController@search')
		->middleware('permission:open-comic');
		Route::get('/comic/{comic_id}/delete','Admin\AdminComicController@delete_comic')->middleware('permission:delete-comic');
		Route::get('/comic/{comic_id}/prepare_edit','Admin\AdminComicController@prepareEdit')->middleware('permission:edit-comic');
		Route::post('/comic/{comic_id}/edit','Admin\AdminComicController@edit_comic')->middleware('permission:edit-comic');
		Route::post('/comic/{comic_id}/add','Admin\AdminComicController@add_page');
		Route::get('/comic/{comic_id}/publish/{val}','Admin\AdminComicController@setPublishComic')->middleware('permission:publish-comic');
		Route::get('/comic/{comic_id}','Admin\AdminPageController@show_all_page');
		Route::get('/comic/{comic_id}/page','Admin\AdminPageController@show_all_page');
		Route::get('/comic/{comic_id}/page/{page_id}','Admin\AdminPageController@edit_page');
		Route::get('/comic/{comic_id}/page/{page_id}/edit','Admin\AdminPageController@edit_page');
		Route::get('/comic/{comic_id}/page/{page_id}/delete','Admin\AdminPageController@delete_page');
	});




	Route::get('/author','Admin\AdminAuthorController@index');//go to author management page
	Route::post('/author/create','Admin\AdminAuthorController@create');//create author
	Route::get('/author/{author}/delete','Admin\AdminAuthorController@delete'); // delete author by id
	Route::get('/author/{author}/edit','Admin\AdminAuthorController@edit');
	Route::post('/author/{author}/update','Admin\AdminAuthorController@update');


	Route::get('/category','Admin\AdminCategoryController@index');//go to author management page
	Route::post('/category/create','Admin\AdminCategoryController@create');//create author
	Route::get('/category/{category}/delete','Admin\AdminCategoryController@delete'); // delete author by id
	Route::get('/category/{category}/edit','Admin\AdminCategoryController@edit');
	Route::post('/category/{category}/update','Admin\AdminCategoryController@update'); 

	Route::group(['middleware'=>'permission:open-role'],function(){
		Route::get('/role','Admin\RoleController@create')->middleware('permission:create-role');
		Route::get('/role/create','Admin\RoleController@create')->middleware('permission:create-role');
		Route::get('/role/insert/{dummy}','Admin\RoleController@insert')->middleware('permission:create-role');
		Route::get('/role/{role}/delete','Admin\RoleController@delete')->middleware('permission:delete-role');
		Route::get('/role/assign','Admin\RoleController@assign')->middleware('permission:assign-role');
		Route::get('/role/assign/{user}','Admin\RoleController@assign_to_user')->middleware('permission:assign-role');
		Route::get('/role/{user}/unassign/{role}','Admin\RoleController@unassign_to_user')->middleware('permission:unassign-role');
	});
});
Route::post('/apply/upload','FileManagerController@upload');
Route::get('/generateThumb/{comic}','InputByDirectoryController@generateThumb');
Route::get('/file/test','FileManagerController@test');

// Route::get('/db/inputComic/dvd_1','InputByDirectoryController@dvd_1');
// Route::get('/db/inputComic/dvd_2','InputByDirectoryController@dvd_2');
