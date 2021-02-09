<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    /** 主キーの型 */
    protected $keyType = 'string';
    
    /** 関連づけるテーブル */
    protected $table = 'shops';


    /** JSONに含めるアクセサ */
    protected $appends = [
        'likes_count', 'liked_by_user'
    ];

    /** JSONに含める属性 */
    protected $visible = [
        'id', 'likes_count', 'liked_by_user', 'name', 'kana', 'image', 'address', 'access', 'catch', 'open', 'category', 'average'
    ];

    /** ユーザ入力値を受け付ける属性 */
    protected $fillable = [
        'id', 'name', 'kana', 'image', 'address', 'access', 'catch', 'open', 'category', 'average'
    ];

    /**
     * モデルの主キーを自動増分させるか否か
     * save()時にモデルインスタンスのidが0として更新されてしまうため
     */
    public $incrementing = false;

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany('App\User', 'favorites')->withTimestamps();
    }

    /**
     * リレーションシップ - commentsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }

    /**
     * 予約に所属する役目を取得
     */
    public function subscriber()
    {
        return $this->belongsToMany('App\User', 'reservations')
            ->as('reservation')
            ->withPivot('id', 'shop_id', 'user_id', 'name', 'kana', 'date', 'time', 'number', 'email', 'phone_num', 'purpose', 'request')
            ->withTimestamps();
    }

    /**
     * アクセサ - likes_count
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return $this->favorites->count();
    }

    /**
     * アクセサ - liked_by_user
     * @return boolean
     */
    public function getLikedByUserAttribute()
    {
        // ログインしていなければfalseを返す
        if (Auth::guest()) {
            return false;
        }

        // ログイン中のユーザがこの(this)店舗に関して既にいいねをしているか
        return $this->favorites->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }
}
