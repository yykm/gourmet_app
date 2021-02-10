<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        // 認証されていない場合422エラー
        $this->middleware('auth');
    }

    /**
     * 退会処理
     * @return @return \Illuminate\Http\JsonResponse
     */
    public function withdrawal()
    {
        $user = Auth::user();

        /** 1.ログアウト */
        Auth::logout(); // ログアウト、update処理が行われる。

        /** 2.各リレーションを削除 */
        DB::beginTransaction();
        try {
            // 予約情報を削除
            $user->reservations()->delete();
            // お気に入り情報を削除
            $user->favorites()->delete();

            // 外部キー制約のため、
            // 写真に紐づくコメントデータを削除してから写真を削除
            // ユーザに紐づく写真モデルを取得
            $photos = $user->photos()->get();
            // 写真モデルの主キーを外部キーとして持つコメントを削除
            if ($photos) {
                $photos->load('comment')->each(function ($photo) {
                    $photo->comment()->delete();
                });
                // 写真を削除
                $user->photos()->delete();
            };
            // ユーザに紐づくコメントモデルを削除
            $user->comments()->delete();

            /** 3.ユーザ削除 */
            $user->delete(); // ユーザが削除される。
            
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        /** 4.ステータス200を返却 */
        return response()->json();
    }
}
