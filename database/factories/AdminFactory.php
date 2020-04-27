<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'id' => '1234567890123456',
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'nomor_ktp' => '1234567890123456',
        'nama' => 'Master Admin',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1998-01-01',
        'jenis_kelamin' => 'laki',
        'alamat' => $faker->address(),
        'nomor_telpon' => '999999999999',
        'email' => 'master@admin.test',
        'foto' => 'default.png',
    ];
});
