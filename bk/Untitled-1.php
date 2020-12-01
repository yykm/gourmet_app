<?php
// 各種パラメータがあるか確認
$keyword = $_POST['keyword'] ? $_POST['keyword'] : '';
$lat = $_POST['lat'] ? $_POST['lat'] : '';
$lon = $_POST['lon'] ? $_POST['lon'] : '';
$range = $_POST['range'] ? $_POST['range'] : '';


$baseurl = 'https://webservice.recruit.co.jp/hotpepper/gourmet/v1/';
$params = [
    'key' => '【APIキー】',
    'format' => 'json',
    'keyword' => $keyword,
    'count' => 100,
    'lat' => $lat,
    'lng' => $lon,
    'range' => $range,
];
$url = $baseurl . '?' . http_build_query($params, '', '&');
 
// リクエストを送り結果を取得
$result = file_get_contents($url);
 
// 結果を出力
header("Content-Type: text/javascript; charset=utf-8");
echo $result;
 
exit();
