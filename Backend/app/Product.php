<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false; // disable timestamps, not needed in this case

    protected $attributes = [
        'img' => 'images/default.jpg'
    ];

    protected $casts = [
        'is_featured' => 'boolean'
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function toArray()
    {
        $attributes = $this->attributesToArray();
        $attributes = array_merge($attributes, $this->relationsToArray());

        // Detect if there is a pivot value and return that as the default value
        if (isset($attributes['pivot']['quantity'])) {
            $attributes['quantity'] = $attributes['pivot']['quantity'];
            unset($attributes['pivot']);
        }
        return $attributes;
    }

    /**
     * Makes a relationship between product and order
     * 
     * @return object
     */
    public function orders()
    {
        return $this->belongsToMany('App\Order', 'order_product');
    }
}