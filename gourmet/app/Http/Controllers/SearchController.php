<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp\Client;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{

  /**
   * 店舗検索
   * @param Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function search(Request $request)
  {

    // 各種パラメータが存在するか確認
    $keyword = $request->input('keyword') ??   '';
    $lat = $request->input('lat') ??  '';
    $lon = $request->input('lon') ?? '';
    $range = $request->input('range') ?? '';

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

    try {
      $result = $client->get(
        $relative_url,
        [
          'query' => $query,
        ]
      )->getBody()->getContents();
    } catch (RequestException $e) {
      // ネットワークエラー

      if ($e->hasResponse()) {
        // [ステータスコード::ステータスコードの説明]形式でエラーログに残す
        $error = $e->getResponse();
        Log::error($error->getStatusCode() . '::' . $error->getReasonPhrase());
      }
      // エラーレスポンスを返却
      return response('検索サービスが只今お使いになれません', $error->getStatusCode());
    } catch (Exception $e) {
      // 内部、その他エラー

      // エラー箇所のファイル・行・メッセージをエラーログに残す
      Log::error(
        'File: ' . $e->getFile() . "\n" .
          'Line: ' . $e->getLine() . "\n" .
          'Message: ' . $e->getMessage()
      );

      // 内部エラーとしてレスポンスを返却
      return response('内部エラー', 500);
    }

    // 連想配列形式へ変換
    $list = json_decode($result, true);

    // グルメサーチAPIはエラー時にレスポンスのステータスコード200で、
    // XMLの<error>タグ内にエラーコードとその理由を返す仕様の為ここで捕捉
    if (isset($list['results']['error'])) {

      // エラー情報
      $errors = $list['results']['error'];

      // エラー箇所のコード・メッセージをエラーログに残す
      foreach ($errors as $error) {
        Log::error(
          'code: ' . $error['code'] . "\n" .
            'message: ' . $error['message'] . "\n"
        );
      }

      // 404エラーとしてレスポンスを返却
      return response('検索サービスが只今お使いになれません', 404);
    };

    return $list;










    // $promise = $client->requestAsync('GET', $relative_url, ['query' => $query, 'http_errors' => false]);

    // $promise->then(
    //   // 成功時
    //   function ($response) {
    //     if ($response->getStatusCode() !== 200) {
    //       \Log::error($response->getStatusCode() . '::' . $response->getReasonPhrase());
    //       return response('検索サービスが只今お使いになれません', 404);
    //     }
    //     return $response;
    //   },

    //   // 失敗時
    //   function ($reason) {
    //     \Log::error($reason->getCode() . '::' . $reason->getMessage());
    //     return response('検索サービスが只今お使いになれません', 404);
    //   }
    // );
    // $response = $promise->wait();

    // // 連想配列形式へ変換
    // $list = json_decode($response->getBody()->getContents(), true);

    // return $list;
  }
}
