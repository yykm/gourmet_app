<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Shop;
use App\User;
use App\Photo;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        // 認証されていない場合422エラー
        // コメント取得に関しては認証不要とする
        $this->middleware('auth')->except(['index']);
    }

    /**
     * コメント一覧
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

        // カラムに設定する値
        $data = [
            'user_id' => Auth::user()->id,
            'shop_id' => $shop->id,
            'photo_id' => $request->photo_id,
            'content' => $request->content,
        ];



        // トランザクションを利用する
        // 写真投稿及びコメント保存失敗時にロールバック
        DB::beginTransaction();

        try {
            // 店舗データが作られていなければ店舗モデルを新たに作成、保存
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
            // コメント付属の写真がある場合は対応する写真IDも併せて保存
            if (Photo::find($request->photo_id) !== null) {
                $comment = Photo::with(['comment'])
                    ->find($request->photo_id)
                    ->comment()
                    ->create($data);

                // 対応する写真データのコメントidも更新
                Photo::find($request->photo_id)->update(['comment_id' => $comment->id]);
            } else {
                $comment = Comment::create($data);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // user, photoリレーションをロードするためにコメントを取得しなおす
        if (!$comment->photo_id == null) {
            $new_comment = Comment::where('id', $comment->id)->with(['user', 'photo'])->first();
        } else {
            $new_comment = Comment::where('id', $comment->id)->with(['user'])->first();
        }

        return response($new_comment, 201);
    }

    /**
     * ユーザことのレビュー情報取得
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getByUser(Request $request)
    {
        if (is_null($request->user_id)) {
            abort(404);
        }

        $comments = User::with(['comments'])
            ->find($request->user_id)
            ->comments()
            ->with(['photo','shop'])
            ->get();

        return $comments;
    }
}
