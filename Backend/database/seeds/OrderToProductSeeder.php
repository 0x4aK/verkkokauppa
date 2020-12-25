<?php

use Illuminate\Database\Seeder;

class OrderToProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = App\Product::all();
        $orders = App\Order::all();

        $orders->each(function ($order) use ($products) {
            $order->ordered()->attach(
                $products->random(rand(1,3))->pluck('id')->toArray()
            );
        });
    }
}
