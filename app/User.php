<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $email
 * @property string $password
 */
class User extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
    ];

    protected $hidden = [
        'password',
    ];

}
