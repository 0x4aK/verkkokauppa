<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The default values
     */
    protected $attributes = [
        'status' => 0,
    ];

    /**
     * Makes a relationship between order and user
     * 
     * @return object
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Makes a relationship between order and products
     * 
     * @return object
     */
    public function ordered()
    {
        return $this->belongsToMany('App\Product', 'order_product')->withPivot('quantity');
    }

    /**
     * Makes a relationship between order and store
     * 
     * @return object
     */
    public function store()
    {
        return $this->belongsTo('App\Store');
    }
}