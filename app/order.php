<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{

    protected $table = 'orders';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $fillable = [
        'amount',
        'paid',
        'deliverd',
        'deliverd_by',
        'status',
        'category_id',
        'user_id',
        'payment_id',
        'sent_to'
    ];


    /**
     * Get the user for this model.
     */
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

}
