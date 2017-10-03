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
    								<a href="#pablo">
    									<img class="img" src="https://winklevosscapital.com/wp-content/uploads/2014/10/2014-09-16-Anoynmous-The-Rise-of-Personal-Networks.jpg" />
    								</a>
    							</div>

    							<div class="content">
    								<h6 class="category text-gray">{{$usuario->roles->first()->name}}</h6>
    								<h4 class="card-title">{{$usuario->name}}</h4>
    								<p class="card-content">
										Cadastrado em: {{formata_data($usuario->created_at)}}
    								</p>
    								<a href="#pablo" class="btn btn-primary btn-round">Mudar Foto</a>
    							</div>
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