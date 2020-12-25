<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new App\User;
        $admin->fname = 'admin';
        $admin->lname = '';
        $admin->email = 'a@a.com';
        $admin->password = password_hash('asdasdasd', PASSWORD_BCRYPT);
        $admin->address = '';
        $admin->phone = '';
        $admin->role = 2;
        $admin->store = 1;

        $admin->save();

        // create 10 users using the user factory
        factory(App\User::class, 10)->create();
    }
}
