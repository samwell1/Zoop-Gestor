<?php

use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//DB::table('states')->delete();
		$estados[] = array(
        array('id' => 1, 'nome' => 'Acre', 'uf' => 'AC', 'codigo' => '12'),
        array('id' => 2, 'nome' => 'Alagoas', 'uf' => 'AL', 'codigo' => '27'),
        array('id' => 3, 'nome' => 'Amapá', 'uf' => 'AP', 'codigo' => '16'),
        array('id' => 4, 'nome' => 'Amazonas', 'uf' => 'AM', 'codigo' => '13'),
        array('id' => 5, 'nome' => 'Bahia', 'uf' => 'BA', 'codigo' => '29'),
        array('id' => 6, 'nome' => 'Ceará', 'uf' => 'CE', 'codigo' => '23'),
        array('id' => 7, 'nome' => 'Distrito Federal', 'uf' => 'DF', 'codigo' => '53'),
        array('id' => 8, 'nome' => 'Espírito Santo', 'uf' => 'ES', 'codigo' => '32'),
        array('id' => 9, 'nome' => 'Goiás', 'uf' => 'GO', 'codigo' => '52'),
        array('id' => 10, 'nome' => 'Maranhão', 'uf' => 'MA', 'codigo' => '21'),
        array('id' => 11, 'nome' => 'Mato Grosso', 'uf' => 'MT', 'codigo' => '51'),
        array('id' => 12, 'nome' => 'Mato Grosso do Sul', 'uf' => 'MS', 'codigo' => '50'),
        array('id' => 13, 'nome' => 'Minas Gerais', 'uf' => 'MG', 'codigo' => '31'),
        array('id' => 14, 'nome' => 'Pará', 'uf' => 'PA', 'codigo' => '15'),
        array('id' => 15, 'nome' => 'Paraíba', 'uf' => 'PB', 'codigo' => '25'),
        array('id' => 16, 'nome' => 'Paraná', 'uf' => 'PR', 'codigo' => '41'),
        array('id' => 17, 'nome' => 'Pernambuco', 'uf' => 'PE', 'codigo' => '26'),
        array('id' => 18, 'nome' => 'Piauí', 'uf' => 'PI', 'codigo' => '22'),
        array('id' => 19, 'nome' => 'Rio de Janeiro', 'uf' => 'RJ', 'codigo' => '33'),
        array('id' => 20, 'nome' => 'Rio Grande do Norte', 'uf' => 'RN', 'codigo' => '24'),
        array('id' => 21, 'nome' => 'Rio Grande do Sul', 'uf' => 'RS', 'codigo' => '43'),
        array('id' => 22, 'nome' => 'Rondônia', 'uf' => 'RO', 'codigo' => '11'),
        array('id' => 23, 'nome' => 'Roraima', 'uf' => 'RR', 'codigo' => '14'),
        array('id' => 24, 'nome' => 'Santa Catarina', 'uf' => 'SC', 'codigo' => '42'),
        array('id' => 25, 'nome' => 'São Paulo', 'uf' => 'SP', 'codigo' => '35'),
        array('id' => 26, 'nome' => 'Sergipe', 'uf' => 'SE', 'codigo' => '28 '),
        array('id' => 27, 'nome' => 'Tocantins', 'uf' => 'TO', 'codigo' => '17')
		);
		
		foreach($estados as $estado){
		$this->command->info('Inserindo estados...');
		DB::table('estados')->insert($estado);
		}
		
    }
    
}
