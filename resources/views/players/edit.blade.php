@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left"><h2>Edit Player</h2></div>
		<div class="pull-right">
			<a class="btn btn-primary" href="{{ route('players.index') }}"> Back</a>
		</div>
	</div>
</div>
@if ($errors->any())
	<div class="alert alert-danger"><strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<form action="{{ route('players.update',$player->id) }}" method="POST" enctype="multipart/form-data">
	@csrf
	@method('PUT')
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>First Name:</strong>
				<input value="{{ $player->firstName }}" required  type="text" name="firstName" class="form-control" placeholder="Name">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Last Name:</strong>
				<input value="{{ $player->lastName }}"  required  type="text" name="lastName" class="form-control" placeholder="Name">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Team Name::</strong>
				<select name="team_id" required class="required form-control">
					<option value="">Select Team</option>
					@if (!empty($player->data)) 
						@foreach ($player->data as $team)
							<option <?php if($team->id == $player->team_id){ ?> selected <?php } ?> value="{{ $team->id}}">{{ $team->name}}</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Player Image: [ jpg,png ]</strong>
				<input id="playerImageURI" name="playerImageURI" class='form-control'  size='20' type='file'>
				<img src="{{ $player->playerImageURI }}" style="width:20px" />
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
</form>

@endsection