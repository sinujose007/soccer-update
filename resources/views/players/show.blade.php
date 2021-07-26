@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left">	<h2> Show Player</h2>	</div>
		<div class="pull-right">
			<a class="btn btn-primary" href="{{ route('players.index') }}"> Back</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group"><strong>First Name:</strong>{{ $player->firstName }}</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group"><strong>Last Name:</strong>{{ $player->lastName }}</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group"><strong>Team Name:</strong>{{ $player->team->name }}</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group"><strong>Profile Image:<img src="{{ $player->playerImageURI }}" style="width:100px;" />	</div>
	</div>
</div>
@endsection