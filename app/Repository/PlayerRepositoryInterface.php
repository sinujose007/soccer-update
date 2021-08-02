<?php

namespace App\Repository;

interface PlayerRepositoryInterface{
	
	public function getAllPlayers() : object;
	
	public function getSinglePlayer(object $player) : object;
	
	public function createPlayer(array $player) : object;
	
	public function updatePlayer(array $player, int $id) : bool;
	
	public function deletePlayer(int $id) : bool;
}