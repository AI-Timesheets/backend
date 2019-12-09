<?php

namespace App;

/**
 * Class UserVerification
 * @package App
 *
 * @property int $user_id;
 * @property string $verification_key;
 * @property App\User $user;
 */
class UserVerification extends BaseModel {
    protected $fillable = [
        'user_id',
        'verification_key',
    ];

    public function user() {
        return $this->belongsTo("App\User");
    }
}
