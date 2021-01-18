<?php

use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = factory(App\Order::class, 25)->create();

        $users = App\User::all();
        $stores = App\Store::all();

        $orders->each(function ($order) use ($users, $stores){
            $order->user()->associate($users->random());
            $order->store()->associate($stores->random());
            $order->update();
        });
    }
}
