<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Route;

class ApiController extends Controller
{ 
	/**
	* Resource Constructor.
	*
	* @return \Illuminate\Http\Response
	*/
	function __construct()
	{
		$this->middleware('permission:team-create', ['only' => ['createTeam','storeTeam']]);
		$this->middleware('permission:team-edit', ['only' => ['editTeam','updateTeam']]);
		$this->middleware('permission:team-delete', ['only' => ['destroyTeam']]);
		$this->middleware('permission:player-create', ['only' => ['createPlayer','storePlayer']]);
		$this->middleware('permission:player-edit', ['only' => ['editPlayer','updatePlayer']]);
		$this->middleware('permission:player-delete', ['only' => ['destroyPlayer']]);
	}
	
	
	/**
	* Display a listing of the Teams by makin a REST API call.
	*
	* @return \Illuminate\Http\Response
	*/
	public function getTeams(){
		$pages = 1;
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/teams?page='.$pages,'GET');
			$response = Route::dispatch($request);//dd($response);
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 200){
				$data = json_decode($response->getContent());			
				return back()->withErrors($data->error);
			}else{
				$results = json_decode($response->getContent());
				//print_r($results); exit;
				$teams = $results->data;
				$links = $results->links;
				return view('teams.index', compact('teams','links'))->with('i', (request()->input('page', 1) - 1) * 5);	
			}			
		}catch (\Exception $e){
			report($e);
			abort(404);
		}			
	}
	
	/**
	* Display details of a team using REST API call with id
	*
	* @return \Illuminate\Http\Response
	*/
	public function getTeamDetails($id){
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/teams/'.$id,'GET');
			$response = Route::dispatch($request);
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 200){
				$data= json_decode($response->getContent());			
				return back()->withErrors($data->error);
			}else{
				$team = json_decode($response->getContent());
				return view('teams.show',compact('team'))->with('i',0);			
			}
		}catch (\Exception $e){
			report($e);
			abort(404);
		}			
		
	}
	
	/**
	* Display a listing of the Players by makin a REST API call.
	*
	* @return \Illuminate\Http\Response
	*/
	public function getPlayers(){			
		$pages = 1;
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/players?page='.$pages,'GET');
			$response = Route::dispatch($request);
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 200){
				$data= json_decode($response->getContent());			
				return back()->withErrors($data->error);
			}else{
				$results = json_decode($response->getContent());
				$players = $results->data;
				$links = $results->links;
				return view('players.index', compact('players','links'))->with('i', (request()->input('page', 1) - 1) * 5);	
			}				
		}catch (\Exception $e){
			report($e);
			abort(404);
		}		
	}
	
	/**
	* Display details of a team using REST API call with id
	*
	* @return \Illuminate\Http\Response
	*/
	public function getPlayerDetails($id){		
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/players/'.$id,'GET');
			$response = Route::dispatch($request);
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 200){
				$data= json_decode($response->getContent());			
				return back()->withErrors($data->error);
			}else{
				$player = json_decode($response->getContent());	
				return view('players.show',compact('player'))->with('i',0);					
			}
		}catch (\Exception $e){
			report($e);
			abort(404);
		}			
			
	}
	
	/**
	* Show the form for creating a new Team.
	*
	* @return \Illuminate\Http\Response
	*/
	public function createTeam()
	{
		return view('teams.create');
	}
	
	/**
	* Store a newly created resource using REST API.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function storeTeam(Request $request)
	{
		// Form validation, 
        request()->validate([
			'name' => 'required',
			'logoURI' => 'required|mimes:jpg,png|max:2048',
		]); 
		
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/teams','POST', request()->post(),request()->file());
			$response = Route::dispatch($request);//dd($response);
			//check the status code 201 or 422
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 201){
				$data = json_decode($response->getContent());
				return back()->withErrors($data->error);
			}else{
				return redirect('/apiTeamList')->with('success','Team created successfully.');
			}
		}catch (\Exception $e){
			report($e);
			return back()->withErrors(['Team creation failed, Please try again']);
		}	
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Team  $team
	* @return \Illuminate\Http\Response
	*/
	public function editTeam($id)
	{
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/teams/'.$id,'GET');
			$response = Route::dispatch($request);
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 200){
				$results = json_decode($response->getContent());			
				return back()->withErrors($data->error);
			}else{
				$team = json_decode($response->getContent());
				return view('teams.edit',compact('team'));			
			}
		}catch (\Exception $e){
			report($e);
			abort(404);
		}
		
	}
	
	/**
	* Update resource using REST API.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function updateTeam(Request $request)
	{
		// Form validation, 
        request()->validate([
			'name' => 'required',
		]); 
		$req = request()->post();
		$id = $req['id']; 
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/teams/'.$id,'PUT', request()->post(),request()->file());
			$response = Route::dispatch($request);
			//check the status code 201 or 422 //dd($response);			
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 201){
				$data = json_decode($response->getContent());
				return back()->withErrors($data->error);
			}else{
				return redirect('/apiTeamList')->with('success','Team updated successfully.');
			}
		}catch (\Exception $e){
			report($e);
			return back()->withErrors(['Team update failed, Please try again']);
		}	
	}
	
	/**
	* Remove the specified resource using rest api
	*
	* @param  \App\Team  $team
	* @return \Illuminate\Http\Response
	*/
	public function destroyTeam($id)
	{
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/teams/'.$id,'DELETE', request()->post());
			$response = Route::dispatch($request);
			//check the status code 201 or 422 	//dd($response);			
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 201){
				$data = json_decode($response->getContent());
				return back()->withErrors($data->error);
			}else{
				return redirect('/apiTeamList')->with('success','Team deleted successfully.');
			}
		}catch (\Exception $e){
			report($e);
			return back()->withErrors(['Team delete failed, Please try again']);
		}
	}	
	
	/**
	* Show the form for creating a new Player.
	*
	* @return \Illuminate\Http\Response
	*/
	public function createPlayer()
	{
		$pages = 1;
		try{			
			$response = $this->fetchAllTeams();
			$data = json_decode($response->getContent());
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 200){
				return back()->withErrors($data->error);
			}else{
				return view('players.create', compact('data'));	
			}			
		}catch (\Exception $e){
			report($e);
			abort(404);
		}		
	}
	
	/**
	* Store a newly created resource using REST API.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function storePlayer(Request $request)
	{
		// Form validation, 
        request()->validate([
			'firstName' => 'required',
			'lastName' => 'required',
			'team_id' => 'required',
			'playerImageURI' => 'required|mimes:jpg,png|max:2048',
		]); 		
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/players','POST', request()->post(),request()->file());
			$response = Route::dispatch($request);//dd($response);
			//check the status code 201 or 422
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 201){
				$data = json_decode($response->getContent());
				return back()->withErrors($data->error);
			}else{
				return redirect('/apiPlayerList')->with('success','Player created successfully.');
			}
		}catch (\Exception $e){
			report($e);
			return back()->withErrors(['Player creation failed, Please try again']);
		}	
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Player  $player->id
	* @return \Illuminate\Http\Response
	*/
	public function editPlayer($id)
	{
		try{
			$request =  Request::create('/api/players/'.$id,'GET');
			$res = Route::dispatch($request);
			$statuscode = json_decode($res->getStatusCode());			
			if($statuscode != 200){
				$data = json_decode($res->getContent());			
				return back()->withErrors($data->error);
			}else{
				$player = json_decode($res->getContent());
				$response = $this->fetchAllTeams();
				$player->data = json_decode($response->getContent());
				return view('players.edit',compact('player'));			
			}
		}catch (\Exception $e){
			report($e);
			abort(404);
		}
		
	}
	
	/**
	* Update resource using REST API.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function updatePlayer(Request $request)
	{
		// Form validation, 
        request()->validate([
			'firstName' => 'required',
			'lastName' => 'required',
			'team_id' => 'required',
		]); 
		$req = request()->post();
		$id = $req['id']; 
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/players/'.$id,'PUT', request()->post(),request()->file());
			$response = Route::dispatch($request);
			//check the status code 201 or 422 //dd($response);			
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 201){
				$data = json_decode($response->getContent());
				return back()->withErrors($data->error);
			}else{
				return redirect('/apiPlayerList')->with('success','Player updated successfully.');
			}
		}catch (\Exception $e){
			report($e);
			return back()->withErrors(['Player update failed, Please try again']);
		}	
	}
	
	/**
	* Remove the specified resource using rest api
	*
	* @param  \App\Player  $player->id
	* @return \Illuminate\Http\Response
	*/
	public function destroyPlayer($id)
	{
		try{
			Request::instance()->headers->set('accept', 'application/json');
			$request =  Request::create('/api/players/'.$id,'DELETE', request()->post());
			$response = Route::dispatch($request);
			//check the status code 201 or 422 	//dd($response);			
			$statuscode = json_decode($response->getStatusCode());
			if($statuscode != 201){
				$data = json_decode($response->getContent());
				return back()->withErrors($data->error);
			}else{
				return redirect('/apiPlayerList')->with('success','Player deleted successfully.');
			}
		}catch (\Exception $e){
			report($e);
			return back()->withErrors(['Player delete failed, Please try again']);
		}
	}
	/**
	* private functions to fetch all teams through REST
	*
	*/
	private function fetchAllTeams(){		
		// Store the original input of the request
		$originalInput = Request::input();
		// Create your request to your API
		$request = Request::create('/api/teams?section=all', 'GET');
		// Replace the input with your request instance input
		Request::replace($request->input());
		// Dispatch your request instance with the router
		$response = Route::dispatch($request);
		Request::replace($originalInput);
		return $response;
	}
	
}