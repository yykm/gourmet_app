<?php

return [

    /*
    |--------------------------------------------------------------------------
    |ホットペッパーグルメサーチAPIで使用する各種設定値
    |--------------------------------------------------------------------------
    */

    // 基底URL
    'gourmet_base_url' => env('GOURMET_BASE_URL', null),
    // 相対URL
    'gourmet_relative_url' => env('GOURMET_RELATIVE_URL', null),
    // APIキー
    'gourmet_key' => env('GOURMET_KEY', null),
    // 取得形式
    'gourmet_format' => env('GOURMET_FORMAT', 'json'),
    // 取得件数
    'gourmet_count' =>env('GOURMET_COUNT', '10')
];
