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
        $users = App\User::where('role', 0);

        $users->each(function ($user) {
            $order = factory(App\Order::class, rand(0,3))->create();
            $user->orders()->saveMany($order);
        });
    }
}
