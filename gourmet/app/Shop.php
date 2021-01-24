<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /** 主キーの型 */
    protected $keyType = 'string';

    /** 返却されるJSON表現に含める属性 */
    protected $visible = [
        'id', //店舗ID
    ];

    /**
     * リレーションシップ - photosテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    /** ユーザ入力値を受け付ける属性 */
    protected $fillable = [
        'id',
    ];
}
