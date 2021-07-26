@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left">	<h2>Edit Team</h2>	</div>
		<div class="pull-right"><a class="btn btn-primary" href="{{ route('teams.index') }}"> Back</a></div>
	</div>
</div>
@if ($errors->any())
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<form action="{{ route('teams.update',$team->id) }}" method="POST"  enctype="multipart/form-data">
	@csrf
	@method('PUT')
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Name:</strong>
				<input type="text" name="name" value="{{ $team->name }}" class="form-control" placeholder="Name">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Logo: [ jpg,png ]</strong>
				<input id="logoURI" name="logoURI" class='form-control'  size='20' type='file' />
				<img src="{{ $team->logoURI }}" style="width:20px" />
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
</form>

@endsection