<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-05-19
 * Time: 16:21
 */

namespace JoseChan\UserLogin\Models;


use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $fillable = ["user_id", "username", "password"];
}
