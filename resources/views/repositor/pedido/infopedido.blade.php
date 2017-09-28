@extends('layouts.repositor')

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


@endsection

@section('post-script')
<script>
	$(".delete").on("submit", function(){
		return confirm("Tem certeza que deseja deletar este item?");
	});
</script>

@endsection									