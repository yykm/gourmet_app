<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    /** 主キーの型 */
    protected $keyType = 'string';

    /** IDの桁数 */
    const ID_LENGTH = 12;

    /** 返却されるJSON表現に含める属性 */
    protected $visible = [
        'id', // 写真ID
        'user', // ユーザ情報
        'url', // 公開URL
        self::CREATED_AT // 写真投稿日
    ];

    /** 返却されるJSON表現にgetUrlAttributeアクセサを含める */
    protected $appends = [
        'url',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // id値が設定されていないモデル生成（新規生成）の場合
        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    /**
     * ランダムなID値をid属性に代入する
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     * @return string
     */
    private function getRandomId()
    {
        // 0-9,a-z,A-Z,-,_をマージした配列を作成
        $characters = array_merge(
            range(0, 9),
            range('a', 'z'),
            range('A', 'Z'),
            ['-', '_']
        );
        // 要素数を取得
        $length = count($characters);

        $id = "";

        // 12桁の0-9,a-z,A-Z,-,_からなるidを生成
        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * リレーションシップ - shopsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
        // S3上のオブジェクトURL（公開URL）を返却する
        return Storage::cloud()->url($this->attributes['filename']);
    }
}
