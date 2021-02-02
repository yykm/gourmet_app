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
Route::get('/favorites/{shop_id}', 'FavoriteController@index')->name('favorite.like');

// いいね
Route::put('/favorites/{shop_id}', 'FavoriteController@like')->name('favorite.like');

// いいね解除
Route::delete('/favorites/{shop_id}', 'FavoriteController@unlike');
