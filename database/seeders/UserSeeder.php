<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'User']);
     
        $role->givePermissionTo(['role-list','users-list','category-list','posts-list']);
        
        $pelanggan = User::create([
            'name' => 'Pelanggan', 
            'email' => 'pelanggan@gmail.com',
            'phone' => '083147173945',
            'password' => bcrypt('12345678'),
            'alamat' => 'bandung',
        ]);
     
        $pelanggan->assignRole([$role->id]);

        $agent = User::create([
            'name' => 'Agent', 
            'email' => 'agent@gmail.com',
            'phone' => '083147173235',
            'password' => bcrypt('12345678'),
            'alamat' => 'bandung',
        ]);

        $agent->assignRole([$role->id]);
        
        $kurir = User::create([
            'name' => 'kurir', 
            'email' => 'kurir@gmail.com',
            'phone' => '084147173945',
            'password' => bcrypt('12345678'),
            'alamat' => 'bandung',
        ]);

        $kurir->assignRole([$role->id]);

        $driver = User::create([
            'name' => 'Driver', 
            'email' => 'driver@gmail.com',
            'phone' => '084137173945',
            'password' => bcrypt('12345678'),
            'alamat' => 'bandung',
        ]);

        $driver->assignRole([$role->id]);
    }
}
