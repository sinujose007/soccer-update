<?php

namespace Tests\Feature;


use Tests\TestCase;

class TeamTest extends TestCase
{
    
	/**
     * feature test Team Listing.
     *
     * @return void
     */
	public function testTeamListedSuccessfully()
    {

        $response = $this->json('GET', '/teams', ['Accept' => 'application/json']);
		
        $response->assertStatus(200);
    }
	
	/**
     * feature test Team Details.
     *
     * @return void
     */
	public function testRetrieveTeamSuccessfully()
    {
		$response = $this->json('GET', '/teams/1', ['Accept' => 'application/json']);
        
		$response->assertStatus(200);
	}
	
	
}
