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
	
	Route::get('/admin/home', 'GetsController@index')->name('home')->middleware('auth');
	
	Route::get('/admin/documentos/{opcao}', 'GetsController@dwdocumentos')->middleware('auth');
	
	Route::get('/admin/pontosvenda', 'GetsController@pontosdeVenda')->name('pontosvenda')->middleware('auth');
	
	Route::get('/admin/pdv/{id}', 'GetsController@infopdv')->middleware('auth');
	
	Route::get('/admin/produtos', 'GetsController@produtos')->name('produtos')->middleware('auth');
	
	Route::get('/admin/pedidos', 'GetsController@pedidos')->name('pedidos')->middleware('auth');
	
	Route::get('/admin/usuarios', 'GetsController@usuarios')->name('usuarios')->middleware('auth');
	
	Route::get('/admin/estoque', 'GetsController@estoque')->name('estoque');
	
	Route::get('/admin/pedido/{id}', 'GetsController@infopedido')->middleware('auth');
	
	Route::get('/admin/perfil', 'GetsController@perfil')->name('perfil')->middleware('auth');
	
	Route::get('/nf', 'GetsController@nf')->name('nf')->middleware('auth');
	//Fim
	
	//Inicio GET's REPOSITOR
	Route::get('/user/home', 'UserGetController@index')->name('user_home')->middleware('auth');
	
	Route::get('/user/documentos/{opcao}', 'UserGetController@dwdocumentos')->middleware('auth');
	
	Route::get('/user/pdv', 'UserGetController@pdv')->name('user_pdv')->middleware('auth');
	
	Route::get('/user/pedidos', 'UserGetController@pedidos')->name('user_pedidos')->middleware('auth');
	
	Route::get('/user/pedido/{id}', 'UserGetController@infopedido')->middleware('auth');
	
	Route::get('/user/pdv/{id}', 'UserGetController@infopdv')->middleware('auth');
	
	Route::get('/user/perfil', 'UserGetController@perfil')->name('user_perfil')->middleware('auth');
	//Fim
	
	//Inicio CRUD Produto
	Route::post('/cadastrar-produto', 'PostsController@cadastrarProduto')->name('cadastrar-produto')->middleware('auth');
	
	Route::post('/editar_produto', 'PostsController@editar_produto')->name('editar_produto')->middleware('auth');
	
	Route::post('/deletar_produto', 'PostsController@deletar_produto')->name('deletar_produto')->middleware('auth');
	//Fim
	
	//Inicio CRUD PDV
	Route::post('/cadastrar-pdv', 'PostsController@cadastrarPdv')->name('cadastrar-pdv')->middleware('auth');
	
	Route::post('/editar_pdv', 'PostsController@editar_pdv')->name('editar_pdv')->middleware('auth');
	
	Route::post('/deletar_pdv', 'PostsController@deletar_pdv')->name('deletar_pdv')->middleware('auth');
	
	Route::post('/cadastrar-pdv', 'PostsController@cadastrarPdv')->name('cadastrar-pdv')->middleware('auth');
	
	//Route::post('/estoque-pdv', 'PostsController@estoquePdv')->name('estoque-pdv');
	//Fim
	
	//Inicio POST's ADMIN
	Route::post('/estoque_repositor', 'PostsController@estoqueRepositor')->name('estoque_repositor')->middleware('auth');
	
	Route::post('/cadastrar_pedido', 'PostsController@cadastrarPedido')->name('cadastrar_pedido')->middleware('auth');
	
	Route::post('/cadastrar_usuario', 'PostsController@cadastrarUsuario')->name('cadastrar_usuario')->middleware('auth');
	
	Route::post('/admin_editar_perfil', 'PostsController@admin_editar_perfil')->middleware('auth');
	
	Route::post('/documentoPdv', 'PostsController@documentoPdv')->middleware('auth');
	//Fim
	
	//Inicio POST's USER
	Route::post('/user_cadastrar_pdv', 'UserPostController@cadastrarPdv')->name('user_cadastrar_pdv')->middleware('auth');
	
	Route::post('/user/pedido/{id}/emitNfe', 'UserPostController@emitNfe')->middleware('auth');
	
	Route::post('/user/pedido/{id}/enviNfe', 'UserPostController@enviNfe')->middleware('auth');
	
	Route::post('/user/pedido/{id}/emitBoleto', 'UserPostController@emitBoleto')->middleware('auth');
	
	Route::post('/user_editar_perfil', 'UserPostController@user_editar_perfil')->middleware('auth');
	//Fim
	
	//Inicio Funcoes
	Route::post('/trocar_imagem_perfil', 'PostsController@trocar_imagem_perfil')->middleware('auth');
	
	Route::post('/mudar_funcao', 'PostsController@mudarFuncao')->name('mudar_funcao')->middleware('auth');
	//Fim
	
	//Inicio GET's JSON
	Route::get('/uf/', function($uf = null){
		return response()->json(DB::table('estados')->orderBy('uf')->get());
	});
	
	Route::get('/cidades/{estado}', function($uf = null){
		return response()->json(DB::table('cidades')->where('id_estado', $uf)->orderBy('nome')->get());
	});
	
	Route::get('/tempo_agora', 'GetsController@tempo_agora')->name('tempo_agora')->middleware('auth');
	
	Route::get('/retornar', 'GetsController@retornar')->name('retornar')->middleware('auth');
	
	Route::get('/download/{pdv}', 'GetsController@download')->name('download')->middleware('auth');
	
	Route::post('/add_imagem_pdv', 'PostsController@add_imagem_pdv')->name('add_imagem_pdv')->middleware('auth');
	
	//Fim
	
	
