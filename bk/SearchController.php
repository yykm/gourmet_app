<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp\Client;

class SearchController extends Controller
{
    public function index()
    {
      // 基底URL
      $base_url = config('api.gourmet_base_url');
      // 相対URL
      $relative_url = config('api.gourmet_relative_url');

      // 検索キーワード
      $keyword = '焼肉';

      // クエリ情報
      $query = [
        'key' => config('api.gourmet_key'),
        'format' => config('api.gourmet_format'),
        'count' => config('api.gourmet_count'),
        'keyword' => $keyword,
      ];

      // HTTPクライアントのインスタンス作成
      $client = new Client( [
        'base_uri' => $base_url,
      ] );

      $promise = $client->requestAsync('GET', $relative_url, ['query' => $query, 'http_errors' => false]);
      $promise->then(
          // 成功時の処理
          function($response) {
              // ステータスコードが200か？
              if ($response->getStatusCode() !== 200) {
                  \Log::error($response->getStatusCode().'::'.$response->getReasonPhrase());
              }
              return $response;
          },
          // 失敗時の処理
          function($reason) {
              // ログ出力
              \Log::error($reason->getCode().'::'.$reason->getMessage());
          }
      );
      // レスポンスが帰ってくるまで処理を待機
      $response = $promise->wait();
      
      
      
         // 連想配列形式へ変換
      $result = json_decode($response->getBody()->getContents(), true);

      
         $result['results']['shop'];
    }
}
