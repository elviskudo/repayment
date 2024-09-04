<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\DebitCard;
use App\Models\DebitCardTransaction;

class DebitCardTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function a_user_can_create_a_debit_card_transaction()
    {
        $user = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $data = [
            'debit_card_id' => $debitCard->id,
            'amount' => 100,
            'description' => 'Test Transaction',
        ];

        $response = $this->post('/debit-card-transactions', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('debit_card_transactions', [
            'debit_card_id' => $debitCard->id,
            'amount' => 100,
        ]);
    }

    public function a_user_can_view_his_own_debit_card_transactions()
    {
        $user = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $user->id]);
        $transaction = DebitCardTransaction::factory()->create(['debit_card_id' => $debitCard->id]);
        $this->actingAs($user);

        $response = $this->get('/debit-card-transactions');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $transaction->id,
            'amount' => $transaction->amount,
        ]);
    }

    public function a_user_cannot_create_a_transaction_for_another_users_debit_card()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $anotherUser->id]);
        $this->actingAs($user);

        $data = [
            'debit_card_id' => $debitCard->id,
            'amount' => 100,
            'description' => 'Test Transaction',
        ];

        $response = $this->post('/debit-card-transactions', $data);

        $response->assertStatus(403);
    }

    public function a_user_cannot_view_transactions_of_another_users_debit_card()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $debitCard = DebitCard::factory()->create(['user_id' => $anotherUser->id]);
        $transaction = DebitCardTransaction::factory()->create(['debit_card_id' => $debitCard->id]);
        $this->actingAs($user);

        $response = $this->get('/debit-card-transactions');

        $response->assertStatus(200);
        $response->assertJsonMissing(['id' => $transaction->id]);
    }

}
