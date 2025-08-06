<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $permissions = [
            'admin-dashboard',
            'admin-bidang-terkait',
            'admin-klasifikasi',
            'admin-laporan',
            'admin-tindakan',
            'manage users',
        ];
//        Role::all()->each(function ($role) {
//            $role->delete();
//        });
//        Permission::all()->each(function ($permission) {
//            $permission->delete();
//        });
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }
        $r1 = Role::firstOrCreate(["name" => "Petugas"]);
        $r2 = Role::firstOrCreate(["name" => "Bidang-Terkait"]);
        $r3 = Role::firstOrCreate(["name" => "Pelapor"]);

        $r1->givePermissionTo('manage users');
        $r1->givePermissionTo('admin-dashboard');
        $r1->givePermissionTo('admin-bidang-terkait');
        $r1->givePermissionTo('admin-klasifikasi');
        $r1->givePermissionTo('admin-laporan');
        $r2->givePermissionTo('admin-tindakan');

        $petugas = User::updateOrCreate([
            'email' =>'petugas@gmail.com'
        ],[
            'name' => 'Petugas',
            'email' =>'petugas@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => null,
        ]);
        $bidang_terkait = User::updateOrCreate([
            'email' => 'bidangterkait@gmail.com',
        ],[
            'name' => 'bidangterkait',
            'email' =>'bidangterkait@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => null,
        ]);

        $petugas->assignRole($r1);
        $bidang_terkait->assignRole($r2);


    }
}
