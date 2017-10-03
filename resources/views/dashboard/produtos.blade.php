@extends('layouts.master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Button trigger modal -->
		<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
			<i class="fa fa-plus"> </i> Produto
		</button>
	</div>
</div>
 <div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Produtos</h4>
				<p class="category">Abaixo estão os produtos</p>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Imagem</th>
						<th>Código</th>
						<th>Nome</th>
						<th>Modelo</th>
						<th>Peso</th>
						<th>Estoque</th>
						<th>Preço Unitário</th>
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
							<td><a href="#" data-toggle="modal" data-target="#photo{{$produto->id}}">
								<img src="{{asset($produto->imagem)}}" style="height:12%;width:auto;">
							</a></td>
							<td>{{$produto->codigo}}</td>
							<td>{{$produto->nome}}</td>
							<td>{{$produto->modelo}}</td>
							<td>{{$produto->peso}}</td>
							<td>{{$produto->quantidade}}</td>
							<td>{{formata_dinheiro($produto->preco)}}</td>
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
						<!-- editar -->
						<div class="modal fade" id="editar{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Editar produto</h4>
									</div>
									<form action="{{route('editar_produto')}}" method="POST">
										<div class="modal-body">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group label-floating">
														<label class="control-label">Nome</label>
														<input type="hidden" name="idProd" value="{{$produto->id}}" class="form-control" >
														<input type="text" name="nome" value="{{$produto->nome}}" class="form-control" >
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group label-floating">
														<label class="control-label">Modelo</label>
														<input type="text" name="modelo" value="{{$produto->modelo}}" class="form-control" >
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group label-floating">
														<label class="control-label">Código</label>
														<input type="text" name="codigo" value="{{$produto->codigo}}" class="form-control" >
													</div>
												</div>
											</div>
											<div class="row">
											<div class="col-md-2">
													<div class="form-group label-floating">
														<label class="control-label">Preço</label>
														<input type="text" name="preco" value="{{$produto->preco}}" class="form-control" >
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group label-floating">
														<label class="control-label">Quantidade</label>
														<input type="number" name="quantidade" value="{{$produto->quantidade}}" class="form-control" >
													</div>
												</div>
												<div class="col-md-5">
													<div class="form-group is-empty is-fileinput">
														
														<input type="file" name="imagem" class="image">
														<div class="input-group">
															<input type="text" readonly="" class="form-control" value="" placeholder="Selecione uma imagem">
															<span class="input-group-btn input-group-sm">
																<button type="button" class="btn btn-fab btn-fab-mini">
																	<i class="fa fa-file-image-o"></i>
																</button>
															</span>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label class="control-label"></label>
														<img src="{{$produto->imagem}}" class="preview" style="height:100px;width:auto;">
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
											<button type="submit" class="btn btn-primary">Confirmar</button>
										</div>
										{{ csrf_field() }}
									</form>
								</div>
							</div>
						</div>
						<!-- Modal -->
						<div class="modal fade" id="photo{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-xs-12">
												<img src="{{asset($produto->imagem)}}" class="img-responsive" style="height:20%;width:auto;">
											</div>
										</div>
									</div>
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
				<h4 class="modal-title" id="myModalLabel">Cadastrar produto</h4>
			</div>
			<form enctype="multipart/form-data" action="{{route('cadastrar-produto')}}" id="upload" method="POST">
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
								<label class="control-label">Modelo</label>
								<input type="text" name="modelo" class="form-control" >
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Código</label>
								<input type="text" name="codigo" class="form-control" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group label-floating">
								<label class="control-label">Quantidade</label>
								<input type="number" name="quantidade" class="form-control" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group label-floating">
								<label class="control-label">Preço</label>
								<input type="text" name="preco" class="form-control" >
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group is-empty is-fileinput">
								
								<input type="file" name="imagem" class="image">
								<div class="input-group">
									<input type="text" readonly="" class="form-control" placeholder="Selecione uma imagem">
									<span class="input-group-btn input-group-sm">
										<button type="button" class="btn btn-fab btn-fab-mini">
											<i class="fa fa-file-image-o"></i>
										</button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label"></label>
								<img src="" class="preview" style="height:100px;width:auto!important;">
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

<script>
    $(".delete").on("submit", function(){
        return confirm("Tem certeza que deseja deletar este item?");
	});
</script>
<script>
	function readURL(input) {
	if (input.files && input.files[0]) {
	var reader = new FileReader();
	
	reader.onload = function (e) {
	$('.preview').attr('src', e.target.result);
	}
	reader.readAsDataURL(input.files[0]);
	}
	}
	$('.image').change(function(){
	readURL(this);
	});
	</script>
	@endsection			