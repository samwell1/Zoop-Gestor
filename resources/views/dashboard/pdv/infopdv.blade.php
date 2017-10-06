@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-chart" data-background-color="green">
				<div class="ct-chart" id="dailySalesChart"></div>
			</div>
			<div class="card-content">
				<h4 class="title">Vendas semanais</h4>
				<p class="category"><span class="text-success"><i class="fa fa-long-arrow-up"></i> 55%  </span> increase in today sales.</p>
			</div>
			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">access_time</i> updated 4 minutes ago
				</div>
			</div>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Ponto de Venda: <b>{{$pontoVenda->nome}}</b></h4>
				<p class="category">Editar dados</p>
			</div>
			<div class="card-content">
				<form>
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">CNPJ (disabled)</label>
								<input type="text" class="form-control" value="{{$pontoVenda->cnpj}}" disabled>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">Nome</label>
								<input type="text" value="{{$pontoVenda->nome}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">Email</label>
								<input type="email" value="{{$pontoVenda->email}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">Fone</label>
								<input type="text" value="{{$pontoVenda->fone}}" class="form-control" >
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">Endereço</label>
								<input type="text" value="{{$pontoVenda->endereco}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group label-floating">
								<label class="control-label">Nº</label>
								<input type="text"  value="{{$pontoVenda->numero}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group label-floating">
								<label class="control-label">Região/Bairro</label>
								<input type="text"  value="{{$pontoVenda->regiao}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group label-floating">
								<label class="control-label">Last Name</label>
								<input type="text"  value="{{$pontoVenda->estado}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group label-floating">
								<label class="control-label">Last Name</label>
								<input type="text"  value="{{$pontoVenda->cidade}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group label-floating">
								<label class="control-label">CEP</label>
								<input type="text" value="{{$pontoVenda->cep}}" class="form-control" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Inscr. Estadual</label>
								<input type="text" value="{{$pontoVenda->ie}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">Inscr. Municipal</label>
								<input type="text" value="{{$pontoVenda->im}}" class="form-control" >
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group label-floating">
								<label class="control-label">ISUF</label>
								<input type="text" value="{{$pontoVenda->isuf}}" class="form-control" >
							</div>
						</div>
						
					</div>
					<button type="submit" class="btn btn-primary pull-right">Atualizar</button>
					<div class="clearfix"></div>
				</form>
			</div>
			
		</div>
	</div>
	
</div>

<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card card-nav-tabs">
			<div class="card-header" data-background-color="purple">
				<div class="nav-tabs-navigation">
					<div class="nav-tabs-wrapper">
						<span class="nav-tabs-title">Opções:</span>
						<ul class="nav nav-tabs" data-tabs="tabs">
							<li class="active">
								<a href="#imagens" data-toggle="tab">
									<i class="material-icons">local_offer</i>
									Imagens
								<div class="ripple-container"></div></a>
							</li>
							<li class="">
								<a href="#documentos" data-toggle="tab">
									<i class="material-icons">store</i>
									Documentos
								<div class="ripple-container"></div></a>
								</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="card-content">
				<div class="tab-content">
					<div class="tab-pane active" id="imagens">
				<div clas="row">
				@foreach($imagens as $img)
				<div class="col-lg-2">
				<a href="{{asset($img->imagem)}}" data-gallery="pdv-imagens" data-toggle="lightbox" data-type="image"><img src="{{asset($img->imagem)}}" class="img-fluid" style="width:100%;height:50%;" ></a>
				</div>
				@endforeach
				
			</div>	
			<div clas="row">
			<div class="col-md-12">
				<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#img">
			<i class="fa fa-plus"> </i> Imagens
		</button>
		<div class="clearfix"></div>
		</div>
		</div>
			</div>
			
			<div class="tab-pane" id="documentos">
				<div clas="row">
			@if($pontoVenda->contrato != null)
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Documento</th>
						<th>Ação</th>
					</thead>
					<tbody>
						<tr>
							<td>{{$pontoVenda->contrato}}</td>
							<td><a href="{{ URL::to('download', $pontoVenda->id) }}" class="btn btn-primary">Download</a></td>
						</tr>
					</tbody>
				</table>
			</div>
			@endif
			<h2>Nenhum documento cadastrado</h2>
			<form enctype="multipart/form-data" action="{{url('documentoPdv')}}"  method="POST">
				{{ csrf_field() }}
				<input type="hidden" name="idPdv" value="{{$pontoVenda->id}}">
				<div class="form-group is-empty is-fileinput">
					<input type="file" name="arquivo" class="arquivo">
					<div class="input-group">
						<input type="text" readonly="" class="form-control" value="" placeholder="Selecionar contrato">
						<span class="input-group-btn input-group-sm">
							<button type="button" class="btn btn-fab btn-fab-mini">
								<i class="fa fa-file-o"></i>
							</button>
						</span>
					</div>
				</div>
				<button type="submit" class="btn btn-primary pull-right btn-round">Salvar</button>
				<div class="clearfix"></div>
				
			</div>
		</form>
			</div>	
			</div>
			
			</div>
			</div>
		</div>
	</div>


<!-- Modal -->
<div class="modal fade" id="img" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Adicionar imagens</h4>
			</div>
			<form action="{{url('add_imagem_pdv')}}" id="upload" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
				<input type="hidden" value="{{$pontoVenda->id}}" name="idPdv">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group is-empty is-fileinput">
								<input type="file" name="imagem[]" class="image" multiple>
								<div class="input-group">
									<input type="text" readonly="" class="form-control" value="" placeholder="Selecione suas imagens" >
									<span class="input-group-btn input-group-sm">
										<button type="button" class="btn btn-fab btn-fab-mini">
											<i class="fa fa-file-image-o"></i>
										</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('post-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.2.0/ekko-lightbox.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.2.0/ekko-lightbox.css"></link>

<script>
	$(document).on('click', '[data-toggle="lightbox"]', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox({alwaysShowClose: true});
	});
</script>

<script>
	$(document).ready(function(){
		dataDailySalesChart = {
			labels: ['1ª', '2ª', '3ª', '4ª'],
			series: [
			[12, 17, 7, 17, 23, 18, 38]
			]
		};
		
		optionsDailySalesChart = {
			lineSmooth: Chartist.Interpolation.cardinal({
				tension: 0
			}),
			low: 0,
			high: 50, // creative tim: we recommend you to set the high sa the biggest value + something for a better look
			chartPadding: { top: 0, right: 0, bottom: 0, left: 0},
		}
		
		var dailySalesChart = new Chartist.Line('#dailySalesChart', dataDailySalesChart, optionsDailySalesChart);
		
		md.startAnimationForLineChart(dailySalesChart);
	});
</script>
@endsection													