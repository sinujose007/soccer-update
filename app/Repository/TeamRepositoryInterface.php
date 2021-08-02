<?php

namespace App\Repository;

interface TeamRepositoryInterface{
	
	public function getAllTeams() : object;
	
	public function getAllTeamsPaging() : object;
	
	public function getSingleTeam(object $team) : object;
	
	public function createTeam(array $team) : object;
	
	public function updateTeam(array $team, int $id) : bool;
	
	public function deleteTeam(int $id) : bool;
}