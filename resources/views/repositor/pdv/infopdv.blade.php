@extends('layouts.repositor')

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
					<button type="submit" class="btn btn-primary pull-right">Atualizar</button>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<hr>
	
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header" data-background-color="purple">
					<h4 class="title">Ponto de Venda: <b>{{$pontoVenda->nome}}</b></h4>
					<p class="category">Editar dados</p>
				</div>
				<div class="card-content">
					<form>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group label-floating">
									<label class="control-label">IE</label>
									<input type="text" value="{{$pontoVenda->ie}}" class="form-control" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group label-floating">
									<label class="control-label">ISUF</label>
									<input type="text" value="{{$pontoVenda->isuf}}" class="form-control" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group label-floating">
									<label class="control-label">IM</label>
									<input type="text" value="{{$pontoVenda->im}}" class="form-control" >
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-primary pull-right">Atualizar</button>
						<div class="clearfix"></div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
	
	
	
	
	@endsection
	
	@section('post-script')
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