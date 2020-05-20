<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-05-19
 * Time: 16:21
 */

namespace JoseChan\UserLogin\Models;


use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        "nickname",
        "sex",
        "language",
        "city",
        "country",
        "province",
        "headimgurl",
        "channel_id",
        "phone",
    ];
}
