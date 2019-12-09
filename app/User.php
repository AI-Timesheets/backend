<?php

namespace App;

/**
 * Class User
 * @package App
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property \App\Company[] $companies;
 * @property boolean $verified;
 */
class User extends BaseModel
{
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'verified',
    ];

    protected $hidden = [
        'password',
    ];

    public function companies() {
        return $this->hasMany("\App\Company", "owner_user_id");
    }

    public function isOwnerOf(Company $company) {
        return $this->id === $company->owner_user_id;
    }



}
