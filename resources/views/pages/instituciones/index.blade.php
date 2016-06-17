@extends('app')
@section('content')
<div class="row">
	<div class="col-md-3">
	<a href="{{ route('instituciones.create') }}">
			<button type="button" class="btn btn-block btn-success"><span class="fa fa-plus"> Nueva Institución</span></button>
		</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Instituciones</h3>
				<!-- /.box-header -->
				<div class="box-body no-padding">
					<table class="table">
						<tr>
							<th style="width: 10px">Id</th>
							<th>Nombre</th>
							<th>Url</th>
							<th>Activa</th>
							<th>Base Datos</th>
							<th>Migracion Seed</th>
							<th style="width: 10px"></th>
						</tr>
						@foreach ($instituciones as $institucion)
						<tr>
							<td>{{ $institucion->id }}</td>
							<td>{{ $institucion->full_name }}</td>
							<td>{{ $institucion->name }}</td>
							<td > 
								@if ($institucion->active)
								<i class="fa fa-check" style="color: #00a65a;"></i>
								@else
								<i class="fa fa-times" style="color: #f56954;"></i>
								@endif
							</td>
							<td > 
								@if ($institucion->is_created_database)
								<i class="fa fa-check" style="color: #00a65a;"></i>
								@else
								<i class="fa fa-times" style="color: #f56954;"></i>
								@endif
							</td>
							<td > 
								@if ($institucion->is_created_migration_seed)
								<i class="fa fa-check" style="color: #00a65a;"></i>
								@else
								<i class="fa fa-times" style="color: #f56954;"></i>
								@endif
							</td>
							<td > 
								<a href="{{ route('instituciones.edit', $institucion->id) }}">
									<i class="fa fa-pencil"></i> 
								</a>
							</tr>
							@endforeach
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix">
						{!! $instituciones->render() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
	@endsection
