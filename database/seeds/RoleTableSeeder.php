<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	//ADMIN
        $role_employee = new Role();
		$role_employee->name = 'admin';
		$role_employee->description = 'Administrador';
		$role_employee->save();
		//REPOSITOR
		$role_manager = new Role();
		$role_manager->name = 'repositor';
		$role_manager->description = 'Repositor';
		$role_manager->save();
    }
}
