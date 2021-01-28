<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userType extends Model
{

    protected $table = 'user_types';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'userType_id';

    protected $fillable = [
        'type','desc'
    ];

}
