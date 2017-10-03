@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-md-12">
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Pedido: <b>{{$pedido->id}}</b> | Repositor: <b>{{$pedido->repositor}}</b></h4>
				<p class="category">Abaixo estão os produtos deste pedido</p>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Código</th>
						<th>Produto</th>
						<th>Quantidade</th>
						<th>Valor Unitário</th>
						<th>Valor Total</th>
					</thead>
					<tbody>
						@foreach($produtos as $produto)
						<tr>
							<td>{{$produto->codigo}}</td>
							<td>{{$produto->nome}} - {{$produto->modelo}}</td>
							<td>{{$produto->qtde}}</td>
							<td>{{formata_dinheiro($produto->preco)}}</td>
							<td><?php echo formata_dinheiro($produto->preco * $produto->qtde)?></td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<h4 class="text-right"><b>Total: {{formata_dinheiro($pedido->valor)}}</b></h4>
			</div>
		</div>
	</div>
</div>
@if($pedido->boleto == null || $pedido->boleto == '')
<form action="{{ URL::to('user/pedido/' . $pedido->id.'/emitBoleto') }}" method="POST">
	{{ csrf_field() }}
	<input type="hidden" value="{{$pedido->id}}" name="idPedido">
	<input type="hidden" value="{{$pedido->id_pdv}}" name="idPdv">
<button type="submit" class="btn btn-success pull-right">
	<i class="fa fa-plus"> </i> Emitir Boleto
</button>
</form>
@endif

@if($pedido->nf != null || $pedido->nf != '')
<form action="{{ URL::to('user/pedido/' . $pedido->id.'/enviNfe') }}" method="POST">
	{{ csrf_field() }}
	<input type="hidden" value="{{$pedido->id}}" name="idPedido">
	<input type="hidden" value="{{$pedido->id_pdv}}" name="idPdv">
	<button type="submit" class="btn btn-info pull-right">
		<i class="fa fa-plus"> </i> enviar Nota fiscal
	</button>
</form>

@else
<form action="{{ URL::to('user/pedido/' . $pedido->id.'/emitNfe') }}" method="POST">
	{{ csrf_field() }}
	<input type="hidden" value="{{$pedido->id}}" name="idPedido">
	<input type="hidden" value="{{$pedido->id_pdv}}" name="idPdv">
	<button type="submit" class="btn btn-info pull-right">
		<i class="fa fa-plus"> </i> Emitir Nota fiscal
	</button>
</form>
@endif

@if($pedido->boleto != null || $pedido->boleto != '')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Status: <b>{{$boleto->status}}</b> | Data de Vencimento Boleto: <b> {{formata_data($boleto->due_date)}}</b> </h4>
				<p class="category">Abaixo estão as ações do boleto</p>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Data</th>
						<th>Descrição</th>
						<th>Notas</th>
					</thead>
					<tbody>
						@foreach($boleto->logs as $logs)
						<tr>
							<td>{{$logs->created_at}}</td>
							<td>{{$logs->description}}</td>
							<td>{{$logs->notes}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif

@endsection

@section('post-script')
<script>
	$(".delete").on("submit", function(){
	return confirm("Tem certeza que deseja deletar este item?");
	});
	</script>
	
	@endsection										