@extends('layouts.master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Button trigger modal -->
		<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
			<i class="fa fa-plus"> </i> Usuário
		</button>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card card-nav-tabs">
			<div class="card-header" data-background-color="purple">
				<div class="nav-tabs-navigation">
					<div class="nav-tabs-wrapper">
						<span class="nav-tabs-title">Tarefas:</span>
						<ul class="nav nav-tabs" data-tabs="tabs">
							<li class="active">
								<a href="#info" data-toggle="tab">
									<i class="material-icons">account_box</i>
									Usuários
								<div class="ripple-container"></div></a>
							</li>
							<li class="">
								<a href="#funcoes" data-toggle="tab">
									<i class="material-icons">assignment_turned_in</i>
									Funções
								<div class="ripple-container"></div></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="card-content">
				<div class="tab-content">
					<div class="tab-pane active" id="info">
						<table class="table">
							<thead class="text-primary">
								<th>Nome</th>
								<th>Email</th>
								<th>Ações</th>
							</thead>
							<tbody>
								@if(count($usuarios) === 0 )
								<tr><td>
									<h3>
										Nenhum produto cadastrado
									</h3>
								</td>
								</tr>
								@endif
								@foreach($usuarios as $usuario)
								<tr>
									<td>{{$usuario->name}}</td>
									<td>{{$usuario->email}}</td>
									<td class="td-actions text-right">
										<button type="button" rel="tooltip" title="Editar" class="btn btn-primary btn-simple btn-xs" data-toggle="modal" data-target="#editar{{$usuario->id}}">
											<i class="material-icons">edit</i>
										</button>
										<form action="{{route('deletar_produto')}}" method="POST">
											{{ csrf_field() }}
											<input type="hidden" value="{{$usuario->id}}" name="idprod">
											<button type="submit" rel="tooltip" title="Deletar" class="btn btn-danger btn-simple btn-xs delete">
												<i class="material-icons">close</i>
											</button>
										</form>
									</td>
								</tr>
								<!-- editar -->
								<div class="modal fade" id="editar{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title" id="myModalLabel">Editar produto</h4>
											</div>
											<form enctype="multipart/form-data" action="{{route('editar_produto')}}"method="POST">
												{{ csrf_field() }}
												<input type="hidden" name="idprod" value="{{$usuario->id}}" class="form-control" >
												<div class="modal-body">
													<div class="row">
														<div class="col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Nome</label>
																<input type="text" name="nome" value="{{$usuario->name}}" class="form-control" >
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Modelo</label>
																<input type="text" name="modelo" value="{{$usuario->email}}" class="form-control" >
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Código</label>
																<input type="text" name="codigo" value="{{$usuario->password}}" class="form-control" >
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
					<div class="tab-pane" id="funcoes">
						<table class="table">
							<thead class="text-primary">
								<th>Nome</th>
								<th>Email</th>
								<th>Administrador</th>
								<th>Repositor</th>
								<th>Ações</th>
							</thead>
							<tbody>
								@if(count($usuarios) === 0 )
								<tr><td>
									<h3>
										Nenhum usuario cadastrado
									</h3>
								</td>
								</tr>
								@endif
								@foreach($usuarios as $usuario)
								<form action="{{ route('mudar_funcao') }}" method="post">
									<tr>
										<td>{{$usuario->name}}</td>
										<td>{{$usuario->email}}</td>
										<input type="hidden" name="email"
									value="{{ $usuario->email }}"></td>
									<td><input type="checkbox" {{ $usuario->hasRole('admin') ?
									'checked' : '' }} name="role_admin"></td>
									<td><input type="checkbox" {{ $usuario->hasRole('repositor') ?
									'checked' : '' }} name="role_repositor"></td>
									{{ csrf_field() }}
									<td><button type="submit"
									class="btn btn-warning btn-fill">Mudar permissão</button></td>
									
								</tr>
							</form>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
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
				<h4 class="modal-title" id="myModalLabel">Cadastrar usuário</h4>
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
								<label class="control-label">Email</label>
								<input type="email" name="email" class="form-control" >
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
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">Quantidade</label>
								<input type="number" name="quantidade" class="form-control" >
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group is-empty is-fileinput">
								
								<input type="file" name="imagem" id="image">
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
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label"></label>
										<img src="" id="preview" style="height:100px;width:auto;">
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
					$('#preview').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$("#image").change(function(){
			readURL(this);
		});
	</script>
	@endsection						