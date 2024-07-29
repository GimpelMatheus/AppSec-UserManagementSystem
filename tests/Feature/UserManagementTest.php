<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\UserSeeder::class); // Popula o banco de dados
    }

    public function test_admin_can_view_dashboard()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_admin_can_create_user()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post('/admin/create', [
            'name' => 'New Admin',
            'email' => 'newadmin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertDatabaseHas('users', ['email' => 'newadmin@example.com', 'role' => 'admin']);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::where('role', 'admin')->first();
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($admin)->delete("/admin/user/{$user->id}");
        $response->assertRedirect('/admin');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect('/home');
    }

    public function test_user_can_view_profile()
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_account_lockout_after_failed_attempts()
    {
        $user = User::where('role', 'user')->first();

        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrongpassword',
            ]);
        }

        $user->refresh();
        $this->assertTrue($user->account_locked);
    }
}

