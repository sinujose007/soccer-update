<?php

namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateTestUserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
	*/
    public function run()
    {
        $username = time();
		$user = User::create([
            'name' => 'Test User',
            'email' => $username.'testuser007@gmail.com',
            'password' => bcrypt('12345678')
        ]);
    
        $role = Role::where('name', 'Admin')->first();
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
		
		return $user;
    }

}