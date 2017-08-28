<?php
	
	/*
		|--------------------------------------------------------------------------
		| Web Routes
		|--------------------------------------------------------------------------
		|
		| Here is where you can register web routes for your application. These
		| routes are loaded by the RouteServiceProvider within a group which
		| contains the "web" middleware group. Now create something great!
		|
	*/
	
	
	//Rotas Login e Cadastro de Usuários do Sistema
	Auth::routes();
	
	//Inicio GET's ADMIN
	Route::get('/', function () {return view('welcome');});
	
	Route::get('/admin/home', 'GetsController@index')->name('home');
	
	Route::get('/admin/pontosvenda', 'GetsController@pontosdeVenda')->name('pontosvenda');
	
	Route::get('/admin/produtos', 'GetsController@produtos')->name('produtos');
	
	Route::get('/admin/pedidos', 'GetsController@pedidos')->name('pedidos');
	
	Route::get('/admin/usuarios', 'GetsController@usuarios')->name('usuarios');
	
	Route::get('/admin/estoque', 'GetsController@estoque')->name('estoque');
	
	Route::get('/admin/pedido/{id}', 'GetsController@infopedido');
	
	//Fim
	
	//Inicio GET's REPOSITOR
	Route::get('/user/home', 'UserGetController@index')->name('user_home');
	
	Route::get('/user/pdv', 'UserGetController@pontosdeVenda')->name('user_pdv');
	//Fim
	
	//Inicio CRUD Produto
	Route::post('/cadastrar-produto', 'PostsController@cadastrarProduto')->name('cadastrar-produto');
	
	Route::post('/editar_produto', 'PostsController@editar_produto')->name('editar_produto');
	
	Route::post('/deletar_produto', 'PostsController@deletar_produto')->name('deletar_produto');
	//Fim
	
	//Inicio CRUD PDV
	Route::post('/cadastrar-pdv', 'PostsController@cadastrarPdv')->name('cadastrar-pdv');
	
	Route::post('/editar_pdv', 'PostsController@editar_pdv')->name('editar_pdv');
	
	Route::post('/deletar_pdv', 'PostsController@deletar_pdv')->name('deletar_pdv');
	
	Route::post('/cadastrar-pdv', 'PostsController@cadastrarPdv')->name('cadastrar-pdv');
	
	//Route::post('/estoque-pdv', 'PostsController@estoquePdv')->name('estoque-pdv');
	//Fim
	
	//Inicio POST's
	Route::post('/estoque-pdv', 'PostsController@estoqueRepositor')->name('estoque-pdv');
	
	Route::post('/cadastrar_pedido', 'PostsController@cadastrarPedido')->name('cadastrar_pedido');
	//Fim
	
	//Inicio Funcoes
	Route::post('/mudar_funcao', 'PostsController@mudarFuncao')->name('mudar_funcao');
	//Fim
	
	//Inicio GET's JSON
	Route::get('/ufs/', function($uf = null){
		return response()->json(\Artesaos\Cidade::select('uf')->distinct('uf')->orderBy('uf')->get());
	});
	
	Route::get('/cidades/{uf}', function($uf = null){
		return response()->json(\Artesaos\Cidade::where('uf', $uf)->orderBy('nome')->get());
	});
	//Fim
	
	
