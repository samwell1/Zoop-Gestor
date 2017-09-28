<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Validator;
	use App\Role;
	use App\Produto;
	use App\PontoVenda;
	use App\Pedido;
	use App\User;
	use Auth;
	
	class PostsController extends Controller
	{
		public function cadastrarUsuario(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			
			
			
			 $validator = Validator::make($request->all(), [
			'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('admin/usuarios')->with('status', $validator);
        }

			$usuario = new User();
			$usuario->name = $request['nome'];
			$usuario->email = $request['email'];
			$usuario->password = $request['senha'];
			
			if($usuario->save())
			{
			$usuario->roles()->attach(Role::where('name', 'repositor')->first());
			return redirect('admin/usuarios')->with('status', 'Produto cadastrado com sucesso!');
			}else
			{
			return redirect('admin/usuarios')->with('error', 'Erro!');
			}
			
		}
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
			$produto = Produto::find($request['idProd']);
			$produto->nome = $request['nome'];
			$produto->modelo = $request['modelo'];
			$produto->quantidade = $request['quantidade'];
			$produto->preco =  formata_valor($request['preco']);
			//Movendo a imagem para a pasta
			if($request['imagem'] != null || $request['imagem'] != ''){
			$imageName = $request['codigo'].'.'.$request->imagem->getClientOriginalExtension();
			$caminho = public_path('uploads/produtos/'.$request['nome'].'-'.$request['modelo']);
			$request->imagem->move(($caminho), $imageName);
			$produto->imagem = 'uploads/produtos/'.$request['nome'].'-'.$request['modelo'].'/'.$imageName;
			$produto->imagem = $request['imagem'];
			}
			//fim
			if($produto->save())
			return redirect('admin/produtos')->with('status', 'Produto editado com sucesso!');
			else
				return redirect('admin/produtos')->with('error', 'Erro!');
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
			$request->user()->authorizeRoles(['admin','repositor']);
			$route = '';
			if(Auth::user()->hasRole('admin'))
			{
				$route = 'admin/pedidos';
			} 
			
			if(Auth::user()->hasRole('repositor'))
			{
				$route = 'user/pedidos';
			}
			
			$valorTotal = 0;
			$next = false;
			$pedido = new Pedido();
			$pedido->id_repositor = Auth::user()->id;
			$pedido->id_pdv = $request['pdv'];
			$pedido->valor = 0;
			$pedido->save();
			$items[] = '';
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
					
					$items[] = ['name' => $produto->nome.' - '.$produto->modelo, 'description' => $produto->nome.' - '.$produto->modelo, 'quantity' => $request['qtde'][$index], 'price_cents' =>  $produto->preco * 100];
					$next = true;
					}else{
					$next = false;
				}
			}
			
			if($next == true){
			$pdv = PontoVenda::find($request['pdv'])->join('cidades','ponto_vendas.cidade', '=', 'cidades.id')->select('cidades.nome as cidades','ponto_vendas.*')->first();
			
			$arrayItem = "";
			
			foreach($items as $item)
			$arrayItem = $item;
			
			$dadosFatura = Array('email' => $pdv->email, 'due_date'=> strval(date('Y-m-d', strtotime('+3 days'))),
			'payer' => Array(	'cpf_cnpj' => $pdv->cnpj,'name' => $pdv->nome,
			'phone_prefix' => '41', 'phone' => $pdv->fone,'email' => $pdv->email,
			'address' => Array('zip_code' => $pdv->cep, 'city' => $pdv->cidades, 'state' => $pdv->estado, 
			'street' => $pdv->endereco,'number' => $pdv->numero, 'district' => $pdv->regiao, 'country' => 'Brasil', 
			'complement' => 'Comercial')),
			'payable_with' => 'bank_slip', 
			'items[]' => $arrayItem);
			
			$urlFatura =  "http://api.iugu.com/v1/invoices/";
			
			$iuguApi = apiIugu('POST', $dadosFatura, $urlFatura);
			
			$retorno = json_decode($iuguApi);
			$idBoleto = $retorno->id;
	
				$pedidoAtual = Pedido::find($pedido->id);
				$pedidoAtual->valor = $valorTotal;
				$pedidoAtual->boleto = $idBoleto;
				$pedidoAtual->save();
				return redirect($route)->with('status', 'Cadastrado com sucesso!');
				
				}else{
				
				$pedidoAtual = Pedido::find($pedido->id);
				$pedidoAtual->delete();
				return redirect($route)->with('error', 'Estoque insuficiente!');
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
			
			if(DB::table('estoque_pontovenda')->where('id_pontovenda', $request['pdv'])->update(['estoque' => $estoque + $request['quantidade']])){
			$produto->quantidade = ($produto->quantidade - $request['quantidade']);
			$produto->save();
				return redirect('admin/pontosvenda')->with('status', 'Atualizado com sucesso!');
			}else if(DB::table('estoque_pontovenda')->insert(['id_pontovenda' => $request['pdv'] , 'id_produto' => $produto->id, 'estoque' => $request['quantidade']])){
			$produto->quantidade = ($produto->quantidade - $request['quantidade']);
			$produto->save();
			return redirect('admin/pontosvenda')->with('status', 'Cadastrado com sucesso!');
			}else{
			return redirect('admin/pontosvenda')->with('error', 'Erro!');
			}
				
			

			
		}
		
		public function estoqueRepositor(Request $request)
		{
			//$repositor = User::find(1)->roles->first()->name;
			$request->user()->authorizeRoles(['admin']);
			
			$produto = Produto::find($request['produto']);	
			$estoque = DB::table('estoque_repositor')->where('id_user',$request['idUser'])->where('id_produto', $request['produto'])->sum('estoque');
			
			if(DB::table('estoque_repositor')->where('id_user', $request['idUser'])->where('id_produto', $request['produto'])->update(['estoque' => $estoque + $request['quantidade']])){
				$produto->quantidade = ($produto->quantidade - $request['quantidade']);
				$produto->save();
				return redirect('admin/usuarios')->with('status', 'Estoque atualizado com sucesso!');	
			}
			else if(DB::table('estoque_repositor')->insert(['id_user' => $request['idUser'] , 'id_produto' => $request['produto'], 'estoque' => $request['quantidade']])){
				$produto->quantidade = ($produto->quantidade - $request['quantidade']);
				$produto->save();
				return redirect('admin/usuarios')->with('status', 'Estoque cadastrado com sucesso!');	
			}
			else{
				return redirect('admin/usuarios')->with('error', 'Erro!');
			}
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
