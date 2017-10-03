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
						<th>Estoque</th>
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
							@if($pontovenda->estoque == null)
							<td>0</td>
							@else
							<td>{{$pontovenda->estoque}}</td>
							@endif
							@if($pontovenda->status == 'Ativo')
							<td><button type="button" rel="tooltip" title="Ponto de venda ativado" class="btn btn-success btn-simple btn-xs"> 
								<i class="fa fa-circle"></i> {{$pontovenda->status}}
							</button> </td>
							<td>
								<a href="{{ URL::to('user/pdv/' . $pontovenda->id) }} "><button type="button" rel="tooltip" title="Visualizar" class="btn btn-danger btn-simple btn-xs delete">
									<i class="material-icons">pageview</i>
								</button></a>
								@if(count($produtos) > 1 )
								<button type="button" class="btn btn-default clonador"><i class="fa fa-plus"></i> Produto</button>
								@endif
								<button type="button"
								class="btn btn-info btn-fill"  data-toggle="modal" data-target="#myModal{{$pontovenda->id}}">Abastecer Estoque</button>
							</td>
							@elseif($pontovenda->status == 'Pendente')
							<td>
								
								<button type="button" rel="tooltip" title="Ponto de venda pendente (contrato, documentos)" class="btn btn-warning btn-simple btn-xs" data-toggle="modal" data-target="#modal{{$pontovenda->id}}"> 
									<i class="fa fa-circle"></i> {{$pontovenda->status}}
								</button></td>
								<td>
									<a href="{{ URL::to('user/pdv/' . $pontovenda->id) }} "><button type="button" rel="tooltip" title="Visualizar" class="btn btn-danger btn-simple btn-xs delete">
										<i class="material-icons">pageview</i>
									</button></a></td>
									@elseif($pontovenda->status == 'Desativado')
									<td>
										<button type="button" rel="tooltip" title="Ponto de venda desativado" class="btn btn-danger btn-simple btn-xs" data-toggle="modal" data-target="#editar{{$pontovenda->id}}">
											<i class="fa fa-circle"></i> {{$pontovenda->status}}
										</button></td>
										<td>
											<a href="{{ URL::to('user/pdv/' . $pontovenda->id) }} "><button type="button" rel="tooltip" title="Visualizar" class="btn btn-danger btn-simple btn-xs delete">
												<i class="material-icons">pageview</i>
											</button></a></td>
											@endif
											
						</tr>
						
						<!-- Modal -->
						<div class="modal fade" id="myModal{{$pontovenda->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Abastecer Estoque</h4>
									</div>
									@if(count($produtos) == 0)
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
											<input type="hidden" name="pdv" value="{{$pontovenda->id}}">
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
														<input type="number" name="qtde[]" class="form-control" value="1">
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
											<button type="submit" class="btn btn-primary">Concluir</button>
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
						@endforeach
					</tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>

<!-- Cadastrar PDV -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cadastrar Ponto de Venda</h4>
			</div>
			<form action="{{route('user_cadastrar_pdv')}}" id="upload" method="POST">
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
<script>(function ( $ ) {

    $.fn.ufs = function(options) {

        var select = $(this);

        var settings = $.extend({
            'default': select.attr('default'),
            'onChange': function(uf){}
        }, options );

        $.get("{{url('')}}/uf", null, function (json) {

            $.each(json, function (key, value) {
                select.append('<option value="' + value.id + '" '+(settings.default==value.uf?'selected':'')+'>' + value.uf + '</option>');
            })

            settings.onChange(select.val());

        }, 'json');

        select.change(function(){
            settings.onChange(select.val());
        });
    };


    $.fn.cidades = function(options) {

        var select = $(this);

        var settings = $.extend({
            'default': select.attr('default'),
            'uf': null
        }, options );

        if(settings.uf == null)
            console.warn('Nenhuma UF informada');
        else{

            select.html('<option>Carregando..</option>');

            $.get("{{url('')}}/cidades/"+settings.uf, null, function (json) {
                select.html('<option value="">Selecione</option>');

                $.each(json, function (key, value) {
                    select.append('<option value="' + value.id + '" '+((settings.default==value.id || settings.default==value.nome)?'selected':'')+'>' + value.nome + '</option>');
                })

            }, 'json');

        }
    };

}( jQuery ));</script>

<script>
	$(document).ready(function(){
		var maskBehavior = function (val) {
			return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		options = {onKeyPress: function(val, e, field, options) {
			field.mask(maskBehavior.apply({}, arguments), options);
		}
		};
		
		//$('.fone').mask(maskBehavior, options);
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
