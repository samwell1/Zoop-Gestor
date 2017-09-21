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
	Route::get('/', function () {return view('auth.login');});
	
	Route::get('/admin/home', 'GetsController@index')->name('home');
	
	Route::get('/admin/pontosvenda', 'GetsController@pontosdeVenda')->name('pontosvenda');
	
	Route::get('/admin/produtos', 'GetsController@produtos')->name('produtos');
	
	Route::get('/admin/pedidos', 'GetsController@pedidos')->name('pedidos');
	
	Route::get('/admin/usuarios', 'GetsController@usuarios')->name('usuarios');
	
	Route::get('/admin/estoque', 'GetsController@estoque')->name('estoque');
	
	Route::get('/admin/pedido/{id}', 'GetsController@infopedido');
	
	Route::get('/nf', 'GetsController@nf')->name('nf');
	
	
	
	//Fim
	
	//Inicio GET's REPOSITOR
	Route::get('/user/home', 'UserGetController@index')->name('user_home');
	
	Route::get('/user/pdv', 'UserGetController@pdv')->name('user_pdv');
	
	Route::get('/user/pedidos', 'UserGetController@pedidos')->name('user_pedidos');
	
	Route::get('/user/pedido/{id}', 'UserGetController@infopedido');
	
	Route::get('/user/pdv/{id}', 'UserGetController@infopdv');
	
	
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
	
	//Inicio POST's ADMIN
	Route::post('/estoque_repositor', 'PostsController@estoqueRepositor')->name('estoque_repositor');
	
	Route::post('/cadastrar_pedido', 'PostsController@cadastrarPedido')->name('cadastrar_pedido');
	
	Route::post('/cadastrar_usuario', 'PostsController@cadastrarUsuario')->name('cadastrar_usuario');
	//Fim
	
	//Inicio POST's USER
	Route::post('/user_cadastrar_pdv', 'UserPostController@cadastrarPdv')->name('user_cadastrar_pdv');
	
	Route::post('/user/pedido/{id}/emitNfe', 'UserPostController@emitNfe');
	
	Route::post('/user/pedido/{id}/enviNfe', 'UserPostController@enviNfe');
	
	
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
	
	Route::get('/tempo_agora', 'GetsController@tempo_agora')->name('tempo_agora');
	//Fim
	
	
