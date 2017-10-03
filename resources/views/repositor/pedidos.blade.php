@extends('layouts.repositor')

@section('content')
<style>
	.hiden{
	display:none;
	visibility:hidden;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<!-- Button trigger modal -->
		<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
			<i class="fa fa-plus"> </i> Pedido
		</button>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Pedidos</h4>
				<p class="category">Abaixo estão os Pedidos</p>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>ID</th>
						<th>Data</th>
						<th>Ponto de Venda</th>
						<th>Valor</th>
						<th>Status</th>
						<th>Ações</th>
					</thead>
					<tbody>
					@if(count($pedidos) === 0 )
						<tr><td>
							<h3>
								Nenhum pedido cadastrado
							</h3>
						</td>
						</tr>
						@endif
						@foreach($pedidos as $pedido)
						<tr>
							<td>{{$pedido->id}}</td>
							<td>{{formata_data($pedido->created_at)}}</td>
							<td>{{$pedido->ponto_venda}}</td>
							<td>{{formata_dinheiro($pedido->valor)}}</td>
							@if($pedido->status == 'paid')
							<td><button type="button" rel="tooltip" title="Boleto Pago" class="btn btn-success btn-simple btn-xs"> 
									<i class="fa fa-circle"></i> {{$pedido->status}}
								</button> </td>
								@elseif($pedido->status == 'pending')
							<td>
								<button type="button" rel="tooltip" title="Boleto Pendente" class="btn btn-warning btn-simple btn-xs" data-toggle="modal" "> 
									<i class="fa fa-circle"></i> {{$pedido->status}}
								</button></td>
								@elseif($pedido->status == 'cancel')
								<td>
								<button type="button" rel="tooltip" title="Boleto Cancelado" class="btn btn-danger btn-simple btn-xs" data-toggle="modal">
									<i class="fa fa-circle"></i> {{$pedido->status}}
								</button></td>
								@else
								<td>
								<button type="button" rel="tooltip" title="Boleto não EMITIDO" class="btn btn-warning btn-simple btn-xs" data-toggle="modal" >
									<i class="fa fa-circle"></i> {{$pedido->status}}
								</button></td>
								@endif
							<td class="td-actions text-right">
								<a href="{{ URL::to('user/pedido/' . $pedido->id) }}"><button type="button" rel="tooltip" title="Visualizar" class="btn btn-danger btn-simple btn-xs delete">
									<i class="material-icons">pageview</i>
								</button></a>
								@if($pedido->nf != null || $pedido->nf != '')
								<form action="{{ URL::to('user/pedido/' . $pedido->id.'/enviNfe') }}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" value="{{$pedido->id}}" name="idPedido">
								<input type="hidden" value="{{$pedido->id_pontovenda}}" name="idPdv">
								<button type="submit" rel="tooltip" title="Enviar NF-e" class="btn btn-success btn-simple btn-xs ">
									<i class="material-icons">send</i>
								</button>
								</form>
								@else
								<form action="{{ URL::to('user/pedido/' . $pedido->id.'/emitNfe') }}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" value="{{$pedido->id}}" name="idPedido">
								<input type="hidden" value="{{$pedido->id_pontovenda}}" name="idPdv">
								<button type="submit" rel="tooltip" title="Emitir NF-e" class="btn btn-info btn-simple btn-xs">
									<i class="material-icons">assignment</i>
								</button>
								</form>
								@endif
						</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Realizar pedido</h4>
			</div>
			@if(count($pontovendas) == 0)
				<div class="modal-body">
					<div class="row">
					<div class="col-md-12">
					<h3>Nenhum <b>Ponto de Venda</b> cadastrado. <a href="{{route('user_pdv')}}">Cadastre</a> um antes de realizar um pedido.</h3>
					</div>
					</div>
					</div>
			@elseif(count($produtos) == 0)
			<div class="modal-body">
					<div class="row">
					<div class="col-md-12">
					<h3>Você <b>NÃO</b> possui  estoque. Contato um administrador <b>ZOOP</b>.</h3>
					</div>
					</div>
					</div>
			@else
			<form enctype="multipart/form-data" action="{{route('cadastrar_pedido')}}"  method="POST">
				{{ csrf_field() }}
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group label-floating">
								<label class="control-label">Ponto de Venda</label>
								<select name="pdv" class="form-control">
									@foreach($pontovendas as $pontovenda)
									<option value="{{$pontovenda->id}}">{{$pontovenda->nome}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="row produtos">
						<div class="col-md-6">
							<div class="form-group label-floating ">
								<label class="control-label">Produto</label>
								<select name="produto[]" class="form-control">
									@foreach($produtos as $produto)
									<option value="{{$produto->id}}">{{$produto->nome}} - {{$produto->modelo}} | <b>Estoque: {{$produto->estoque}}</b></option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4" >
							<div class="form-group label-floating">
								<label class="control-label">Quantidade</label>
								<input type="number" name="qtde[]" class="form-control" value="1" min="1" max="{{$produto->estoque}}" required/>
							</div>
						</div>
					</div>
					<div class="local">
						
					</div>
					@if(count($produtos) > 1 )
					<button type="button" class="btn btn-default clonador"><i class="fa fa-plus"></i> Produto</button>
					@endif
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Confirmar</button>
				</div>
			</form>
			@endif
		</div>
	</div>
</div>

<div class="row produtos hiden">
	<div class="col-md-6">
		<div class="form-group label-floating ">
			<label class="control-label">Produto</label>
			<select name="produto[]" class="form-control">
				@foreach($produtos as $produto)
				<option value="{{$produto->id}}">{{$produto->nome}} - {{$produto->modelo}} | <b>Estoque: {{$produto->estoque}}</b></option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-md-3" >
		<div class="form-group label-floating">
			<label class="control-label">Quantidade</label>
			<input type="number" name="qtde[]" class="form-control" value="1">
		</div>
	</div>
	<div class="col-md-3" >
	<div class="form-group label-floating">
	<label class="control-label"></label>
	<button type="button" class="btn btn-warning btn_remove"><i class="fa fa-trash-o"></i> Deletar</button>	
	</div>
	</div>
	
	
	</div>
	@endsection
	
	@section('post-script')
	<script>
	$(".delete").on("submit", function(){
	return confirm("Tem certeza que deseja deletar este item?");
	});
	
	$('.clonador').click(function(){
	//clona o modelo oculto, clone(true) para copiar também os manipuladores de eventos
	$clone = $('.produtos.hiden').clone(true);
	//remove a classe que oculta o modelo do elemento clonado
	$clone.removeClass('hiden');
	
	//adiciona no form
	$('.local').append($clone);
	
	});
	//Para remover
	$('.btn_remove').click(function(){
	$(this).parents('.produtos').remove();
	});
	</script>
	
	
	@endsection										