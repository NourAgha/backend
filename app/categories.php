<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    protected $table = 'categories';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'costperliter',
        'currency',
    ];

}
