<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
		// Role comes before User seeder here.
		$this->call(RoleTableSeeder::class);
		// User seeder will use the roles above created.
		$this->call(EstadosSeeder::class);
		$this->call(CidadesSeeder::class);
		$this->call(StatusTableSeeder::class);
		$this->call(UserTableSeeder::class);
		$this->call(ProdutosTableSeeder::class);
		$this->call(PontoVendaTableSeeder::class);
		
    }
}
