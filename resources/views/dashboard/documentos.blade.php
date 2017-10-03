@extends('layouts.master')
@section('content')
<div class="row">
	                    <div class="col-md-12">
	                        <div class="card">
	                            <div class="card-header" data-background-color="purple">
	                                <h4 class="title">Documentos para Download</h4>
	                                <p class="category">Abaxio escolha um documento para download</p>
	                            </div>
	                            <div class="card-content table-responsive">
	                                <table class="table">
	                                    <thead class="text-primary">
	                                    	<th>Nome</th>
	                                    	<th>Descrição</th>
											<th>Download</th>
	                                    </thead>
	                                    <tbody>
	                                        <tr>
	                                        	<td>Briefing Zoop</td>
	                                        	<td>Documento de Briefing do Ponto de Venda</td>
	                                        	<td><a href="{{url('admin/documentos',2)}}" class="btn btn-primary"><i class="fa fa-download"></i> DOWNLOAD</a></td>
	                                        </tr>
											<tr>
	                                        	<td>Cadastro de Informações Zoop</td>
	                                        	<td>Documento de Cadastro de Informações do Ponto de Venda</td>
	                                        	<td><a href="{{url('admin/documentos',3)}}" class="btn btn-primary"><i class="fa fa-download"></i> DOWNLOAD</a></td>
	                                        </tr>
											<tr>
	                                        	<td>Proposta do Cliente Zoop</td>
	                                        	<td>Documento de Proposta do Cliente</td>
	                                        	<td><a href="{{url('admin/documentos',4)}}" class="btn btn-primary"><i class="fa fa-download"></i> DOWNLOAD</a></td>
	                                        </tr>
	                                    </tbody>
	                                </table>

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