<?php

use Illuminate\Http\Request;

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

/* this is public api */
/* this api will be accessable via domain.com/user/{uri} */
Route::group(['middleware'=>'api','prefix'=>'user'],function(){
	Route::post('/register','UserController@register');
	Route::post('/login','UserController@login');
	Route::post('/edit','UserController@update')->middleware('jwtAuth');
});

Route::group(['middleware'=>'api','prefix'=>'comic'],function(){
	//cek jwt 
	Route::group(['middleware'=>'jwtAuth'],function(){
		//get comic all
		Route::post('get_all','ComicController@get_all');
		
		//get comment by comic_id
		Route::post('comment/get/{comic_id}','CommentController@get_comment');

		//edit and delete reply and comment//post reply and comment
		Route::post('comment/post','CommentController@post_comment');
		Route::post('comment/update','CommentController@update_comment');
		Route::post('comment/delete','CommentController@delete_comment');

		//favorite or anfavorite comic
		Route::post('favorite/set','ComicController@favorite');

		//get favorite user
		Route::post('favorite/get','ComicController@get_favorite');

		//get favorite user
		Route::post('rate/post','ComicController@post_rating');

		//get favorite user
		Route::post('page/get','PageController@get_page');


		Route::post('search','ComicController@search');
	});
	Route::group(['middleware'=>'headerAuth'],function(){
		Route::get('page/show','PageController@show');
	});
});

//add panel via admin
Route::post('comic/{comic_id}/page/{page_id}/add','ComicController@add_Panel');
Route::post('comic/{comic_id}/page/{page_id}/get_panel','ComicController@get_Panel');