@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left"><h2>Add New Player</h2></div>
		<div class="pull-right">
			<a class="btn btn-primary" href="{{ route('players.index') }}"> Back</a>
		</div>
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
<form action="{{ route('players.store') }}" method="POST"  enctype="multipart/form-data">
	@csrf
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>First Name:</strong>
				<input required  type="text" name="firstName" class="form-control" placeholder="Name">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Last Name:</strong>
				<input required  type="text" name="lastName" class="form-control" placeholder="Name">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Team Name::</strong>
				<select name="team_id" required class="required form-control">
					<option value="">Select Team</option>
					@if (!empty($data)) 
						@foreach ($data as $team)
							<option value="{{ $team->id}}">{{ $team->name}}</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Player Image: [ jpg,png ]</strong>
				<input id="playerImageURI" name="playerImageURI" required class='required form-control'  size='20' type='file'>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
</form>

@endsection