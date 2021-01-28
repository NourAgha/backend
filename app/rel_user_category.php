<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rel_user_category extends Model
{
    protected $table = 'rel_user_categories';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'rel_user_category_id';

    protected $fillable = [
        'category_id',
        'user_id',
        'status',
        'cost'
    ];
}
