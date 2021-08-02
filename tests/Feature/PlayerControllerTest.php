<?php 
namespace Tests\Feature;

use App\Repository\PlayerRepositoryInterface;
use Mockery as m;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PlayerControllerTest extends TestCase
{
	use WithoutMiddleware;
	private $playerRepository;

	public function setUp() : void
	{
		parent::setup();

		$this->playerRepository = m::mock('App\Repository\PlayerRepositoryInterface');
		$this->app->instance('App\Repository\PlayerRepositoryInterface', $this->playerRepository);
	}

	public function tearDown() : void
	{
		m::close();
		parent::tearDown();
	}

	public function testIndex()
	{
		$this->playerRepository->shouldReceive('getAllPlayers')->once();
		$response = $this->call('GET', '/players');
		$this->assertEquals(200, $response->status());
	}
}