<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = new AuthService();
        $this->user = User::factory()->create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'login' => 'Test',
            'password' => bcrypt('password')
        ]);
    }


    public function test_login()
    {
        $result = $this->authService->login([
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);
    }
}
