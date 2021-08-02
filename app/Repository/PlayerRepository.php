<?php 

namespace App\Repository;

use App\Models\Player;

class PlayerRepository implements PlayerRepositoryInterface
{
	/*
    |--------------------------------------------------------------------------
    | Player Repository
    |--------------------------------------------------------------------------
    |
    | This repository will act a sa bridge between controller and models
    | Ths repository will mainly handle the data transfer between controllers
    | and player model.
    |
    */
	
	/**
     * Create a method to fetch the lists of all players.
     *
     * @return collections
     */
	public function getAllPlayers():object {		
		return Player::join('teams', 'players.team_id', '=', 'teams.id') 
				->orderBy('players.created_at', 'desc')
				->select('players.*','teams.name')->paginate(5);
	}
	
	/**
     * Create a method to fetch the single team details based on id.
     *
     * @return collections
     */	
	public function getSinglePlayer(object $player) : object{
		$player->team = $player->team;
		return $player;		
	}	
	
	/**
     * Method to create a new record in the player table.
     *
     * @return collections
     */
	public function createPlayer(array $player) : object {
		$player = Player::create($player);
		return $player;
	}
	
	/**
     * Method to update a records in the player table
     *
     * @return bool
     */
	public function updatePlayer(array $player,int $id) : bool {
		$player = Player::where('id', $id)->update($player);
		return true;
	}
	
	/**
     * Method to delete a records in the player table
     *
     * @return bool
     */
	public function deletePlayer(int $id) : bool {
		if (Player::destroy($id)) {
			return true;
		}else{
			return false;
		}
	}

}