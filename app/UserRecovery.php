<?php

namespace App;

/**
 * Class UserRecovery
 * @package App
 *
 * @property int $user_id;
 * @property string $recovery_key;
 * @property string $expires_at;
 * @property \App\User $user;
 */
class UserRecovery extends BaseModel {
    protected $fillable = [
        'user_id',
        'expires_at',
        'recovery_key',
    ];

    public function user() {
        return $this->belongsTo("App\User");
    }
}
