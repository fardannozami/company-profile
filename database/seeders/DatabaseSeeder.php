<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanyProfileSeeder::class,
            ArticleSeeder::class,
            ProductSeeder::class,
            EventSeeder::class,
            ClientSeeder::class,
            GallerySeeder::class,
        ]);

        /*
         | Demo accounts are created for local development only. They are
         | intentionally skipped in production so a deployed app never ships
         | with known credentials; create real accounts via /admin/users.
         */
        if (app()->environment('local')) {
            $admin = User::create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
            ]);
            $admin->forceFill(['role' => 'admin'])->save();

            $user = User::create([
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user123'),
            ]);
            $user->forceFill(['role' => 'user'])->save();
        }
    }
}
