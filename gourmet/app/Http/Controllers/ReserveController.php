<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReserve;
use Illuminate\Support\Facades\Auth;
use App\Shop;
use App\User;
use App\Reservation;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\ReservationPosted;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class ReserveController extends Controller
{
    public function __construct()
    {
        // 認証されていない場合422エラー
        $this->middleware('auth');
    }


    /**
     * 予約
     * @param StoreReserve $request
     * @return \Illuminate\Http\Response
     */
    public function reserve(StoreReserve $request)
    {
        /** １．予約情報を作成 */
        $data = array();
        foreach ($request->form as $key => $value) {
            $data = array_merge($data, array($key => $value));
        }
        
        // json文字列からオブジェクトへ変換
        $shop = json_decode($request->shop);

        // 利用目的
        if (!is_null($data['purpose'])) {
            $text = '';

            switch (intval($data['purpose'])) {
                case 1:
                    $text = "誕生日";
                    break;
                case 2:
                    $text = "デート";
                    break;
                case 3:
                    $text = "接待";
                    break;
                case 4:
                    $text = "歓迎会";
                    break;
                case 5:
                    $text = "送別会";
                    break;
                case 6:
                    $text = "同窓会";
                    break;
                case 7:
                    $text = "忘年会";
                    break;
                case 8:
                    $text = "結婚式２次会";
                    break;
                case 9:
                    $text = "法事";
                    break;
                case 10:
                    $text = "食事";
                    break;
                case 11:
                    $text = "飲み会";
                    break;
                case 12:
                    $text = "合コン";
                    break;
                default:
                    $text = "その他";
                    break;
            }
            // 利用目的を代入
            $data['purpose'] = $text;
        } else {
            $data['purpose'] = '無し';
        }

        // ご要望・ご意見未入力の場合
        if (is_null($data['request'])) {
            $data['request'] = '無し';
        }

        /** ２．予約情報の保存 */
        DB::beginTransaction();
        try {
            // 店舗情報がない場合は店舗情報を格納、取得
            $shop = Shop::with(['subscriber'])
                ->firstOrCreate(
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

            // ある予約対象の店舗に紐づくあるユーザに関する予約情報を格納
            // １ユーザは１店舗のみ予約可能
            $shop->subscriber()->detach(Auth::user()->id);
            $shop->subscriber()->attach(Auth::user()->id, $data);

            // 予約情報のリロード
            $reservation = Reservation::where('shop_id', $shop->id)
                ->where('user_id', Auth::user()->id)
                ->first();
            // テーブル名がpivotとして参照されるためボツ
            // $reservation = Shop::with(['subscriber'])
            //     ->find($request->shop_id)
            //     ->subscriber
            //     ->find(Auth::user()->id)
            //     ->reservation;

            // ３．メール送信
            // Mail::to(Auth::user())->queue(new ReservationPosted($reservation));

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // ４．作成した予約情報を返却
        return response($reservation, 201);
    }

    /**
     * ユーザことの予約情報取得
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getByUser(Request $request)
    {
        if (is_null($request->user_id)) {
            abort(404);
        }

        $reservations = User::with(['reservations'])
            ->find($request->user_id)
            ->reservations()
            ->with(['shop'])
            ->get();

        return $reservations;
    }
}
