@extends('app')
@section('content')
<div class="row">
	<div class="col-md-8">
	@if ($errors->all())
		<div class="callout callout-danger">
			<h4>Errores </h4>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
		</div>
	@endif
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nueva Institución</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			{!! Form::open(array('route' => 'instituciones.store', 'class' => 'form', 'role' => 'form')) !!}
			<div class="box-body">
				<div class="form-group">
					{!! Form::label('Nombre institución') !!}
					{!! Form::text('full_name', null, 
					array( 
					'class'=>'form-control', 
					'placeholder'=>'')) !!}
				</div>
				<div class="form-group">
					{!! Form::label('Url') !!}
					{!! Form::text('name', null, 
					array( 
					'class'=>'form-control', 
					'placeholder'=>'')) !!}
				</div>
				<div class="form-group">
					{!! Form::label('Base Datos') !!}
					{!! Form::text('database', null, 
					array( 
					'class'=>'form-control', 
					'placeholder'=>'')) !!}
				</div>
				<div class="form-group">
					{!! Form::label('Usuario Base Datos') !!}
					{!! Form::text('db_user', null, 
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
				{!! Form::label('Institución Activa?') !!}
				{!! Form::checkbox('active', 1, true) !!}

			</div>
			<!-- /.box-body -->
			<div class="box-footer">

				{!! Form::submit('Crear Institución', ['class' => 'btn btn-primary']) !!}

			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection