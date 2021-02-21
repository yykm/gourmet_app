<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use App\User;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        // 認証されていない場合422エラー
        // 写真一覧取得, ダウンロードリンク取得に関しては認証不要とする
        $this->middleware('auth')->except(['index']);
    }


    /**
     * いいね取得
     * @param string $shop_id
     * @return array
     */
    public function index(string $shop_id)
    {
        if(!is_null(Shop::find($shop_id))){
            $favorite = Shop::where('id', $shop_id)->with(['favorites'])->first();
        } else{
            $favorite = null;
        }
        
        return $favorite;
    }

    /**
     * いいね
     * @param Illuminate\Http\Request; $request
     * @return array
     */
    public function like(Request $request)
    {
        // json文字列からオブジェクトへ変換
        $shop = json_decode($request->shop);

        // 店舗がない場合は作成
        $shop = Shop::firstOrCreate(
            [
                'id' => $shop->id
            ],
            [
                'name' => $shop->name,
                'kana' => $shop->kana,
                'image' => $shop->image_l,
                'address' => $shop->address,
                'access' => $shop->access,
                'catch' => $shop->catch,
                'open' => $shop->open,
                'category' => $shop->category,
                'average' => $shop->average
            ]
        );

        $shop = Shop::where('id', $shop->id)->with('favorites')->first();

        // ある店舗に紐づくいいねのうちログイン中のユーザに関する中間テーブルのデータ行を削除してから追加（いいねを１ユーザ１個に限定するため）
        $shop->favorites()->detach(Auth::user()->id);
        $shop->favorites()->attach(Auth::user()->id);

        return ["shop_id" => $shop->id];
    }


    /**
     * いいね解除
     * @param string $shop_id
     * @return array
     */
    public function unlike(string $shop_id)
    {
        $shop = Shop::where('id', $shop_id)->with('favorites')->first();

        // ある店舗に関するログイン中のユーザの中間テーブルの１いいねを削除
        $shop->favorites()->detach(Auth::user()->id);

        return ["shop_id" => $shop_id];
    }

    /**
     * ユーザことのお気に入り情報取得
     * @return \Illuminate\Http\Response
     */
    public function getByUser()
    {
        // ログインしているユーザ情報に紐づくお気に入り情報を取得
        $favorites = User::with(['favorites'])
            ->find(Auth::id())
            ->favorites()
            ->with(['shop'])
            ->get();

        return $favorites;
    }
}
