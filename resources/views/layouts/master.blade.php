<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		
		<title>{{ config('app.name', 'Laravel') }}</title>
		
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<title>{{ config('app.name', 'Laravel') }}</title>
		
		<!-- Bootstrap core CSS     -->
		<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
		<!--  Material Dashboard CSS    -->
		<link href="{{ asset('css/material-dashboard.css') }}" rel="stylesheet">
		<!--     Fonts and icons     -->
		<link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>
		<!--     JS and Scripts     -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="{{ asset('js/image-preview.js') }}"></script>
	</head>
	<body>
		<div class="wrapper">
			<div class="sidebar" data-color="purple" data-image="{{ asset('img/sidebar-3.jpg')}}">
				
				<!--
					Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"
					
					Tip 2: you can also add an image using data-image tag
				-->
				
				<div class="logo">
					<a href="#" class="simple-text">
						{{ config('app.name', 'Laravel') }}
					</a>
				</div>
				
				<div class="sidebar-wrapper">
					<ul class="nav">
						<li class="{{set_active('admin/home')}}">
							<a href="{{ route('home') }}">
								<i class="material-icons">dashboard</i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="{{set_active('admin/usuarios')}}">
							<a href="{{ route('usuarios') }}">
								<i class="fa fa-users"></i>
								<p>Usuarios</p>
							</a>
						</li>
						<li class="{{set_active('admin/pontosvenda')}}">
							<a href="{{ route('pontosvenda') }}">
								<i class="material-icons">store</i>
								<p>Pontos de Venda</p>
							</a>
						</li>
						<li class="{{set_active('admin/produtos')}}">
							<a href="{{ route('produtos') }}">
								<i class="fa fa-tags"></i>
								<p>Produtos</p>
							</a>
						</li>
						<li class="{{set_active('admin/pedidos')}}">
							<a href="{{ route('pedidos') }}">
								<i class="fa fa-file-text-o"></i>
								<p>Pedidos</p>
							</a>
						</li>
						<li class="{{set_active('admin/estoque')}}">
							<a href="{{ route('estoque') }}">
								<i class="fa fa-cube"></i>
								<p>Estoque</p>
							</a>
						</li>
						<li class="{{set_active('admin/documentos/1')}}">
							<a href="{{ url('admin/documentos' ,'1') }}">
								<i class="fa fa-download"></i>
								<p>Documentos</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="main-panel">
				<nav class="navbar navbar-transparent navbar-absolute">
					<div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="#">Table List</a>
						</div>
						<div class="collapse navbar-collapse">
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
									<div id="data"></div>
										<p class="hidden-lg hidden-md">Dashboard</p>
									</a>
								</li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<i class="material-icons">notifications</i>
										<span class="notification">1</span>
										<p class="hidden-lg hidden-md">Notifications</p>
									</a>
									<ul class="dropdown-menu">
										<li><a href="#">Mike John responded to your email</a></li>
									</ul>
								</li>
								<li class="dropdown">
									<a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
										
										<i class="material-icons"> person</i>
										<p class="hidden-lg hidden-md">Profile</p>
										{{ Auth::user()->name }}	
										
									</a>
									<ul class="dropdown-menu">
										<li><a href="{{route('perfil')}}">Perfil</a></li>
										<li><a href="{{ route('logout') }}"
											onclick="event.preventDefault();
										document.getElementById('logout-form').submit();">Sair</a></li>
										
										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											{{ csrf_field() }}
										</form>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</nav>
				
				<div class="content">
					<div class="container-fluid">
						<div class="row">
							@yield('content')
						</div>
					</div>
				</div>
				<footer class="footer">
					<div class="container-fluid">
						<nav class="pull-left">
							<ul>
								<li>
									<a href="#">
										Home
									</a>
								</li>
								
							</ul>
						</nav>
						<p class="copyright pull-right">
							&copy; <script>document.write(new Date().getFullYear())</script> <a href="http://www.zoopbr.com.br">ZoopBR</a>| Dispositivo espremdor de tubos e pastas
						</p>
					</div>
				</footer>
			</div>
		</div>
	</body>
	
	<script>
		$( document ).ready(function() {
			$('.modal').appendTo("body");
		});
	</script>
	
	@if (session('status'))
	<script>
		$(document).ready(function(){
			$.notify({
				icon: "check_circle",
				message: "{{ session('status') }}"
				
				},{
				type: 'success',
				timer: 4000,
				placement: {
					from: 'top',
					align: 'right'
				}
			});
		});
		
	</script>
	@elseif (session('error'))
	<script>
		$(document).ready(function(){
			$.notify({
				icon: "report_problem",
				message: "{{ session('error') }}"
				
				},{
				type: 'danger',
				timer: 4000,
				placement: {
					from: 'top',
					align: 'right'
				}
			});
		});
		
	</script>
	@endif
	<!--   Core JS Files   -->
	
	<script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('js/material.min.js') }}" type="text/javascript"></script>
	
	<!--  Charts Plugin -->
	<script src="{{ asset('js/chartist.min.js') }}"></script>
	
	<!--  Notifications Plugin    -->
	<script src="{{ asset('js/bootstrap-notify.js') }}"></script>
	
	<!--  Google Maps Plugin    -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
	
	<!-- Material Dashboard javascript methods -->
	<script src="{{ asset('js/material-dashboard.js') }}"></script>
	
	<!-- Demo -->
	
	
		<script>
function get_fb_complete(){
    
    var feedback = $.ajax({
        type: "GET",
        url: "{{url('tempo_agora')}}"
    }).done(function(data){
	$('#data').html('<i class="material-icons">date_range</i>'+data);
        setTimeout(function(){get_fb_complete();}, 1000);
    }).responseText;

    //$('#data').html('complete feedback');
}

$(function(){
    get_fb_complete();
});
</script>

	@yield('post-script')
	
</html>
