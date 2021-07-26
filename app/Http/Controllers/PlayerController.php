<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repository\PlayerRepositoryInterface; 
use App\Repository\TeamRepositoryInterface;
use App\Http\Traits\UploadTrait;

class PlayerController extends Controller
{ 
	use UploadTrait;
	private $playerRepository;
	private $teamRepository;
	
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	function __construct(PlayerRepositoryInterface $playerRepository, TeamRepositoryInterface $teamRepository)
	{
		$this->playerRepository = $playerRepository;
		$this->teamRepository   = $teamRepository;
		$this->middleware('permission:player-create', ['only' => ['create','store']]);
		$this->middleware('permission:player-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:player-delete', ['only' => ['destroy']]);
	}
	
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{		
		$players = $this->playerRepository->getAllPlayers();
		return view('players.index',compact('players'))->with('i', (request()->input('page', 1) - 1) * 5);
	}
	
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{	
		$data = $this->teamRepository->getAllTeams();
		return view('players.create',compact('data'));
	}
	
	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
		// Form validation
        request()->validate([
			'firstName' => 'required',
			'lastName' => 'required',
			'team_id' => 'required',
			'playerImageURI' => 'required|mimes:jpg,png|max:2048',
		]);        
		$fileName = null;
        // Check if a profile image has been uploaded
        if ($request->has('playerImageURI')) {
			$fileName = $this->storeImage($request);
		}
		//insert in to database table
		if( $fileName !='' ) {			
			$player = ['firstName'=>$request->firstName,'lastName'=>$request->lastName,'team_id'=>$request->team_id,'playerImageURI'=>$fileName];
			$create = $this->playerRepository->createPlayer($player);
			if($create !='' ){
				return redirect()->route('players.index')->with('success','Player created successfully.');
			}else{		
				return back()->withErrors(['Player creation failed, Please try again']);
			}
		}else{		
			return back()->withErrors(['Image Upload Failed']);
		}
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Player  $player
	* @return \Illuminate\Http\Response
	*/
	public function show(Player $player)
	{		
		return view('players.show',compact('player'));
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Player  $player
	* @return \Illuminate\Http\Response
	*/
	public function edit(Player $player)
	{
		$player['data'] = $this->playerRepository->getAllPlayers();
		return view('players.edit',compact('player'));
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Player  $player
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Player $player)
	{		
		$save = [];
		if ($request->hasFile('playerImageURI')) {			
			request()->validate(['firstName' => 'required','lastName' => 'required','team_id' => 'required','playerImageURI' => 'required|mimes:jpg,png|max:2048',]);
			//delete previous image			
			$this->deleteOne(public_path('uploads/'), $player->playerImageURI);
			//upload new image
			$fileName = $this->storeImage($request);
			$save['playerImageURI'] = $fileName;
		}else{
			request()->validate(['firstName' => 'required','lastName' => 'required','team_id' => 'required']);
		}
		$save['firstName'] = $request->firstName;
		$save['lastName'] = $request->lastName;
		$save['team_id'] = $request->team_id;
		if($this->playerRepository->updatePlayer($save, $player->id)) {
			return redirect()->route('players.index')->with('success','Player updated successfully.');
		}else{		
			return back()->withErrors(['Updates failed, Please try again']);
		}
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Player  $player
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Player $player)
	{
		$player->delete();
		return redirect()->route('players.index')->with('success','Player deleted successfully');
	}
	
	/**
	* functions to store image, calling upload functions in the trait
	*/
	private function storeImage($request):string {
		// Get image file
        $image = $request->file('playerImageURI');
        // Make a image name based on user name and current timestamp
        $name = Str::slug($request->input('firstName')).'_'.time();
        // Define folder path
        $folder = public_path('uploads');
        // Make a file path where image will be stored [ folder path + file name + file extension]
        $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
        // Upload image
        $result = $this->uploadOne($image, $folder, 'public', $name);
		$fileURL = url('/uploads').'/'.$result;
		return $fileURL;
	}
}