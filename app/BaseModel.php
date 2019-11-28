<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BaseModel
 * @package App
 *
 * @property int $id;
 * @property string $created_at;
 * @property string $updated_at;
 * @property string $deleted_at;
 */
class BaseModel extends Model {
    use SoftDeletes;
}
