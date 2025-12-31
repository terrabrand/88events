<?php

namespace Tests\Feature;

use App\Models\AdPackage;
use App\Models\CreditTransaction;
use App\Models\Event;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_deposit_credits()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        
        $response = $this->actingAs($organizer)->post(route('organizer.credits.deposit'), [
            'amount' => 100,
        ]);

        $response->assertRedirect();
        $this->assertEquals(100, $organizer->fresh()->credits);
        $this->assertDatabaseHas('credit_transactions', [
            'user_id' => $organizer->id,
            'amount' => 100,
            'type' => 'deposit',
        ]);
    }

    public function test_organizer_can_purchase_promotion()
    {
        $organizer = User::factory()->create(['role' => 'organizer', 'credits' => 50]);
        $event = Event::factory()->create(['organizer_id' => $organizer->id]);
        $package = AdPackage::create([
            'name' => 'Test Package',
            'price' => 20,
            'duration_days' => 7,
            'is_active' => true,
        ]);

        $response = $this->actingAs($organizer)->post(route('organizer.promotions.store'), [
            'event_id' => $event->id,
            'ad_package_id' => $package->id,
        ]);

        $response->assertRedirect();
        $this->assertEquals(30, $organizer->fresh()->credits); // 50 - 20
        $this->assertDatabaseHas('promotions', [
            'event_id' => $event->id,
            'ad_package_id' => $package->id,
            'status' => 'active',
        ]);
        $this->assertTrue($event->fresh()->is_promoted);
    }

    public function test_organizer_cannot_purchase_promotion_with_insufficient_funds()
    {
        $organizer = User::factory()->create(['role' => 'organizer', 'credits' => 10]);
        $event = Event::factory()->create(['organizer_id' => $organizer->id]);
        $package = AdPackage::create([
            'name' => 'Test Package',
            'price' => 20,
            'duration_days' => 7,
            'is_active' => true,
        ]);

        $response = $this->actingAs($organizer)->post(route('organizer.promotions.store'), [
            'event_id' => $event->id,
            'ad_package_id' => $package->id,
        ]);

        $response->assertSessionHasErrors('error');
        $this->assertEquals(10, $organizer->fresh()->credits);
        $this->assertDatabaseMissing('promotions', [
            'event_id' => $event->id,
        ]);
    }
}
