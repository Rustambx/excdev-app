<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $user = User::factory()->create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'login' => 'Test',
            'password' => bcrypt('password')
        ]);

        Transaction::factory()->count(3)->create([
            'user_id' => $user->id,
            'amount' => 100,
            'type' => 'credit',
            'description' => 'Test transaction',
        ]);

        $token = $user->createToken('main')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/transactions');

        $response->assertStatus(200);

        $this->assertCount(3, $response->json());

        $response->assertJsonStructure([
            '*' => [
                'id',
                'amount',
                'type',
                'description',
                'created_at'
            ]
        ]);
    }
}
