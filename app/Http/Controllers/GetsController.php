<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	
	//use Illuminate\Support\Facades\Request;
	use Illuminate\Support\Facades\DB;
	use App\Produto;
	use App\Pedido;
	use App\PontoVenda;
	use App\User;
	use App\Helpers\HeaderFunctions;
	
	use NFePHP\NFe\Tools;
	use NFePHP\Common\Certificate;
	use NFePHP\Common\Soap\SoapCurl;
	use NFePHP\NFe\Make;
	
	use NFePHP\DA\NFe\Danfe;
	
	use Illuminate\Support\Facades\Storage;
	
	
	class GetsController extends Controller
	{
		public function __construct()
		{
			$this->middleware('auth');
		}
		
		public function index(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			$produtosEstoque = Produto::where('status',1)->sum('quantidade');
			$produtosVendendo = DB::table('estoque_pontovenda')->sum('estoque');
			$produtosRepositor = DB::table('estoque_repositor')->sum('estoque');
			//$produtos->quantidade;
			$totalProdutos = $produtosEstoque + $produtosVendendo;
			return view('home',['produtosEstoque' => $produtosEstoque,'produtosVendendo' => $produtosVendendo, 'totalProdutos' => $totalProdutos,'produtosRepositor' => $produtosRepositor]);
		}
		
		public function nf(Request $request)
		{
				$idPontoVenda = 1;
				$Produtos[] = array('id' => 1,'qtde' => '1');
				nfe($idPontoVenda,$Produtos);			
		}
		
		public function usuarios(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			$usuarios = User::all();
			return view('dashboard.usuarios',['usuarios' => $usuarios]);
		}
		
		public function pontosdeVenda(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			//$pontosVenda = PontoVenda::all();
			$pontosVenda = PontoVenda::where('status',1)->join('cidades', 'ponto_vendas.cidade', '=', 'cidades.id')->join('users','ponto_vendas.user_id','=','users.id')->select('ponto_vendas.*','cidades.nome as cidade','users.name as repositor')->get();
			$produtos = Produto::all();
			return view('dashboard.pontosdeVenda',['pontosvenda' => $pontosVenda,'produtos' => $produtos]);
		}
		
		public function produtos(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			$produtos = Produto::all()->where('status',1);;
			return view('dashboard.produtos',['produtos'=> $produtos]);
		}
		
		public function pedidos(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			//$produtos = Produto::all();
			$produtos = Produto::join('estoque_repositor','produtos.id','=','estoque_repositor.id_produto')->select('produtos.*','estoque_repositor.estoque as estoque')->get();
			$pontovendas = PontoVenda::all();
			//$pedidos = Pedido::join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->join('users','pedidos.id_repositor','=','users.id')->select('produtos.nome as produto','produtos.modelo as modelo','pedido_produtos.qtde as qtde','users.name as repositor','pedidos.*')->get();
			//$pedidos = Pedido::join('users','pedidos.id_repositor','=','users.id')->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->select('pedido_produtos.qtde as qtde','pedido_produtos.id_pedido as id_pedido','users.name as repositor','pedidos.*')->groupBy('repositor')->pluck('repositor');;
			$pedidos = Pedido::join('users','pedidos.id_repositor','=','users.id')->select('users.name as repositor','pedidos.*')->get();
			return view('dashboard.pedidos',['produtos'=> $produtos, 'pedidos' => $pedidos, 'pontovendas' => $pontovendas]);
		}
		
		public function estoque(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			$repositores = User::whereHas('roles', function ($query) {$query->where('name', '=', 'repositor');})->join('estoque_repositor','users.id','=','estoque_repositor.id_user')->join('produtos','estoque_repositor.id_produto','produtos.id')->select('users.*','produtos.nome as produto','produtos.modelo as produtomodelo','estoque_repositor.estoque as estoque')->get();
			$produtos = Produto::all()->where('status', 1);
			$pontosVenda = PontoVenda::where('ponto_vendas.status', 1)->join('estoque_pontovenda','ponto_vendas.id','=','estoque_pontovenda.id_pontovenda')->join('produtos','estoque_pontovenda.id_produto','produtos.id')->select('ponto_vendas.*','produtos.nome as produto','produtos.modelo as produtomodelo','estoque_pontovenda.estoque as estoque')->get();
			
			$produtosEstoque = Produto::where('status',1)->sum('quantidade');
		$produtosVendendo = DB::table('estoque_pontovenda')->sum('estoque');
		//$produtos->quantidade;
		$totalProdutos = $produtosEstoque + $produtosVendendo;
		
		
		return view('dashboard.estoque',['produtos'=> $produtos, 'pontosvenda' => $pontosVenda, 'repositores' => $repositores]);
		}
		
		public function infopedido(Request $request, $idPedido)
		{
		$request->user()->authorizeRoles(['admin']);
		//$produtos = Produto::all();
		//$pontovendas = PontoVenda::all();
		$pedido = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('users','pedidos.id_repositor','=','users.id')->select('pedido_produtos.qtde as qtde','users.name as repositor','pedidos.*')->first();
		$produtos = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->select('produtos.nome as nome','produtos.modelo as modelo','produtos.codigo as codigo','pedido_produtos.qtde as qtde','produtos.preco as preco')->get();
		//$pedido = Pedido::find($idPedido);
		
		return view('dashboard.pedido.infopedido',[ 'pedido' => $pedido, 'produtos' => $produtos]);
		}
		
		
		}
				