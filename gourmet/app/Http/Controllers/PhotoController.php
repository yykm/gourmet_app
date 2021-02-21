<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Shop;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StorePhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\ExecutableFinder;

class PhotoController extends Controller
{
    public function __construct()
    {
        // 認証されていない場合422エラー
        // 写真一覧取得, ダウンロードに関しては認証不要とする
        $this->middleware('auth')->except(['index', 'download']);
    }


    /**
     * 写真ダウンロード
     * @param Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function download(Photo $photo)
    {
        // S3上の写真の存在チェック
        if (!Storage::cloud()->exists($photo->filename)) {
            abort(404, '指定された写真が存在しません。');
        }

        // Content-Dispositionヘッダを指定することによって、
        // ブラウザにダウンロードさせるために保存ダイアログを開くよう指示
        $disposition = 'attachment; filename="' . $photo->filename . '"';
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => $disposition,
        ];

        // 画像（バイナリファイル）を返却
        return response(Storage::cloud()->get($photo->filename), 200, $headers);
    }

    /**
     * 写真一覧
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        // ある店舗に関する写真と、その写真を投稿したユーザ情報を取得
        $photos = Photo::with(['user'])
            ->where('shop_id', $request->shop_id)
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

            // ユーザに紐づく写真データとして写真テーブルに保存
            Auth::user()->photos()->save($photo);
            
            // コミット
            DB::commit();
        } catch (Exception $e) {
            // ロールバック
            DB::rollBack();

            // DBとの不整合を避けるためアップロードしたファイルを削除
            Storage::cloud()->delete($photo->filename);

            // エラー箇所のファイル・行・メッセージをエラーログに残す
            Log::error(
                'File: ' . $e->getFile() . "\n" .
                    'Line: ' . $e->getLine() . "\n" .
                    'Message: ' . $e->getMessage()
            );

            // 内部エラーとしてレスポンスを返却
            abort(500, "内部エラー、お手数ですが管理者にご連絡下さい");
        }

        // リソースの新規作成なので
        // レスポンスコードは201(CREATED)を返却する
        return response($photo, 201);
    }

    /**
     * ユーザことの投稿写真情報取得
     * @return \Illuminate\Http\Response
     */
    public function getByUser()
    {
        // ログインしているユーザ情報に紐づく写真情報を取得
        $photos = User::with(['photos'])
            ->find(Auth::id())
            ->photos()
            ->with(['shop'])
            ->get();

        return $photos;
    }
}
