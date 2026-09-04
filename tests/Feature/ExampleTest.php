<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Everyday');
        $response->assertSee('Email or Username');
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        // Seed Role and Admin
        $role = Role::create(['role_name' => 'Super Admin']);
        $admin = Admin::create([
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admin@everyday.test',
            'password' => bcrypt('password')
        ]);
        $admin->roles()->attach($role->id);

        $response = $this->actingAs($admin)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Everyday');
        $response->assertSee('General Notes');
        $response->assertSee('Roles & Users', false);
    }
}
