<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\DB;
	use Auth;
	
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
	
	
	use Iugu\iugu\lib;
	
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
	//GET
	$urlPgDireto = "http://api.iugu.com/v1/charge";
	$urlConsFatura = "http://api.iugu.com/v1/invoices/3755C6F80B4F4C2888FA8C6E4416DFCB";
	$urlTodasFatura =  "http://api.iugu.com/v1/invoices/";
	
	//POST
	$dadosFatura = Array('email' => 'wellerso@hotmail.com','due_date'=>'2017-09-29','payer' => Array(
	'cpf_cnpj' => '25182254000107','name' => 'Wellerson Samuel','phone_prefix' => '41','phone' => '96969696','email' => 'wellerson@hotmail.com', 'address' => Array(
	'zip_code' => '80230090', 'city' => 'CURITIBA', 'state' => 'PR', 'street' => 'AV.Brasil','number' => '3432','district' => 'Centro', 'country' => 'Brasil', 'complement' => 'Casa')
	),'payable_with' => 'bank_slip', 'items[]' => Array('name' => 'Zoop', 'description' => 'zoop', 'quantity' => '10','price_cents' => '400'));
	
	//$iuguApi = apiIugu('GET','',$urlConsFatura);
	//$retorno = json_decode($iuguApi);
	//echo $retorno->due_date.' - '.$retorno->id;
	$idPedido = 1;
		$produtos = DB::table('pedido_produtos')->where('pedido_produtos.id_pedido', $idPedido)->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->select('produtos.nome as nome','produtos.modelo as modelo','produtos.codigo as codigo','pedido_produtos.qtde as qtde','produtos.id as id_produto','produtos.preco as preco','pedido_produtos.id_produto as id_prod_ped','produtos.peso as peso')->get();
		foreach($produtos as $produto)
		echo $produto->nome.'<br>';
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
			$pontosVenda = PontoVenda::join('cidades', 'ponto_vendas.cidade', '=', 'cidades.id')->join('estados','ponto_vendas.estado','=','estados.id')->join('status','ponto_vendas.status','=','status.id')->leftJoin('estoque_pontovenda','ponto_vendas.id','=','estoque_pontovenda.id_pontovenda')->join('users','ponto_vendas.user_id','=','users.id')->select('users.name as repositor','ponto_vendas.*','cidades.nome as cidade','estados.nome as estado','estoque_pontovenda.id_pontovenda as pdv','ponto_vendas.max_estoque as estoque','status.nome as status')->groupBy('ponto_vendas.id')->orderBy('status.id')->get();
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
			$boleto = null;
			foreach($pedidos as $pedido){
			if($pedido->boleto != null || $pedido->boleto != ''){
			$urlFatura =  "http://api.iugu.com/v1/invoices/".$pedido->boleto;
			$iuguApi = apiIugu('GET', '', $urlFatura);
			$boleto = json_decode($iuguApi);
			$pedido->status = $boleto->status;
		}	
			}
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
		
		public function perfil(Request $request){
		$request->user()->authorizeRoles(['admin']);
		$idUsuario = Auth::user()->id;
		
		$usuario = User::find($idUsuario);
		
		return view('dashboard.perfil', ['usuario' => $usuario]);
		}
		public function infopdv(Request $request, $idPdv)
		{
		$request->user()->authorizeRoles(['admin']);
		$pontoVenda = PontoVenda::find($idPdv);
		//$pontoVenda = PontoVenda::find($idPdv)->leftJoin('imagens_pdv','ponto_vendas.id','=','imagens_pdv.id_pdv')->select('imagens_pdv.imagem as imagem','ponto_vendas.*')->first();
		$imagens = DB::table('imagens_pdv')->where('id_pdv',$pontoVenda->id)->select('imagens_pdv.imagem as imagem')->get();
		return view('dashboard.pdv.infopdv',[ 'pontoVenda' => $pontoVenda,'imagens' => $imagens]);
		}
		
		public function infopedido(Request $request, $idPedido)
		{
		$request->user()->authorizeRoles(['admin']);
		//$produtos = Produto::all();
		//$pontovendas = PontoVenda::all();
		$pedido = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('users','pedidos.id_repositor','=','users.id')->select('pedido_produtos.qtde as qtde','users.name as repositor','pedidos.*')->first();
		$produtos = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->select('produtos.nome as nome','produtos.modelo as modelo','produtos.codigo as codigo','pedido_produtos.qtde as qtde','produtos.preco as preco')->get();
		//$pedido = Pedido::find($idPedido);
		$boleto = null;
		if($pedido->boleto != null || $pedido->boleto != ''){
		$urlFatura =  "http://api.iugu.com/v1/invoices/".$pedido->boleto;
		$iuguApi = apiIugu('GET', '', $urlFatura);
		$boleto = json_decode($iuguApi);
		}
		
		return view('dashboard.pedido.infopedido',[ 'pedido' => $pedido, 'produtos' => $produtos,'boleto' => $boleto]);
		
		}
		
		public function dwdocumentos(Request $request,$opcao)
		{
			$request->user()->authorizeRoles(['admin']);
			if($opcao == 1){
				return view('dashboard.documentos');
			}else{
			switch($opcao){
			case 2:
			$file = public_path(). "/arquivos/briefing_zoop.xlsx";
			break;
			case 3:
			$file = public_path(). "/arquivos/cadastro_info_pdv_zoop.docx";
			break;
			case 4:
			$file = public_path(). "/arquivos/proposta_cliente_zoop.docx";
			break;
			}
		    return response()->download($file);
			}
		}
		
		public function tempo_agora() {
			return Date('d/m/Y - H:i');
		}
		
		public function retornar()
		{
		if(Auth::user()->roles->first()->name == 'admin')
		{
			return redirect()->route('home');
		}else if(Auth::user()->roles->first()->name == 'repositor')
		{
			return redirect()->route('user_home');
		}
		}
		
		public function download(Request $request,$caminho1)
		{
				$request->user()->authorizeRoles(['admin']);
				$pdv = PontoVenda::find(1);
				
				$file = Storage::get($pdv->contrato);
			//	if($file == null)
				//$file = $caminho1.'/'.$caminho2.'/'.$caminho3.'/'.$arquivo;
			//echo $file;
				return response()->download($file);
				
		}
		}
				