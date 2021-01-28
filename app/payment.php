<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{

    protected $table = 'payments';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'value',
        'currency',
        'method',
        'user_id'
    ];


    /**
     * Get the user for this model.
     */
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }

}
