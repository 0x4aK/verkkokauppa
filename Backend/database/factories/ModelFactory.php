<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'fname' => $faker->firstName,
        'lname' => $faker->lastName,
        'email' => $faker->unique()->email,
        'password' => password_hash('asdasdasd', PASSWORD_BCRYPT),
        'address' => $faker->address,
        'phone' => $faker->e164PhoneNumber,
        'store' => rand(1,4)
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Burger ' . ucfirst($faker->unique()->word),
        'price' => rand(40, 120) / 10,
        'is_featured' => $faker->boolean,
        'description' => $faker->text(),
        'category' => rand(1,3),
        'img' => '/images/menu.jpg',
    ];
});

$factory->define(App\Store::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Mesen Burgeri ' . $faker->unique()->city,
        'address' => $faker->streetAddress(),
        'phone' => $faker->e164PhoneNumber(),
        'open' => ['ma-to' => '8-21', 'pe-la' => '10-23','su' => '12-21'],
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
        'status' => rand(1,4)
    ];
});
