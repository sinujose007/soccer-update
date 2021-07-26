<?php

namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use App\Models\Team;

class CreateTeamSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
	*/
    public function run()
    {
        $teams = [
				['name' => 'Manchester', 'logoURI' => 'http://127.0.0.1:8000/uploads/1626838847.png'],
				['name' => 'Striker',    'logoURI' => 'http://127.0.0.1:8000/uploads/1626843276.jpg']
		];
		Team::insert($teams);		
    }
}