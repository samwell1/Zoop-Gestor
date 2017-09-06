<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use App\Role;
	use App\Produto;
	use App\PontoVenda;
	use App\Pedido;
	use App\User;
	use Auth;
	
	class PostsController extends Controller
	{
		public function cadastrarProduto(Request $request)
		{	
			$request->user()->authorizeRoles(['admin']);
			
			$produto = new Produto();
			$produto->nome = $request['nome'];
			$produto->modelo = $request['modelo'];
			$produto->codigo = $request['codigo'];
			$produto->quantidade = $request['quantidade'];
			$produto->preco = formata_valor($request['preco']);
			$produto->status = 1;
			//Movendo a imagem para a pasta
			$imageName = $request['codigo'].'.'.$request->imagem->getClientOriginalExtension();
			$caminho = public_path('uploads/produtos/'.$request['nome'].'-'.$request['modelo']);
			$request->imagem->move(($caminho), $imageName);
			$produto->imagem = 'uploads/produtos/'.$request['nome'].'-'.$request['modelo'].'/'.$imageName;
			
			$produto->save();
			return redirect('admin/produtos')->with('status', 'Produto cadastrado com sucesso!');
		}
		
		public function editar_produto(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			$produto = Produto::find($request['idprod']);
			$produto->nome = $request['nome'];
			$produto->modelo = $request['modelo'];
			$produto->quantidade = $request['quantidade'];
			//Movendo a imagem para a pasta
			$imageName = $request['codigo'].'.'.$request->imagem->getClientOriginalExtension();
			$caminho = public_path('uploads/produtos/'.$request['nome'].'-'.$request['modelo']);
			$request->imagem->move(($caminho), $imageName);
			$produto->imagem = 'uploads/produtos/'.$request['nome'].'-'.$request['modelo'].'/'.$imageName;
			$produto->imagem = $request['imagem'];
			//fim
			$produto->save();
			return redirect('admin/produtos')->with('status', 'Produto editado com sucesso!');
		}
		
		public function deletar_produto(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			$produto = Produto::find($request['idprod']);
			$produto->status = 0;
			$produto->save();
			return redirect('admin/produtos')->with('status', 'Produto deletado com sucesso!');
		}
		
		public function cadastrarPdv(Request $request)
		{	
			$request->user()->authorizeRoles(['admin']);
			
			$pontoVenda = new PontoVenda();
			$pontoVenda->nome = $request['nome'];
			$pontoVenda->endereco = $request['endereco'];
			$pontoVenda->regiao = $request['regiao'];
			$pontoVenda->estado = $request['estado'];
			$pontoVenda->cidade = $request['cidade'];
			$pontoVenda->status = 1;
			$pontoVenda->save();
			
			//DB::table('estoque_pontovenda')->insert(['email' => 'john@example.com', 'votes' => 0]);
			return redirect('admin/pontosvenda')->with('status', 'Cadastrado com sucesso!');
		}
		
		public function cadastrarPedido(Request $request)
		{	
			$request->user()->authorizeRoles(['admin']);
			$valorTotal = 0;
			$next = false;
			$pedido = new Pedido();
			$pedido->id_repositor = Auth::user()->id;
			$pedido->id_pdv = $request['pdv'];
			$pedido->valor = 0;
			$pedido->save();
			
			foreach($request['produto'] as $index => $produto){
			$repositor = Auth::user()->id;
			//Retorna o Estoque do repositor logado
			$estoque = DB::table('estoque_repositor')->where('id_user',$repositor)->where('id_produto', $produto)->sum('estoque');
			//Verifica se o estoque do Repositor - Quantidade requerida é maior que 0
			if(($request['qtde'][$index] > 0) && ($estoque - $request['qtde'][$index]) >= 0){
			//Estoque do repositor é atualizado 
			DB::table('estoque_repositor')->where('id_user',$repositor)->where('id_produto', $produto)->update(['estoque' => $estoque - $request['qtde'][$index]]);
			
			DB::table('pedido_produtos')->insert(['id_pedido' => $pedido->id , 'id_produto' => $produto, 'qtde' => $request['qtde'][$index]]);
			
			DB::table('estoque_pontovenda')->insert(['id_pontovenda' => $request['pdv'] , 'id_produto' => $produto, 'estoque' => $request['qtde'][$index]]);
			
			$produto = Produto::find($produto);
			
			$valorTotal += $produto->preco *  $request['qtde'][$index] ;
			$next = true;
			}else{
			$next = false;
			}
			}
			
			if($next == true){
			$pedidoAtual = Pedido::find($pedido->id);
			$pedidoAtual->valor = $valorTotal;
			$pedidoAtual->save();
			return redirect('admin/pedidos')->with('status', 'Cadastrado com sucesso!');
			
			}else{
			
			$pedidoAtual = Pedido::find($pedido->id);
			$pedidoAtual->delete();
			return redirect('admin/pedidos')->with('error', 'Estoque insuficiente!');
			}
		}
		
		public function deletar_pdv(Request $request)
		{	
			$request->user()->authorizeRoles(['admin']);
			
			$pontoVenda = PontoVenda::find($request['idPdv']);
			$pontoVenda->status = 0;
			$pontoVenda->save();
			
			return redirect('admin/pontosvenda')->with('status', 'Deletado com sucesso!');
		}
		
		public function editar_pdv(Request $request)
		{	
			$request->user()->authorizeRoles(['admin']);
			
			$pontoVenda = PontoVenda::find($request['idPdv']);
			$pontoVenda->nome = $request['nome'];
			$pontoVenda->endereco = $request['endereco'];
			$pontoVenda->regiao = $request['regiao'];
			$pontoVenda->estado = $request['estado'];
			$pontoVenda->cidade = $request['cidade'];
			$pontoVenda->save();
			
			return redirect('admin/pontosvenda')->with('status', 'Editado com sucesso!');
		}
		
		public function estoquePdv(Request $request)
		{	
			$request->user()->authorizeRoles(['admin']);
			
			$produto = Produto::find($request['produto']);	
			$produto->quantidade = ($produto->quantidade - $request['quantidade']);
			$produto->save();
			DB::table('estoque_pontovenda')->insert(['id_pontovenda' => $request['pdv'] , 'id_produto' => $produto->id, 'estoque' => $request['quantidade']]);
			return redirect('admin/pontosvenda')->with('status', 'Cadastrado com sucesso!');
		}
		
		public function estoqueRepositor(Request $request)
		{
			$repositor = User::find(1)->roles->first()->name;
			$produto = Produto::find($request['produto']);	
			$produto->quantidade = ($produto->quantidade - $request['quantidade']);
			DB::table('estoque_pontovenda')->insert(['id_pontovenda' => $request['pdv'] , 'id_produto' => $produto->id, 'estoque' => $request['quantidade']]);
			return redirect('admin/pontovenda');
			//$repositor
		}
		
		public function mudarFuncao(Request $request) {
		$request->user()->authorizeRoles(['admin', 'gerente']);
		
		$user = User::where ( 'email', $request ['email'] )->first ();
		
		$user->roles ()->detach ();
		if ($request ['role_admin']) {
			$user->roles ()->attach ( Role::where ( 'name', 'admin' )->first () );
		}
		if ($request ['role_repositor']) {
			$user->roles ()->attach ( Role::where ( 'name', 'repositor' )->first () );
		}
		return redirect('admin/usuarios')->with('status', 'Usuário alterado com sucesso!');
	}
		
		
	}
