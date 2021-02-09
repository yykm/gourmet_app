<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Shop;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StorePhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    public function __construct()
    {
        // 認証されていない場合422エラー
        // 写真一覧取得, ダウンロードリンク取得に関しては認証不要とする
        $this->middleware('auth')->except(['index', 'download']);
    }


    /**
     * 写真ダウンロード
     * @param Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function download(Photo $photo)
    {
        // 写真の存在チェック
        if (!Storage::cloud()->exists($photo->filename)) {
            abort(404);
        }

        // Content-Dispositionヘッダを指定することによって、
        // ブラウザにダウンロードさせるために保存ダイアログを開くよう指示
        $disposition = 'attachment; filename="' . $photo->filename . '"';
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => $disposition,
        ];

        return response(Storage::cloud()->get($photo->filename), 200, $headers);
    }

    /**
     * 写真一覧
     */
    public function index(Request $request)
    {
        // ある店舗に関する写真と、その写真を投稿したユーザ情報を取得
        $photos = Photo::with(['user'])
            ->where('shop_id', '=', $request->shop_id)
            ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        return $photos;
    }

    /**
     * 写真投稿
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePhoto $request)
    {
        // 投稿写真の拡張子を取得する
        $extension = $request->photo->extension();

        // json文字列からオブジェクトへ変換
        $shop = json_decode($request->shop);

        // 新しく写真インスタンスを生成
        $photo = new Photo();

        // "インスタンス生成時に割り振られたランダムなID値　+ 拡張子"のファイル名とする
        $photo->filename = $photo->id . '.' . $extension;

        // 店舗IDを設定
        $photo->shop_id = $shop->id;

        // S3に写真を保存する
        // 第4引数の'public'はファイルを公開状態で保存するため
        Storage::cloud()
            ->putFileAs('', $request->photo, $photo->filename, 'public');

        // データベースエラー時にファイル削除を行うため
        // トランザクションを利用する
        DB::beginTransaction();
        try {
            // 親テーブルの主キーが登録されていなければ登録
            Shop::firstOrCreate(
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

            Auth::user()->photos()->save($photo);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // DBとの不整合を避けるためアップロードしたファイルを削除
            Storage::cloud()->delete($photo->filename);
            throw $exception;
        }

        // リソースの新規作成なので
        // レスポンスコードは201(CREATED)を返却する
        return response($photo, 201);
    }

    /**
     * ユーザことの投稿写真情報取得
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getByUser(Request $request)
    {
        if (is_null($request->user_id)) {
            abort(404);
        }

        $photos = User::with(['photos'])
            ->find($request->user_id)
            ->photos()
            ->with(['shop'])
            ->get();

        return $photos;
    }
}
