<?php

namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use App\Models\Player;

class CreatePlayerSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
	*/
    public function run()
    {
        $players = [
				['firstName' => 'PlayerF1','lastName' => 'PlayerL1','team_id' => '1', 'playerImageURI' => 'http://127.0.0.1:8000/uploads/1.jpg'],
				['firstName' => 'PlayerF2','lastName' => 'PlayerL2','team_id' => '1', 'playerImageURI' => 'http://127.0.0.1:8000/uploads/2.jpg'],
				['firstName' => 'PlayerF3','lastName' => 'PlayerL4','team_id' => '2', 'playerImageURI' => 'http://127.0.0.1:8000/uploads/3.jpg'],
				['firstName' => 'PlayerF4','lastName' => 'PlayerL4','team_id' => '2', 'playerImageURI' => 'http://127.0.0.1:8000/uploads/4.jpg']
		];
		Player::insert($players);
    }
}
