<?php 
namespace Tests\Feature;

use App\Repository\TeamRepositoryInterface;
use Mockery as m;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TeamControllerTest extends TestCase
{
	use WithoutMiddleware;
	private $teamRepository;

	public function setUp() : void
	{
		parent::setup();

		$this->teamRepository = m::mock('App\Repository\TeamRepositoryInterface');
		$this->app->instance('App\Repository\TeamRepositoryInterface', $this->teamRepository);
	}

	public function tearDown() : void
	{
		m::close();
		parent::tearDown();
	}

	public function testIndex()
	{
		$this->teamRepository->shouldReceive('getAllTeams')->once();
		$response = $this->call('GET', '/teams?section');
		$this->assertEquals(200, $response->status());
	}
	
	public function testIndexPaging()
	{
		$this->teamRepository->shouldReceive('getAllTeamsPaging')->once();
		$response = $this->call('GET', '/teams');
		$this->assertEquals(200, $response->status());
	}
}