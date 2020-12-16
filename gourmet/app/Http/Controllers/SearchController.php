<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp\Client;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // 各種パラメータが存在するか確認
        $keyword = $request->input('keyword') ?  $request->input('keyword') : '';
        $lat = $request->input('lat') ?  $request->input('lat') : '';
        $lon = $request->input('lon') ?  $request->input('lon') : '';
        $range = $request->input('range') ?  $request->input('range') : '';

        // 基底URL
        $base_url = config('api.gourmet_base_url');
        // 相対URL
        $relative_url = config('api.gourmet_relative_url');

        // クエリ情報
        $query = [
        'key' => config('api.gourmet_key'),
        'format' => config('api.gourmet_format'),
        'keyword' => $keyword,
        'count' => config('api.gourmet_count'),
        'lat' => $lat,
        'lng' => $lon,
        'range' => $range,
      ];
        // HTTPクライアントのインスタンス作成
        $client = new Client([
        'base_uri' => $base_url,
      ]);

        $promise = $client->requestAsync('GET', $relative_url, ['query' => $query, 'http_errors' => false]);
        $promise->then(
          // 成功時
          function ($response) {
              if ($response->getStatusCode() !== 200) {
                  \Log::error($response->getStatusCode().'::'.$response->getReasonPhrase());
              }
              return $response;
          },
           // 失敗時
             function ($reason) {
                 \Log::error($reason->getCode().'::'.$reason->getMessage());
             }
        );
        $response = $promise->wait();

        // 連想配列形式へ変換
        $list = json_decode($response->getBody()->getContents(), true);

        return $list;
    }
}
