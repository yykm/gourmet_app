<?php

namespace App\Http\Controllers;

use App\Comment;
use Exception;
use App\Shop;
use App\User;
use App\Photo;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct()
    {
        // 認証されていない場合422エラー
        // コメント一覧取得に関しては認証不要とする
        $this->middleware('auth')->except(['index']);
    }

    /**
     * コメント一覧取得
     * @param use Illuminate\Http\Request;
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // ある店舗のコメント一覧を投稿日の降順に取得
        $comments = Comment::with(['photo', 'user'])
            ->where('shop_id', $request->shop_id)
            ->orderBy('id', 'desc')
            ->paginate();

        return $comments;
    }

    /**
     * コメント投稿
     * @param StoreComment $request
     * @return \Illuminate\Http\Response
     */
    public function create(StoreComment $request)
    {
        // json文字列からオブジェクトへ変換
        $shop = json_decode($request->shop);

        // コメントテーブルに保存する値
        $data = [
            'user_id' => Auth::user()->id,
            'shop_id' => $shop->id,
            'photo_id' => $request->photo_id,
            'content' => $request->content,
        ];

        // トランザクションを利用する
        // コメント保存失敗時にロールバック
        DB::beginTransaction();

        try {
            // 関連する店舗データが作られていなければ新たに保存
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

            // コメント保存
            // コメントと一緒に投稿された写真がある場合は、対応する写真IDも併せて保存
            if (Photo::find($request->photo_id) !== null) {
                $comment = Photo::with(['comment'])
                    ->find($request->photo_id)
                    ->comment()
                    ->create($data);

                // 対応する写真データのcomment_idも更新
                Photo::find($request->photo_id)->update(['comment_id' => $comment->id]);
            } else {
                $comment = Comment::create($data);
            }
                        
            // コミット
            DB::commit();
        } catch (Exception $e) {
            // ロールバック
            DB::rollBack();

            // エラー箇所のファイル・行・メッセージをエラーログに残す
            Log::error(
                'File: ' . $e->getFile() . "\n" .
                    'Line: ' . $e->getLine() . "\n" .
                    'Message: ' . $e->getMessage()
            );

            // 内部エラーとしてレスポンスを返却
            abort(500, "内部エラー、お手数ですが管理者にご連絡下さい");
        }

        // user, photoリレーションをロードするためにコメントを取得しなおす
        if ($comment->photo_id !== null) {
            $new_comment = Comment::where('id', $comment->id)->with(['user', 'photo'])->first();
        } else {
            $new_comment = Comment::where('id', $comment->id)->with(['user'])->first();
        }

        return response($new_comment, 201);
    }

    /**
     * ユーザことの口コミ情報取得
     * @return \Illuminate\Http\Response
     */
    public function getByUser()
    {
        // ログインしているユーザ情報に紐づく口コミ情報を取得
        $comments = User::with(['comments'])
            ->find(Auth::id())
            ->comments()
            ->with(['photo', 'shop'])
            ->get();

        return $comments;
    }
}
