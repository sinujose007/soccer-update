<?php

namespace App\Repository;

interface PlayerRepositoryInterface{
	
	public function getAllPlayers() : object;
	
	public function createPlayer(array $player) : object;
	
	public function updatePlayer(array $player, int $id) : bool;
}