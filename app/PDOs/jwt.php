<?php

namespace App\PDOs;

/**
 * Class jwt
 * @package App\PDOs
 *
 * @property string $issuer;
 * @property string $subject;
 * @property string $audience;
 * @property int $expiration;
 * @property int $notBefore;
 * @property int $issuedAt;
 * @property int $activeUntil;
 * @property App\User $user;
 */
class jwt {
    public $issuer;
    public $subject;
    public $audience;
    public $expiration;
    public $notBefore;
    public $issuedAt;
    public $activeUntil;
    public $user;
}
