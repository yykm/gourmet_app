<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
|--------------------------------------------------------------------------
| ユーザ定義
|--------------------------------------------------------------------------
|
| 以下は追加したルート
|
*/


// グルメサーチAPI取得
Route::post('/search', 'SearchController@search')->name('search');

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインユーザー取得
Route::get('/user', function () {
    return Auth::user();
})->name('user');

// 写真投稿
Route::post('/photos', 'PhotoController@create')->name('photo.create');

// 写真一覧
Route::get('/photos', 'PhotoController@index')->name('photo.index');

// コメント投稿
Route::post('/comments', 'CommentController@create')->name('comment.create');

// コメント一覧
Route::get('/comments', 'CommentController@index')->name('comment.index');

// いいねに関する情報取得
Route::get('/favorites/byUser', 'FavoriteController@getByUser')->name('favorite.getByUser');

Route::get('/favorites/{shop_id}', 'FavoriteController@index')->name('favorite.index');

// いいね
Route::post('/favorites', 'FavoriteController@like')->name('favorite.like');

// いいね解除
Route::delete('/favorites/{shop_id}', 'FavoriteController@unlike');

// 予約機能
Route::post('/reserve', 'ReserveController@reserve')->name('reserve.reserve');

// ユーザごとの予約機能情報取得
Route::get('/reserve/byUser', 'ReserveController@getByUser')->name('reserve.getByUser');
Route::get('/comments/byUser', 'CommentController@getByUser')->name('comment.getByUser');
Route::get('/photos/byUser', 'PhotoController@getByUser')->name('photo.getByUser');
