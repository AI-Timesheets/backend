<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmployeeFaces
 * @package App
 *
 * @property int $company_employee_id
 * @property string $face_id
 * @property App\User $user
 */
class EmployeeFaces extends Model
{
    protected $fillable = [
        'company_employee_id',
        'face_id'
    ];

    public function companyEmployee() {
        return $this->belongsTo('\App\CompanyEmployee');
    }
}
