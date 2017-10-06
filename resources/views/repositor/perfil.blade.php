@extends('layouts.repositor')

@section('content')



<div class="row">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Seu Perfil</h4>
				<p class="category">Edite seu perfil</p>
			</div>
			<div class="card-content">
				<form action="{{url('user_editar_perfil')}}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" value="{{$usuario->id}}" name="idUser">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group label-floating">
								<label class="control-label">Nome</label>
								<input type="text" value="{{$usuario->name}}" name="nome" class="form-control" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group label-floating">
								<label class="control-label">Email</label>
								<input type="email" value="{{$usuario->email}}" name="email" class="form-control" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group label-floating">
								<label class="control-label">Senha Atual</label>
								<input type="password" name="confirmpassword" class="form-control" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group label-floating">
								<label class="control-label">Senha Nova</label>
								<input type="password" name="password" class="form-control" >
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary pull-right">Salvar</button>
					<div class="clearfix"></div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="card card-profile">
			<div class="card-avatar">
				@if($usuario->imagem != null)
				<a href="{{asset($usuario->imagem)}}" data-toggle="lightbox" class="visualizar">
					<img src="{{asset($usuario->imagem)}}" class="img preview img-fluid" style="height:auto;width:100%!important;">
					@else
					<a href="http://www.mvsevm.it/ContentsFiles/anonimo.jpg" data-toggle="lightbox" class="visualizar">
						<img src="http://www.mvsevm.it/ContentsFiles/anonimo.jpg" class="img preview img-fluid" style="height:auto;width:100%!important;">
						@endif
						<!-- <img class="" src="https://winklevosscapital.com/wp-content/uploads/2014/10/2014-09-16-Anoynmous-The-Rise-of-Personal-Networks.jpg" />-->
					</a>
				</div>
				
				<div class="content">
					<h6 class="category text-gray">{{$usuario->roles->first()->name}}</h6>
					<h4 class="card-title">{{$usuario->name}}</h4>
					<p class="card-content">
						Cadastrado em: {{formata_data($usuario->created_at)}}
					</p>
					<form enctype="multipart/form-data" action="{{url('trocar_imagem_perfil')}}" method="POST">
						{{ csrf_field() }}
						<input type="hidden" name="idUser" value="{{$usuario->id}}">
						<div class="form-group is-empty is-fileinput">
							<input type="file" name="imagem" class="image">								
							<div class="form-group">
								<button type="button" class="btn btn-primary btn-round" id="trocar">
									<i class="fa fa-file-image-o"></i> Trocar Foto
								</button>
								
					
							</div>
						</div>
						<div class="col-md-12">
						<button type="submit" class="btn btn-info btn-round hidden" id="salvar">
							<i class="fa fa-file-image-o"></i> Salvar Foto
						</button>
					<a href="#" class="cancelar hidden">Cancelar</a>		
					</div>
					</form>
					
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label"></label>
							
						</div>
					</div>
				</div>
				
				
			</div>
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
				$('.visualizar').attr('href', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
		$("#salvar").removeClass( "hidden" );
		$(".cancelar").removeClass( "hidden" );
		$("#trocar").addClass( "hidden" );
	}
	$(".image").change(function(){
		readURL(this);
	});
	
	$(".cancelar").on('click', function(event){
		$('.preview').attr('src', '{{asset($usuario->imagem)}}');
		$('.visualizar').attr('href', '{{asset($usuario->imagem)}}');
		
		$(".cancelar").addClass( "hidden" );
		$("#salvar").addClass( "hidden" );
		
		$("#trocar").removeClass( "hidden" );
		console.log('teste');
	});
	
</script>
@endsection						