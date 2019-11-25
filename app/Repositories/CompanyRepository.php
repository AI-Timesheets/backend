<?php

namespace App\Repositories;

use App\Company;

class CompanyRepository extends BaseRepository {
    public function __construct() {
        parent::__construct(Company::class, ['owner', 'locations']);
    }
}
