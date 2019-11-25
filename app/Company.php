<?php

namespace App;

/**
 * Class Company
 * @package App
 *
 * @property string $name;
 * @property string $company_code;
 * @property int $owner_user_id;
 * @property \App\User $owner;
 * @property \App\Location[] $locations;
 */
class Company extends BaseModel {
    protected $fillable = [
        'name',
        'company_code',
        'owner_user_id',
    ];

    public function owner() {
        return $this->belongsTo("\App\User", "owner_user_id");
    }

    public function locations() {
        return $this->hasMany("\App\Location");
    }
}
