<?php

use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = App\Order::all();

        factory(App\Store::class, 4)->create();

        $orders->each(function ($order) {
            $order->store()->associate(App\Store::all()->shuffle()->first())->save();
        });
    }
}
