<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\Setting;
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
        $role = new Role();
        $role->name = 'Super Admin';
        $role->guard_name = 'web';
        $role->save();

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@easytrade.com';
        $user->is_admin = 1;
        $user->role_id = $role->id;
        $user->password = Hash::make('admin@easytrade');
        $user->save();
        $user->assignRole('Super Admin');

        $role = new Role();
        $role->name = 'Customer';
        $role->guard_name = 'web';
        $role->save();

        $setting = new Setting();
        $setting->withdraw_limit = 100;
        $setting->referral_amount = 10;
        $setting->save();

        $role = new Role();
        $role->name = 'Customer';
        $role->guard_name = 'web';
        $role->save();
    }
}
