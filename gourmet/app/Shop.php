<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /** 主キーの型 */
    protected $keyType = 'string';

    /** ユーザ入力値を受け付ける属性 */
    protected $fillable = [
        'id',
    ];
}
