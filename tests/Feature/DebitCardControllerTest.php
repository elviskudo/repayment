<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\DebitCard;

class DebitCardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function a_user_can_create_a_debit_card()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'card_number' => '1234567890123456',
            'expiry_date' => '12/25',
            'cvv' => '123',
        ];

        $response = $this->post('/debit-cards', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('debit_cards', [
            'user_id' => $user->id,
            'card_number' => '1234567890123456',
        ]);
    }

    public function a_user_can_update_his_own_debit_card()
    {
        $user = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $data = [
            'expiry_date' => '01/26',
        ];

        $response = $this->put("/debit-cards/{$debitCard->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('debit_cards', [
            'id' => $debitCard->id,
            'expiry_date' => '01/26',
        ]);
    }

    public function a_user_can_view_his_own_debit_cards()
    {
        $user = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get('/debit-cards');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $debitCard->id,
            'card_number' => $debitCard->card_number,
        ]);
    }

    public function a_user_can_delete_his_own_debit_card()
    {
        $user = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->delete("/debit-cards/{$debitCard->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('debit_cards', ['id' => $debitCard->id]);
    }

    public function a_user_cannot_create_a_debit_card_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'card_number' => 'invalid_card_number',
            'expiry_date' => 'invalid_date',
            'cvv' => '12',
        ];

        $response = $this->post('/debit-cards', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['card_number', 'expiry_date', 'cvv']);
    }

    public function a_user_cannot_update_another_users_debit_card()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $anotherUser->id]);
        $this->actingAs($user);

        $data = [
            'expiry_date' => '01/26',
        ];

        $response = $this->put("/debit-cards/{$debitCard->id}", $data);

        $response->assertStatus(403);
    }

    public function a_user_cannot_view_another_users_debit_cards()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $anotherUser->id]);
        $this->actingAs($user);

        $response = $this->get('/debit-cards');

        $response->assertStatus(200);
        $response->assertJsonMissing(['id' => $debitCard->id]);
    }

}
