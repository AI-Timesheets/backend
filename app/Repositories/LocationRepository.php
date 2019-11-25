<?php

namespace App\Repositories;

use App\Location;

class LocationRepository extends BaseRepository {
    public function __construct() {
        parent::__construct(Location::class, ['companyEmployees', 'clockInLogs']);
    }
}
