<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    public $timestamps = false; // disable timestamps

    protected $attributes = [
        'img' => 'images/default.jpg'
    ];

    /**
     * make 'open' into JSON form
     *
     * @var array
     */
    protected $casts = [
        'open' => 'array',
    ];

    /**
     * Makes a relationship between store and order
     *
     * @return object
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}