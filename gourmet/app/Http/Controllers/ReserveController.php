<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReserve;
use Log;

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
        Log::debug(['aa', $request->form['date']]);

        return response('', 201);
    }
}
