@extends('app')
@section('content')
<div class="row">
	<div class="col-md-8">
	<!-- Flash Messages -->
		@if (session('actualizada'))
			<div class="alert alert-success alert-dismissible">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                <h4><i class="icon fa fa-check"></i> {{ \Session::get('actualizada') }} </h4>
	              </div>
		@endif
		@if (session('error_migraciones'))
			<div class="alert alert-danger alert-dismissible">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                <h4><i class="icon fa fa-check"></i> {{ \Session::get('error_migraciones') }} </h4>
	              </div>
		@endif
		@if (session('success_migraciones'))
			<div class="alert alert-success alert-dismissible">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                <h4><i class="icon fa fa-check"></i> {{ \Session::get('success_migraciones') }} </h4>
	              </div>
		@endif
		
		@if ($errors->all())
			<div class="callout callout-danger">
				<h4>Errores </h4>
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
			</div>
			
		@endif
	<!-- Flash Messages -->

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nombre: {{ $institucion->full_name }}</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			{!! Form::open(array('route' => ['instituciones.update', $institucion->id], 'method' => 'PUT', 'class' => 'form', 'role' => 'form')) !!}
			<div class="box-body">
				<div class="form-group">
					{!! Form::label('Nombre institución') !!}
					{!! Form::text('full_name', $institucion->full_name, 
					array( 
					'class'=>'form-control', 
					'placeholder'=>'')) !!}
				</div>
				<div class="form-group">
					{!! Form::label('Url') !!}
					{!! Form::text('name', $institucion->name, 
					array( 
					'class'=>'form-control', 
					'placeholder'=>'')) !!}
				</div>
				<div class="form-group">
					{!! Form::label('Base Datos') !!}
					{!! Form::text('database', $institucion->database, 
					array( 
					'class'=>'form-control', 
					'placeholder'=>'')) !!}
				</div>
				<div class="form-group">
					{!! Form::label('Usuario Base Datos') !!}
					{!! Form::text('db_user', $institucion->db_user, 
					array( 
					'class'=>'form-control', 
					'placeholder'=>'')) !!}
				</div>
				<div class="form-group">
					{!! Form::label('Password Base Datos') !!}
					{!! Form::password('db_password', 
					array( 
					'class'=>'form-control', 
					'placeholder'=>'')) !!}
				</div>
				{!! Form::hidden('active', 0) !!}
				{!! Form::label('Institución Activa?') !!}
				{!! Form::checkbox('active', 1, $institucion->active) !!}

			</div>
			<!-- /.box-body -->
			<div class="box-footer">

				{!! Form::submit('Editar Institución', ['class' => 'btn btn-primary']) !!}

			</div>
			{!! Form::close() !!}
		</div>
	</div>

	@if ( !$institucion->is_created_migration_seed )
		<div class="col-md-4">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title">Acciones</h3>
				</div>
				<table class="table table-bordered text-center">
					<tbody>
						<tr>
							<td>
								{!! Form::open(array('route' => ['ejecutar_migraciones_seeds'], 'method' => 'POST', 'class' => 'form', 'role' => 'form')) !!}
								{!! Form::hidden('institucion_id', $institucion->id) !!}
								{!! Form::submit('Ejecutar Migración y Seeds', ['class' => 'btn btn-block btn-warning', 'id'=>'demo']) !!}

								{!! Form::close() !!}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	@endif



</div>
@endsection


@section('page-script')
<script type="text/javascript">

	// none, bounce, rotateplane, stretch, orbit, 
	// roundBounce, win8, win8_linear or ios
	var current_effect = 'bounce'; // 

    
    $('#demo').click(function(){
		run_waitMe(current_effect);
	});

	function run_waitMe(effect){
			$('body').waitMe({

				//none, rotateplane, stretch, orbit, roundBounce, win8, 
				//win8_linear, ios, facebook, rotation, timer, pulse, 
				//progressBar, bouncePulse or img
				effect: 'bounce',

				//place text under the effect (string).
				text: 'Ejecutando Migraciones y Seeds en la Base de Datos...',

				//background for container (string).
				bg: 'rgba(255,255,255,0.7)',

				//color for background animation and text (string).
				color: '#000',

				//change width for elem animation (string).
				sizeW: '',

				//change height for elem animation (string).
				sizeH: '',

				// url to image
				source: '',

				// callback
				onClose: function() {}

			});
		}

</script>
@stop