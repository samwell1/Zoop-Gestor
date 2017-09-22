<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\DB;
	
	use App\Produto;
	use App\Pedido;
	use App\PontoVenda;
	use App\User;
	use App\Helpers\HeaderFunctions;
	
	use NFePHP\NFe\Tools;
	use NFePHP\NFe\Complements;
	use NFePHP\Common\Certificate;
	use NFePHP\Common\Soap\SoapCurl;
	use NFePHP\NFe\Make;
	use NFePHP\DA\NFe\Danfe;
	

	
	
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
		/*$uri = 'https://api.iugu.com/v1/';
		$usuarioSenha = '16f5eb7c2cb167556af5aceabdddcbd5:';
		$us8 = utf8_encode($usuarioSenha);
		$us64 = base64_encode($us8);
		echo $us8.'<br>'.$us64;*/
		
		
$username = '16f5eb7c2cb167556af5aceabdddcbd5:';
$password = '';

$username8 = utf8_encode($username);
$password8 = utf8_encode($password);
$username64 = base64_encode($username8);
$password64 = base64_encode($password8);
		
//curl_setopt($ch, CURLOPT_URL, "https://api.iugu.com/v1/invoices");
//$campos = '{"email":"Contafarma","due_date":"2017-09-22","items":[{"quantity":45,"description":"zoop variados","price_cents":4.19}],"payer":{"cpf_cnpj":"08671558924","name":"Joao da Silva","phone_prefix":"11","phone":"33244578","email":"joao@example.com","address":{"zip_code":"80230090","street":"Brasil","number":"46","district":"Centro","city":"Curitiba","state":"PR","country":"Brasil","complement":"Casa"}}}';
//$camposEnvia = json_encode($campos);
$login = 'login';
$password = 'password';
$url = 'https://api.iugu.com/v1/';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username64:$password64");
$result = curl_exec($ch);
curl_close($ch);  
echo($result);
/*
  //criando o recurso cURL 
  $cr = curl_init();
  //definindo a url de busca 
  curl_setopt($cr, CURLOPT_URL, "https://api.iugu.com/v1/"); 
  //definindo a url de busca 
  curl_setopt($cr, CURLOPT_RETURNTRANSFER, true); 
  
  curl_setopt($cr, CURLOPT_HTTPHEADER,
            array(
              "Authorization: Basic " . base64_encode($username64 . ":" . $password64)
));
  //curl_setopt($cr, CURLOPT_POST, TRUE);

  //curl_setopt($cr, CURLOPT_POSTFIELDS, $campos);

 //definindo uma variável para receber o conteúdo da página... 
  $retorno = curl_exec($cr); 
  //fechando-o para liberação do sistema. 
  curl_close($cr); 
  //fechamos o recurso e liberamos o sistema...
  //mostrando o conteúdo... 
  echo($retorno);*/
		}
		
		public function usuarios(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			$usuarios = User::all();
			$produtos = Produto::all();
			return view('dashboard.usuarios',['usuarios' => $usuarios,'produtos' => $produtos ]);
		}
		
		public function pontosdeVenda(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			//$pontosVenda = PontoVenda::all();
			$pontosVenda = PontoVenda::join('cidades', 'ponto_vendas.cidade', '=', 'cidades.id')->join('status','ponto_vendas.status','=','status.id')->leftJoin('estoque_pontovenda','ponto_vendas.id','=','estoque_pontovenda.id_pontovenda')->join('users','ponto_vendas.user_id','=','users.id')->select('users.name as repositor','ponto_vendas.*','cidades.nome as cidade','estoque_pontovenda.id_pontovenda as pdv',DB::raw('SUM(estoque_pontovenda.estoque) as estoque'),'status.nome as status')->groupBy('ponto_vendas.id')->orderBy('status.id')->get();
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
			$idUser = $request->user()->id;

			$produtos = Produto::join('estoque_repositor','produtos.id','=','estoque_repositor.id_produto')->where('estoque_repositor.id_user', $idUser)->select('produtos.*','estoque_repositor.estoque as estoque')->get();
			$pontovendas = PontoVenda::all();
			//$pedidos = Pedido::join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->join('users','pedidos.id_repositor','=','users.id')->select('produtos.nome as produto','produtos.modelo as modelo','pedido_produtos.qtde as qtde','users.name as repositor','pedidos.*')->get();
			//$pedidos = Pedido::join('users','pedidos.id_repositor','=','users.id')->join('ponto_vendas','pedidos.id_pdv','=','ponto_vendas.id')->leftJoin('nf','pedidos.id','=','nf.id_pedido')->select('users.name as repositor','pedidos.*','ponto_vendas.nome as ponto_venda','ponto_vendas.id as id_pontovenda','nf.status as nf')->groupBy('nf.id_pedido')->orderBy('pedidos.id')->get();
			$pedidos = Pedido::join('users','pedidos.id_repositor','=','users.id')->select('users.name as repositor','pedidos.*')->get();
			return view('dashboard.pedidos',['produtos'=> $produtos, 'pedidos' => $pedidos, 'pontovendas' => $pontovendas]);
		}
		
		public function estoque(Request $request)
		{
			$request->user()->authorizeRoles(['admin']);
			$repositores = User::whereHas('roles', function ($query) {$query->where('name', '=', 'repositor');})->leftJoin('estoque_repositor','users.id','=','estoque_repositor.id_user')->leftJoin('produtos','estoque_repositor.id_produto','produtos.id')->select('users.*','produtos.nome as produto','produtos.modelo as produtomodelo','estoque_repositor.estoque as estoque')->get();
			$produtos = Produto::all()->where('status', 1);
			$pontosVenda = PontoVenda::where('ponto_vendas.status', 1)->leftJoin('estoque_pontovenda','ponto_vendas.id','=','estoque_pontovenda.id_pontovenda')->leftJoin('produtos','estoque_pontovenda.id_produto','produtos.id')->select('ponto_vendas.*','produtos.nome as produto','produtos.modelo as produtomodelo','estoque_pontovenda.estoque as estoque')->get();
			
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
		
		public function tempo_agora() {
			return Date('d/m/Y - H:i');
		}
		
		}
				