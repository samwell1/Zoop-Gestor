<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('name', 'admin')->first();
		$repositor = Role::where('name', 'repositor')->first();
		
		$employee = new User();
		$employee->name = 'Wellerson Samuel';
		$employee->email = 'wellerso@hotmail.com';
		$employee->documento = '08547889554';
		$employee->password = bcrypt('admin');
		$employee->save();
		$employee->roles()->attach($admin);
		
		
		$employee2 = new User();
		$employee2->name = 'João Repositor';
		$employee2->email = 'joao@example.com';
		$employee2->documento = '54878965424';
		$employee2->password = bcrypt('joao');
		$employee2->save();
		$employee2->roles()->attach($repositor);
		
		
    }
}
