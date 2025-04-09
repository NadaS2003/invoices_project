<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class createAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user =User::query()->create([
            'name'=> 'Nada Salama',
            'email'=>'nadaramadan1512003@gmail.com',
            'password'=> bcrypt('123456'),
            'roles_name'=>['owner'],
            'status'=>'مفعل'
        ]);

        $role = Role::create(['name'=>'owner']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

    }
}
