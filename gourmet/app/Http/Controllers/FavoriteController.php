<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use Illuminate\Support\Facades\Auth;
use Log;

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
        Shop::firstOrCreate(['id' => $shop_id]);

        $favorite = Shop::where('id', $shop_id)->with(['favorites'])->first();

        return $favorite;
    }

    /**
     * いいね
     * @param string $shop_id
     * @return array
     */
    public function like(string $shop_id)
    {
        // 店舗がない場合は作成
        Shop::firstOrCreate(['id' => $shop_id]);

        $shop = Shop::where('id', $shop_id)->with('favorites')->first();

        // ある店舗に紐づくいいねのうちログイン中のユーザに関する中間テーブルのデータ行を削除してから追加（いいねを１ユーザ１個に限定するため）
        $shop->favorites()->detach(Auth::user()->id);
        $shop->favorites()->attach(Auth::user()->id);

        return ["shop_id" => $shop_id];
    }


    /**
     * いいね解除
     * @param string $shop_id
     * @return array
     */
    public function unlike(string $shop_id)
    {
        // 店舗がない場合は作成
        if (!Shop::where('id', $shop_id)) {
            Shop::firstOrCreate(['id' => $shop_id]);
        }

        $shop = Shop::where('id', $shop_id)->with('favorites')->first();

        // ある店舗に関するログイン中のユーザの中間テーブルの１いいねを削除
        $shop->favorites()->detach(Auth::user()->id);

        return ["shop_id" => $shop_id];
    }
}
