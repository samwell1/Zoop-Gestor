@extends('layouts.repositor')

@section('content')
<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="card card-stats">
								<div class="card-header" data-background-color="blue">
									<i class="fa fa-user"></i>
								</div>
								<div class="card-content">
									<p class="category">Seu Estoque</p>
									<h3 class="title"><small>{{$estoqueRepositor}} pçs</small></h3>
								</div>
								@if($estoqueRepositor < 10)
								<div class="card-footer">
									<div class="stats">
										<i class="material-icons text-danger">warning</i> Estoque baixo
									</div>
								</div>
								@else
									<div class="card-footer">
									<div class="stats">
										<i class="material-icons">info</i> Estoque normal
									</div>
								</div>
								@endif
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="card card-stats">
								<div class="card-header" data-background-color="green">
									<i class="material-icons">store</i>
								</div>
								<div class="card-content">
										<p class="category">Pontos de Venda</p>
									<h3 class="title"><small>{{$estoquePdv}} pçs</small></h3>
								</div>
								<div class="card-footer">
									<div class="stats">
										<i class="material-icons">date_range</i> Last 24 Hours
									</div>
								</div>
							</div>
						</div>
					</div>
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
					<script>
					$(document).ready(function(){
					dataDailySalesChart = {
            labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
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

