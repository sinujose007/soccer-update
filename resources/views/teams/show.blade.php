@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left">	<h2> Show Team</h2>	</div>
		<div class="pull-right">
			<a class="btn btn-primary" href="{{ route('teams.index') }}"> Back</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group"><strong>Name:</strong>{{ $team->name }}	</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group"><strong>Logo:<img src="{{ $team->logoURI }}" style="width:100px;" />	</div>
	</div>
</div>
<table class="table table-bordered">
	<tr>
		<th>No</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Image</th>
	</tr>
	{{ $i=0; }}
	@foreach ($team->players as $player)
	<tr>
		<td>{{ ++$i }}</td>
		<td>{{ $player->firstName }}</td>
		<td>{{ $player->lastName }}</td>
		<td><img src="{{ $player->playerImageURI }}" style="width:20px;" /></td>
</tr>
@endforeach
</table>
@endsection