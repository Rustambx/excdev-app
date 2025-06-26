<?php

namespace Tests\Feature;

use App\Models\Balance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show()
    {
        $user = User::factory()->create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'login' => 'Test',
            'password' => bcrypt('password')
        ]);
        Balance::factory()->create([
            'user_id' => $user->id,
            'amount' => 2000
        ]);

        Transaction::factory()->count(6)->create([
            'user_id' => $user->id,
            'amount' => 100,
            'type' => 'debit',
            'description' => 'Test Transaction'
        ]);

        $token = $user->createToken('Test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/balance');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'balance',
            'transactions' => [
                '*' => [
                    'id',
                    'amount',
                    'type',
                    'description',
                    'created_at',
                ]
            ]
        ]);

        $this->assertEquals(2000, $response->json('balance'));
        $this->assertCount(5, $response->json('transactions'));
    }
}
