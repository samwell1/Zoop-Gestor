@extends('layouts.master')

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
						<th>Cadastrado por</th>
						<th>Estoque MAX</th>
						<th>Status</th>
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
							<td>{{$pontovenda->endereco}}, {{$pontovenda->numero}}</td>
							<td>{{$pontovenda->regiao}}</td>
							<td>{{$pontovenda->cidade}}/{{$pontovenda->estado}}</td>
							<td>{{$pontovenda->repositor}}</td>
							@if($pontovenda->estoque == null)
							<td>0</td>
							@else
							<td>{{$pontovenda->estoque}}</td>
							@endif
							@if($pontovenda->status == 'Ativo')
							<td><button type="button" rel="tooltip" title="Ponto de venda ativado" class="btn btn-success btn-simple btn-xs"> 
									<i class="fa fa-circle"></i> {{$pontovenda->status}}
								</button> </td>
								@elseif($pontovenda->status == 'Pendente')
							<td>
								<button type="button" rel="tooltip" title="Ponto de venda pendente (contrato, documentos)" class="btn btn-warning btn-simple btn-xs" data-toggle="modal" data-target="#modal{{$pontovenda->id}}"> 
									<i class="fa fa-circle"></i> {{$pontovenda->status}}
								</button></td>
								@elseif($pontovenda->status == 'Desativado')
								<td>
								<button type="button" rel="tooltip" title="Ponto de venda desativado" class="btn btn-danger btn-simple btn-xs" data-toggle="modal" data-target="#editar{{$pontovenda->id}}">
									<i class="fa fa-circle"></i> {{$pontovenda->status}}
								</button></td>
								@endif
								
							<td class="td-actions text-right">
								<a href="{{ URL::to('admin/pdv/' . $pontovenda->id) }}"  rel="tooltip" title="Ver" class="btn btn-info btn-simple btn-xs" > 
									<i class="fa fa-eye"></i>
								</a>
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
				<h4 class="modal-title" id="myModalLabel">Cadastrar Ponto de Venda</h4>
			</div>
			<form action="{{route('cadastrar-pdv')}}" id="upload" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Nome</label>
								<input type="text" name="nome" class="form-control" required/>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">CNPJ</label>
								<input type="text" name="cnpj" class=" cnpj form-control" required/>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">CEP</label>
								<input type="text" name="cep" class="cep form-control" required/>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Endereço</label>
								<input type="text" name="endereco" class="form-control" required/>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group label-floating">
								<label class="control-label">Nº</label>
								<input type="text" name="numero" class="form-control" required/>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">Bairro/Região</label>
								<input type="text" name="regiao" class="form-control" required/>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">Telefone</label>
								<input type="text" name="telefone" class="fone form-control" required/>
							</div>
						</div>
						
					</div>
					
					<div class="row">
						<div class="col-md-2">
							<div class="form-group label">
								<label class="control-label">UF</label>
								<select id="uf" name="estado" class="uf form-control" required/></select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label">
								<label class="control-label">Cidade</label>
								<select id="cidade" name="cidade" class="cidade form-control" required/></select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Email</label>
								<input type="text" name="email" class="form-control" required/>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group label-floating">
								<label class="control-label">Estoque Máximo</label>
								<input type="number" class="form-control" name="estoque" required/>
							</div>
						</div>
					</div>
					<div class="row">
												<div class="col-md-6">
													<div class="form-group is-empty is-fileinput">
														
														<input type="file" name="imagem[]" class="image" multiple>
														<div class="input-group">
															<input type="text" readonly="" class="form-control" value="" placeholder="Selecione uma imagem" >
															<span class="input-group-btn input-group-sm">
																<button type="button" class="btn btn-fab btn-fab-mini">
																	<i class="fa fa-file-image-o"></i>
																</button>
															</span>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"></label>
														<img src="" class="preview" style="height:100px;width:auto;">
													</div>
												</div>
											</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Cadastrar</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('post-script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
<script src="/vendor/artesaos/cidades/js/scripts.js"></script>

<script>
	$(document).ready(function(){
		var maskBehavior = function (val) {
			return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		options = {onKeyPress: function(val, e, field, options) {
			field.mask(maskBehavior.apply({}, arguments), options);
		}
		};
		
		$('.fone').mask(maskBehavior, options);
		$('.cep').mask('00000-000');
		//$('.cpf').mask('000.000.000-00', {reverse: true});
		$('.cnpj').mask('00.000.000/0000-00', {reverse: true});
		
	});
</script>

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
