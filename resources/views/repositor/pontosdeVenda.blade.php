@extends('layouts.repositor')

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Button trigger modal -->
		<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
			<i class="fa fa-plus"> </i> Ponto de Venda
		</button>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Pontos de Venda</h4>
				<p class="category">Abaixo estão os pontos de venda</p>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Nome</th>
						<th>Endereço</th>
						<th>Região/Bairro</th>
						<th>Cidade</th>
						<th>Estado</th>
						<th>Ações</th>
					</thead>
					<tbody>
						@if(count($pontosvenda) === 0 )
						<tr><td>
							<h3>
								Nenhum ponto de venda cadastrado
							</h3>
						</td>
						</tr>
						@endif
						@foreach($pontosvenda as $pontovenda)
						<tr>
							<td>{{$pontovenda->nome}}</td>
							<td>{{$pontovenda->endereco}}</td>
							<td>{{$pontovenda->regiao}}</td>
							<td>{{$pontovenda->cidade}}</td>
							<td>{{$pontovenda->estado}}</td>
							<td class="td-actions text-right">
								<button type="button" rel="tooltip" title="Ver" class="btn btn-info btn-simple btn-xs" data-toggle="modal" data-target="#modal{{$pontovenda->id}}"> 
									<i class="fa fa-eye"></i>
								</button>
								<button type="button" rel="tooltip" title="Editar" class="btn btn-primary btn-simple btn-xs" data-toggle="modal" data-target="#editar{{$pontovenda->id}}">
									<i class="material-icons">edit</i>
								</button>
								<form action="{{route('deletar_pdv')}}" class="delete" method="POST">
									{{ csrf_field() }}
									<input type="hidden" value="{{$pontovenda->id}}" name="idPdv">
									<button type="submit" rel="tooltip" title="Deletar" class="btn btn-danger btn-simple btn-xs delete">
										<i class="material-icons">close</i>
									</button>
								</form>
								</td>
							</tr>
							<!-- Editar -->
							<div class="modal fade" id="editar{{$pontovenda->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Cadastrar</h4>
									</div>
									<form action="{{route('editar_pdv')}}" id="upload" method="POST">
										{{ csrf_field() }}
										<input type="hidden" name="idPdv" value="{{$pontovenda->id}}"">
										<div class="modal-body">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group label-floating">
														<label class="control-label">Nome</label>
														<input type="text" name="nome" value="{{$pontovenda->nome}}" class="form-control" >
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group label-floating">
														<label class="control-label">Endereço</label>
														<input type="text" name="endereco" class="form-control"  value="{{$pontovenda->endereco}}">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group label-floating">
														<label class="control-label">Bairro/Região</label>
														<input type="text" name="regiao" class="form-control" value="{{$pontovenda->regiao}}">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group label-floating">
														<label class="control-label">UF</label>
														<select id="uf" default="{{$pontovenda->estado}}" name="estado" class="uf form-control"></select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group label-floating">
														<label class="control-label">Cidade</label>
														<select id="cidade" default="{{$pontovenda->cidade}}"  name="cidade" class="cidade form-control"></select>
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
											<button type="submit" class="btn btn-primary">Cadastrar</button>
										</div>
									</form>
								</div>
							</div>
						</div> 
						<!-- Estoque -->
						<div class="modal fade" id="modal{{$pontovenda->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Abastecer estoque</h4>
									</div>
									<form action="{{route('estoque-pdv')}}" method="POST">
										{{ csrf_field() }}
										<input type="hidden" name="pdv" value="{{$pontovenda->id}}">
										<div class="modal-body">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group label-floating">
														<label class="control-label">Produto</label>
														<select name="produto" class="form-control">
															@foreach($produtos as $produto)
															<option value="{{$produto->id}}">{{$produto->nome}} - {{$produto->modelo}} | <b>Estoque: {{$produto->quantidade}}</b></option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group label-floating">
														<label class="control-label">Quantidade</label>
														<input type="number" name="quantidade" class="form-control" >
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
											<button type="submit" class="btn btn-primary">Cadastrar</button>
										</div>
									</form>
								</div>
							</div>
						</div>
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
				<h4 class="modal-title" id="myModalLabel">Cadastrar</h4>
			</div>
			<form action="{{route('user_cadastrar_pdv')}}" id="upload" method="POST">
				{{ csrf_field() }}
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Nome</label>
								<input type="text" name="nome" class="form-control" >
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Endereço</label>
								<input type="text" name="endereco" class="form-control" >
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Bairro/Região</label>
								<input type="text" name="regiao" class="form-control" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group label-floating">
								<label class="control-label">UF</label>
								<select id="uf" name="estado" class="uf form-control"></select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group label-floating">
								<label class="control-label">Cidade</label>
								<select id="cidade" name="cidade" class="cidade form-control"></select>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					<button type="submit" class="btn btn-primary">Cadastrar</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('post-script')

<script src="/vendor/artesaos/cidades/js/scripts.js"></script>

<script>
	$('.uf').ufs({
		onChange: function(uf){
			$('.cidade').cidades({uf: uf});
		}
	});
</script>

<script>
    $(".delete").on("submit", function(){
        return confirm("Tem certeza que deseja deletar este item?");
    });
</script>


@endsection	
