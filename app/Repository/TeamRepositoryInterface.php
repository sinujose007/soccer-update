<?php

namespace App\Repository;

interface TeamRepositoryInterface{
	
	public function getAllTeams() : object;
	
	public function createTeam(array $player) : object;
	
	public function updateTeam(array $player, int $id) : bool;
}