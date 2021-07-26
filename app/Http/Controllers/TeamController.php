<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repository\TeamRepositoryInterface;
use App\Http\Traits\UploadTrait;

class TeamController extends Controller
{ 
	use UploadTrait;
	private $teamRepository;
	
	/**
	* Display a listing of the resource.
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
	public function index()
	{
		
		$teams = $this->teamRepository->getAllTeams();
		return view('teams.index',compact('teams'))->with('i', (request()->input('page', 1) - 1) * 5);
	}
	
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		return view('teams.create');
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
			'name' => 'required',
			'logoURI' => 'required|mimes:jpg,png|max:2048',
		]);        
		$fileName = null;
        // Check if a profile image has been uploaded
        if ($request->has('logoURI')) {
			$fileName = $this->storeImage($request);
		}
		//insert in to database table
		if( $fileName !='' ) {			
			$team = ['name'=>$request->name,'logoURI'=>$fileName];
			$create = $this->teamRepository->createTeam($team);
			if($create !='' ){
				return redirect()->route('teams.index')->with('success','Team created successfully.');
			}else{		
				return back()->withErrors(['Team creation failed, Please try again']);
			}
		}else{		
			return back()->withErrors(['Image Upload Failed']);
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
		return view('teams.show',compact('team'));
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Team  $team
	* @return \Illuminate\Http\Response
	*/
	public function edit(Team $team)
	{
		return view('teams.edit',compact('team'));
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
			request()->validate(['name' => 'required','logoURI' => 'required|mimes:jpg,png|max:2048',]);
			//delete previous image			
			$this->deleteOne(public_path('uploads/'), $team->logoURI);
			//upload new image
			$fileName = $this->storeImage($request);
			$save['logoURI'] = $fileName;
		}else{
			request()->validate(['name' => 'required']);
		}
		$save['name'] = $request->name;
		if($this->teamRepository->updateTeam($save, $team->id)) {
			return redirect()->route('teams.index')->with('success','Team updated successfully.');
		}else{		
			return back()->withErrors(['Updates failed, Please try again']);
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
		$team->delete();
		return redirect()->route('teams.index')->with('success','Team deleted successfully');
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