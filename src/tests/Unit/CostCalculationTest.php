<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\Log;
use App\Models\Station;
use App\Models\User;
use App\Services\CostCalculationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CostCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function testMonthlyCostForSingleUser()
    {
        $NUM_LOGS = 10;
        $COST_PER_LOG = 10;

        $user = User::factory()->createOne();
        $date = new Carbon('2021-01-03');
        // relevant logs
        Log::factory()->for(Device::factory()->for($user))->count($NUM_LOGS)->create([
            'dateOfExit' => $date,
            'cost' => $COST_PER_LOG
        ]);
        // non relevant logs for the same user
        Log::factory()->for(Device::factory()->for($user))->count($NUM_LOGS)->create([
            'dateOfExit' => $date->copy()->addMonth(),
            'cost' => 9999
        ]);
        // non relevant logs for other users
        $anotherUser = User::factory()->createOne();
        Log::factory()->for(Device::factory()->for($anotherUser))->count($NUM_LOGS)->create([
            'dateOfExit' => $date,
            'cost' => 9999
        ]);

        $cost = CostCalculationService::monthlyCostForUser($user, $date);

        $this->assertEqualsWithDelta($NUM_LOGS * $COST_PER_LOG, $cost, 0.001);
    }

    public function testMonthlyCostForAllUsers()
    {
        $NUM_LOGS = 10;
        $COST_PER_LOG = 10;

        $users = User::factory()->count(10)->create();
        $date = new Carbon('2021-01-03');
        $users->each(function($user) use($NUM_LOGS, $COST_PER_LOG, $date) {
            // relevant logs
            Log::factory()->for(Device::factory()->for($user))->count($NUM_LOGS)->create([
                'dateOfExit' => $date,
                'cost' => $COST_PER_LOG * $user->id // multiply by the user id so the total cost is different for each user
            ]);
            // non relevant logs for the same user
            Log::factory()->for(Device::factory()->for($user))->count($NUM_LOGS)->create([
                'dateOfExit' => $date->copy()->addMonth(),
                'cost' => 9999
            ]);
        });

        $cost = CostCalculationService::monthlyCostForAll($date);

        $cost->each(function($user) use($NUM_LOGS, $COST_PER_LOG) {
            $this->assertEqualsWithDelta($NUM_LOGS * $COST_PER_LOG * $user->id, $user->cost, 0.001);
        });
    }
}
