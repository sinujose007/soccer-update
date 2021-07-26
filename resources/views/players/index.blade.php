@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left"><h2>Players</h2>	</div>
		<div class="pull-right">
			@can('player-create')
				<a class="btn btn-success" href="{{ route('players.create') }}"> Create New Player</a>
			@endcan
		</div>
	</div>
</div>
@if ($message = Session::get('success'))
	<div class="alert alert-success">
		<p>{{ $message }}</p>
	</div>
@endif
<table class="table table-bordered">
	<tr>
		<th>No</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Team Name</th>
		<th>Image</th>
		<th width="280px">Action</th>
	</tr>
	@foreach ($players as $player)
	<tr>
		<td>{{ ++$i }}</td>
		<td>{{ $player->firstName }}</td>
		<td>{{ $player->lastName }}</td>
		<td>{{ $player->name }}</td>
		<td><img src="{{ $player->playerImageURI }}" style="width:20px;" /></td>
		<td>
			<form onsubmit="return validateDel();" action="{{ route('players.destroy',$player->id) }}" method="POST">
				<a class="btn btn-info" href="{{ route('players.show',$player->id) }}">Show</a>
				@can('player-edit')
				<a class="btn btn-primary" href="{{ route('players.edit',$player->id) }}">Edit</a>
				@endcan
				@csrf
				@method('DELETE')
				@can('player-delete')
					<button type="submit" class="btn btn-danger">Delete</button>
				@endcan
			</form>
		</td>
</tr>
@endforeach
</table>
{!! $players->links() !!}
@endsection

<script type="text/javascript">
function validateDel(){
	if(confirm('Are You Sure')){
		return true;
	}
	return false;
}
</script>