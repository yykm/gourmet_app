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

    /** ユーザ入力値を受け付ける属性 */
    protected $fillable = [
        'id',
    ];

    /**
     * リレーションシップ - photosテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    /**
     * リレーションシップ - commentsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }
}
