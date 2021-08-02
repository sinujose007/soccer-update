<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repository\PlayerRepositoryInterface; 
use App\Repository\TeamRepositoryInterface;
use App\Http\Traits\UploadTrait;
use Illuminate\Support\Facades\Validator;

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
		return $players;		
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
		$validator = Validator::make($request->all(),[       
			'firstName' => 'required',
			'lastName' => 'required',
			'team_id' => 'required',
			'playerImageURI' => 'required|mimes:jpg,png|max:2048',
        ]); 
		if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 422);                        
        }      
		$fileName = null;
        // Check if a profile image has been uploaded
		if ($request->has('playerImageURI')) {
			try{
				$fileName = $this->storeImage($request);
			}catch(\Exception $e){
				return response()->json(['error'=>'Internal Server Error'], 500);    
			}
		}
		$player = ['firstName'=>$request->firstName,'lastName'=>$request->lastName,'team_id'=>$request->team_id,'playerImageURI'=>$fileName];
		//insert in to database table
		try{
			$this->playerRepository->createPlayer($player);
			return response()->json(['success'=>'Player Created Successfully'], 201);   
		}catch(\Exception $e){
			return response()->json(['error'=>'Internal Server Error'], 500);    
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
		try{
			$player = $this->playerRepository->getSinglePlayer($player);
		}catch(ModelNotFoundException $e) {			
			return response()->json(['error'=>'Resource Not Found'], 422);   
		}
		return $player;
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
			// Form validation
			$validator = Validator::make($request->all(),[       
				'firstName' => 'required',
				'lastName' => 'required',
				'team_id' => 'required',
				'playerImageURI' => 'required|mimes:jpg,png|max:2048',
			]); 
			if ($validator->fails()) {          
				return response()->json(['error'=>$validator->errors()], 422);                        
			}      
			$this->deleteOne(public_path('uploads/'), $player->playerImageURI);
			//upload new image
			try{
				$fileName = $this->storeImage($request);
			}catch(\Exception $e){
				return response()->json(['error'=>'Internal Server Error'], 500);    
			}
			$save['playerImageURI'] = $fileName;
		}else{
			$validator = Validator::make($request->all(),['firstName' => 'required','lastName' => 'required','team_id' => 'required',]); 
			if ($validator->fails()) {          
				return response()->json(['error'=>$validator->errors()], 422);                        
			} 
		}
		$save['firstName'] = $request->firstName;
		$save['lastName'] = $request->lastName;
		$save['team_id'] = $request->team_id;
		try{
			$this->playerRepository->updatePlayer($save, $player->id);
			return response()->json(['success'=>'Player Updated Successfully'], 201); 
		}catch(\Exception $e){
			return response()->json(['error'=>'Internal Server Error'], 500);    
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
		try{
			$this->playerRepository->deletePlayer($player->id);
			return response()->json(['success'=>'Player UDeleted Successfully'], 201); 
		}catch(\Exception $e){
			return response()->json(['error'=>'Internal Server Error'], 500);    
		}
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