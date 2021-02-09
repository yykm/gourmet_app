<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** JSONに含める属性 */
    protected $visible = [
        'id', 'user', 'content', self::CREATED_AT, 'photo', 'shop'
    ];

    protected $perPage = 10; // ペジネーション１ページあたりのデータ数

    // マスアサインメント用
    protected $guarded = [
        'id',
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * リレーションシップ - photosテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }

    /**
     * リレーションシップ - shops
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }
}
