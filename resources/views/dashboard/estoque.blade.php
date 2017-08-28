@extends('layouts.master')

@section('content')
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card card-nav-tabs">
			<div class="card-header" data-background-color="purple">
				<div class="nav-tabs-navigation">
					<div class="nav-tabs-wrapper">
						<span class="nav-tabs-title">Estoques:</span>
						<ul class="nav nav-tabs" data-tabs="tabs">
							<li class="active">
								<a href="#produto" data-toggle="tab">
									<i class="material-icons">local_offer</i>
									Produtos
								<div class="ripple-container"></div></a>
							</li>
							<li class="">
								<a href="#pdv" data-toggle="tab">
									<i class="material-icons">store</i>
									Pontos de Venda
								<div class="ripple-container"></div></a>
							</li>
							<li class="">
								<a href="#repositor" data-toggle="tab">
									<i class="material-icons">transfer_within_a_station</i>
									Repositor
								<div class="ripple-container"></div></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
			<!-- Table PRODUTOS -->
			<div class="card-content">
				<div class="tab-content">
					<div class="tab-pane active" id="produto">
						<table class="table">
					<thead class="text-primary">
						<th>Código</th>
						<th>Produto/Modelo</th>
						<th>Estoque</th>
						<th>Ações</th>
					</thead>
					<tbody>
					@if(count($produtos) === 0 )
						<tr><td>
							<h3>
								Nenhum produto cadastrado
							</h3>
							</td>
						</tr>
						@endif
						@foreach($produtos as $produto)
						<tr>
							<td>{{$produto->codigo}}</td>
							<td>{{$produto->nome}}/{{$produto->modelo}}</td>
							<td>{{$produto->quantidade}}</td>
							<td class="td-actions text-right">
								<button type="button" rel="tooltip" title="Editar" class="btn btn-primary btn-simple btn-xs" data-toggle="modal" data-target="#editar{{$produto->id}}">
									<i class="material-icons">edit</i>
								</button>
								<form action="{{route('deletar_produto')}}" class="delete" method="POST">
									{{ csrf_field() }}
									<input type="hidden" value="{{$produto->id}}" name="idprod">
									<button type="submit" rel="tooltip" title="Deletar" class="btn btn-danger btn-simple btn-xs delete">
										<i class="material-icons">close</i>
									</button>
								</form>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
					</div>
					
					<!-- Table PONTOS VENDA -->
					<div class="tab-pane" id="pdv">
						<table class="table">
					<thead class="text-primary">
						<th>Ponto de Venda</th>
						<th>Produto/Modelo</th>
						<th>Quantidade</th>
						<th>Ações</th>
					</thead>
					<tbody>
					@if(count($pontosvenda) === 0 )
						<tr><td>
							<h3>
								Nenhum produto cadastrado
							</h3>
							</td>
						</tr>
						@endif
						@foreach($pontosvenda as $pontovenda)
						<tr>
							<td>{{$pontovenda->nome}}</td>
							<td>{{$pontovenda->produto}}/{{$pontovenda->produtomodelo}}</td>
							<td>{{$pontovenda->estoque}}</td>
							<td class="td-actions text-right">
								<button type="button" rel="tooltip" title="Editar" class="btn btn-primary btn-simple btn-xs" data-toggle="modal" data-target="#editar{{$produto->id}}">
									<i class="material-icons">edit</i>
								</button>
								<form action="{{route('deletar_produto')}}" class="delete" method="POST">
									{{ csrf_field() }}
									<input type="hidden" value="{{$produto->id}}" name="idprod">
									<button type="submit" rel="tooltip" title="Deletar" class="btn btn-danger btn-simple btn-xs delete">
										<i class="material-icons">close</i>
									</button>
								</form>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				</div>
		
		<!-- Table REPOSITOR -->
				
				<div class="tab-pane" id="repositor">
						<table class="table">
					<thead class="text-primary">
						<th>Repositor</th>
						<th>Produto/Modelo</th>
						<th>Estoque</th>
						<th>Ações</th>
					</thead>
					<tbody>
					@if(count($repositores) === 0 )
						<tr><td>
							<h3>
								Nenhum produto cadastrado
							</h3>
							</td>
						</tr>
						@endif
						@foreach($repositores as $repositor)
						<tr>
							<td>{{$repositor->name}}</td>
							<td>{{$repositor->produto}}/{{$repositor->produtomodelo}}</td>
							<td>{{$repositor->estoque}}</td>
							<td class="td-actions text-right">
								<button type="button" rel="tooltip" title="Editar" class="btn btn-primary btn-simple btn-xs" data-toggle="modal" data-target="#editar{{$produto->id}}">
									<i class="material-icons">edit</i>
								</button>
								<form action="{{route('deletar_produto')}}" class="delete" method="POST">
									{{ csrf_field() }}
									<input type="hidden" value="{{$produto->id}}" name="idprod">
									<button type="submit" rel="tooltip" title="Deletar" class="btn btn-danger btn-simple btn-xs delete">
										<i class="material-icons">close</i>
									</button>
								</form>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
 @endsection