<?php 

namespace App\Repository;

use App\Models\Team;

class TeamRepository implements TeamRepositoryInterface
{
	/*
    |--------------------------------------------------------------------------
    | Team Repository
    |--------------------------------------------------------------------------
    |
    | This repository will act a sa bridge between controller and models
    | Ths repository will mainly handle the data transfer between controllers
    | and team model.
    |
    */
	
	/**
     * Create a method to fetch the lists of all teams.
     *
     * @return collections
     */
	public function getAllTeams():object {		
		return Team::latest()->paginate(5);
	}
	
	/**
     * Method to create a new record in the team table.
     *
     * @return collections
     */
	public function createTeam(array $team) : object {
		$team = Team::create($team);
		return $team;
	}
	
	/**
     * Method to update a records in the team table
     *
     * @return bool
     */
	public function updateTeam(array $team,int $id) : bool {
		$team = Team::where('id', $id)->update($team);
		return true;
	}

}