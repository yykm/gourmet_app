<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Shop;
use App\Photo;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Log;

class CommentController extends Controller
{

    /**
     * コメント一覧
     */
    public function index(Request $request)
    {
        // ある店舗のコメント一覧を投稿日の降順に取得
        $comments = Comment::with(['photo','user'])
        ->join('photos', 'comments.photo_id', '=', 'photos.id')
        ->where('comments.shop_id', $request->shop_id)
        ->whereNotNull('photos.comment_id')
        ->orderBy('comments.id', 'desc')
        ->get();

        return $comments;
    }

    /**
     * コメント投稿
     * @param StoreComment $request
     * @return \Illuminate\Http\Response
     */
    public function create(StoreComment $request)
    {
        // カラムに設定する値
        $data = [
            'user_id'=> Auth::user()->id,
            'shop_id' => $request->shop_id,
            'photo_id' => $request->photo_id,
            'content' => $request->content,
        ];

        // トランザクションを利用する
        // 写真投稿及びコメント保存失敗時にロールバック
        DB::beginTransaction();

        try {
            // 店舗データが作られていなければ店舗モデルを新たに作成、保存
            Shop::firstOrCreate(['id' => $request->shop_id]);

            // コメント保存
            // コメント付属の写真がある場合は対応する写真IDも併せて保存
            if (Photo::find($request->photo_id) !== null) {
                $comment = Photo::with(['comment'])->find($request->photo_id)->comment()->create($data);
            } else {
                $comment = Comment::create($data);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // コメント投稿が失敗したら対応する画像も写真
            if (Photo::find($request->photo_id) !== null) {
                Photo::destroy($request->photo_id);
            }
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
}
