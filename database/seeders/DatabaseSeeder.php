<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'firstname' => 'Arsim',
            'lastname' => 'Ajdari',
            'email' => 'admin@online.shop',
            'password' => Hash::make('12345678'),
          ]);

          $admin->email_verified_at = now();
          $admin->save();

          Role::insert([
            ['name' => 'admin'],
          ]);

          $role = Role::where('name', 'admin')->first();

          $admin->roles()->sync([$role->id]);
    }
}
