<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repository\TeamRepositoryInterface;
use App\Http\Traits\UploadTrait;
use Illuminate\Support\Facades\Validator;
//use Request;
use Illuminate\Support\Facades\Input;

class TeamController extends Controller
{ 
	use UploadTrait;
	private $teamRepository;
	
	/**
	* Resource Constructor.
	*
	* @return \Illuminate\Http\Response
	*/
	function __construct(TeamRepositoryInterface $teamRepository)
	{
		$this->teamRepository = $teamRepository;
		$this->middleware('permission:team-create', ['only' => ['create','store']]);
		$this->middleware('permission:team-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:team-delete', ['only' => ['destroy']]);
	}
	
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request)
	{		
		if($request->has('section'))
		{			
			$teams = $this->teamRepository->getAllTeams();
		}else{
			$teams = $this->teamRepository->getAllTeamsPaging();			
		}
		return $teams;		
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
			'name' => 'required',
            'logoURI' => 'required|mimes:jpg,png|max:2048',
        ]);   
 
		if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 422);                        
        } 
		$fileName = null;
        // Check if a profile image has been uploaded
        if ($request->has('logoURI')) {
			try{
				$fileName = $this->storeImage($request);
			}catch(\Exception $e){
				return response()->json(['error'=>'Internal Server Error'], 500);    
			}
		}
		$team = ['name'=>$request->name,'logoURI'=>$fileName];
		try{
			$this->teamRepository->createTeam($team);
			return response()->json(['success'=>'Team Created Successfully'], 201);   
		}catch(\Exception $e){
			return response()->json(['error'=>'Internal Server Error'], 500);    
		}		
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Team  $team
	* @return \Illuminate\Http\Response
	*/
	public function show(Team $team)
	{
		try{
			$team = $this->teamRepository->getSingleTeam($team);
		}catch(ModelNotFoundException $e) {			
			return response()->json(['error'=>'Resource Not Found'], 422);   
		}
		return $team;
		
	}	
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Team  $team
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Team $team)
	{	
		$save = [];
		if ($request->hasFile('logoURI')) {			
			$validator = Validator::make($request->all(),[       
				'name' => 'required',
				'logoURI' => 'required|mimes:jpg,png|max:2048',
			]);
			if ($validator->fails()) {          
				return response()->json(['error'=>$validator->errors()], 422);                        
			} 
			//delete previous image			
			$this->deleteOne(public_path('uploads/'), $team->logoURI);
			//upload new image
			try{
				$fileName = $this->storeImage($request);
			}catch(\Exception $e){
				return response()->json(['error'=>'Internal Server Error'], 500);    
			}
			$save['logoURI'] = $fileName;
		}else{
			$validator = Validator::make($request->all(),[ 'name' => 'required',]);
			if ($validator->fails()) {          
				return response()->json(['error'=>$validator->errors()], 422);                        
			} 
		}
		$save['name'] = $request->name;
		try{
			$this->teamRepository->updateTeam($save, $team->id);
			return response()->json(['success'=>'Team Updated Successfully'], 201); 
		}catch(\Exception $e){
			return response()->json(['error'=>'Internal Server Error'], 500);    
		}
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Team  $team
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Team $team)
	{
		try{
			$this->teamRepository->deleteTeam($team->id);
			return response()->json(['success'=>'Team UDeleted Successfully'], 201); 
		}catch(\Exception $e){
			return response()->json(['error'=>'Internal Server Error'], 500);    
		}	
	}
	
	/**
	* functions to store image, calling upload functions in the trait
	*/
	private function storeImage($request):string {
		// Get image file
        $image = $request->file('logoURI');
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