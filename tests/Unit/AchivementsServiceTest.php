<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserAchievements;
use App\Services\AchievementsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AchivementsServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testGetUnlockedAchievements(): void
    {
        $user = User::factory()->make();

        $achievements = UserAchievements::factory()->count(2)->make()->toArray();

        $userAchievements = [];
        foreach ($achievements as $achievement) {
            $userAchievements[] = [
                'name' => $achievement['achievement_name']
            ];
        }

        // Mock the service binding in the container
        $mockedService = Mockery::mock(AchievementsService::class);
        $this->app->instance(AchievementsService::class, $mockedService);

        $mockedService->shouldReceive('getUnlockedAchievements')->once()->with($user)->andReturn($userAchievements);

        // Call the service through the container
        $unlockedAchievements = app(AchievementsService::class)->getUnlockedAchievements($user);

        // assert that achievements count is 2
        $this->assertCount(2, $unlockedAchievements);

        // check if the response is same
        $this->assertEquals($userAchievements, $unlockedAchievements);
    }

    public function testAchievementCountToNextBadge(): void
    {
        $user = User::factory()->make();

        $achievementCount = UserAchievements::factory()->count(3)->make()->count();

        // Mock the service binding in the container
        $mockedService = Mockery::mock(AchievementsService::class);
        $this->app->instance(AchievementsService::class, $mockedService);

        $mockedService->shouldReceive('getAchievementsCountToNextBadge')->once()->with($user)->andReturn(1);

        // Call the service through the container
        $countToNextBadge = app(AchievementsService::class)->getAchievementsCountToNextBadge($user);

        // assert that achievements count for next badge is 1
        $this->assertEquals(1, $countToNextBadge);
    }

    public function testNextBadge(): void
    {
        $user = User::factory()->make();

        // Mock the service binding in the container
        $mockedService = Mockery::mock(AchievementsService::class);
        $this->app->instance(AchievementsService::class, $mockedService);

        $mockedService->shouldReceive('getCurrentBadge')->once()->with($user)->andReturn("Beginner");
        
        $badge = app(AchievementsService::class)->getCurrentBadge($user);

        $mockedService->shouldReceive('getNextBadge')->once()->with($user)->andReturn("Intermediate");
        

        // Call the service through the container
        $nextBadge = app(AchievementsService::class)->getNextBadge($user);

        // assert that next badge is Beginner
        $this->assertEquals("Intermediate", $nextBadge);

        UserAchievements::factory()->count(4)->make()->count();

        $mockedService->shouldReceive('getNextBadge')->once()->with($user)->andReturn("Advanced");

        $mockedService->shouldReceive('getCurrentBadge')->once()->with($user)->andReturn("Advanced");

        // Call the service through the container
        $nextBadge = app(AchievementsService::class)->getNextBadge($user);
        $currentBadge = app(AchievementsService::class)->getCurrentBadge($user);

        $this->assertEquals("Advanced", $nextBadge);
        $this->assertNotEquals($currentBadge, $badge);

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
