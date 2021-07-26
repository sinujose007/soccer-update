<?php

namespace Tests\Feature;


use Tests\TestCase;

class PlayerTest extends TestCase
{
    
	/**
     * feature test Player Listing.
     *
     * @return void
     */
	public function testPlayerListedSuccessfully()
    {

        $response = $this->json('GET', '/players', ['Accept' => 'application/json']);
		
        $response->assertStatus(200);
    }
	
	/**
     * feature test Team Details.
     *
     * @return void
     */
	public function testRetrievePlayerSuccessfully()
    {
		$response = $this->json('GET', '/players/1', ['Accept' => 'application/json']);
        
		$response->assertStatus(200);
	}
	
	
}
