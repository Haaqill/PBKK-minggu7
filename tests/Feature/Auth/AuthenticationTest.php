<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\DemoLoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_demo_login_forms_are_outside_the_email_login_form(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        Route::post('/_local/demo-login', [DemoLoginController::class, 'login'])
            ->middleware('web')
            ->name('local.demo-login');
        Route::getRoutes()->refreshNameLookups();

        $html = $this->get('/login')
            ->assertOk()
            ->getContent();

        $emailFormStart = strpos($html, '<form method="POST" action="'.route('login').'"');
        $demoFormStart = strpos($html, '<form method="POST" action="'.route('local.demo-login').'"');
        $emailFormEnd = strpos($html, '</form>', $emailFormStart);

        $this->assertNotFalse($emailFormStart);
        $this->assertNotFalse($demoFormStart);
        $this->assertNotFalse($emailFormEnd);
        $this->assertLessThan($demoFormStart, $emailFormEnd);

        $dosen = User::factory()->create([
            'email' => 'dosen@gmail.com',
            'role' => 'dosen',
        ]);

        $this->post(route('local.demo-login'), [
            'role' => 'dosen',
            '_token' => session()->token(),
        ])
            ->assertRedirect(route('admin.index'));
        $this->assertAuthenticatedAs($dosen);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('mahasiswa.proposal.index'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
