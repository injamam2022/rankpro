<?php

namespace Tests\Feature;

use Tests\TestCase;

class StudentClassNameTest extends TestCase
{
    public function test_class_options_include_nine_through_twelve_passed(): void
    {
        $this->assertSame(
            ['9', '10', '11', '12', '12 passed'],
            student_class_values()
        );
    }

    public function test_signup_page_shows_class_dropdown(): void
    {
        $response = $this->get(route('signup'));

        $response->assertOk();
        $response->assertSee('name="class_name"', false);
        $response->assertSee('12 passed', false);
        $response->assertSee('Select class', false);
    }

    public function test_signup_requires_class(): void
    {
        $response = $this->postJson(route('admissionstore'), [
            'first_name' => 'Test',
            'last_name' => 'Student',
            'mobile_number' => '9876543210',
            'email_id' => 'class-dropdown-test@example.com',
            'password' => 'test',
            'password_confirmation' => 'test',
        ]);

        $response->assertStatus(400);
        $response->assertJsonPath('success', false);
        $this->assertArrayHasKey('class_name', $response->json('errors') ?? []);
    }
}
