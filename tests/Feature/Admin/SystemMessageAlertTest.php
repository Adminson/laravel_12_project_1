<?php

namespace Tests\Feature\Admin;

use App\Models\CompanyProfile;
use App\Models\SystemMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SystemMessageAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_system_message_page_shows_only_active_company_alerts_with_type_styles(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-03-09 10:00:00', 'UTC'));

        try {
            $user = User::factory()->create();

            $company = CompanyProfile::create([
                'company_name' => 'Alpha Company',
            ]);

            $otherCompany = CompanyProfile::create([
                'company_name' => 'Beta Company',
            ]);

            SystemMessage::create([
                'company_profile_id' => $company->cmp_id,
                'title' => 'Blue Active',
                'description' => 'Best 1 {{date1}} to {{date2}}',
                'type' => 'blue',
                'start_date' => now()->subHours(2),
                'end_date' => now()->addHours(2),
            ]);

            SystemMessage::create([
                'company_profile_id' => $company->cmp_id,
                'title' => 'Red Active',
                'description' => 'Red active description',
                'type' => 'red',
                'start_date' => now()->subMinutes(90),
                'end_date' => now()->addMinutes(90),
            ]);

            SystemMessage::create([
                'company_profile_id' => $company->cmp_id,
                'title' => 'Orange Active',
                'description' => 'Orange active description',
                'type' => 'orange',
                'start_date' => now()->subMinutes(30),
                'end_date' => now()->addMinutes(30),
            ]);

            SystemMessage::create([
                'company_profile_id' => $company->cmp_id,
                'title' => 'Future Message',
                'description' => 'Should not be visible',
                'type' => 'blue',
                'start_date' => now()->addMinute(),
                'end_date' => now()->addHour(),
            ]);

            SystemMessage::create([
                'company_profile_id' => $company->cmp_id,
                'title' => 'Expired Message',
                'description' => 'Should not be visible',
                'type' => 'red',
                'start_date' => now()->subHours(3),
                'end_date' => now()->subMinute(),
            ]);

            SystemMessage::create([
                'company_profile_id' => $company->cmp_id,
                'title' => 'Missing Date Message',
                'description' => 'Should not be visible',
                'type' => 'orange',
                'start_date' => null,
                'end_date' => now()->addHour(),
            ]);

            SystemMessage::create([
                'company_profile_id' => $otherCompany->id,
                'title' => 'Other Company Active',
                'description' => 'Should not be visible',
                'type' => 'blue',
                'start_date' => now()->subHour(),
                'end_date' => now()->addHour(),
            ]);

            $response = $this->actingAs($user)->get('/admin/system-message/' . $company->cmp_id);

            $response->assertOk();
            $response->assertSeeInOrder([
                'Orange Active',
                'Red Active',
                'Blue Active',
            ]);

            $response->assertSee('Orange active description');
            $response->assertSee('Red active description');
            $response->assertSee('Best 1 09-Mar-2026 08:00 AM to 09-Mar-2026 12:00 PM');

            $response->assertSee('alert alert-solid-warning d-flex align-items-center mb-3', false);
            $response->assertSee('alert alert-solid-danger d-flex align-items-center mb-3', false);
            $response->assertSee('alert alert-solid-success d-flex align-items-center mb-3', false);

            $response->assertSee('tabler-bell', false);
            $response->assertSee('tabler-ban', false);
            $response->assertSee('tabler-check', false);

            $response->assertDontSee('Future Message');
            $response->assertDontSee('Expired Message');
            $response->assertDontSee('Missing Date Message');
            $response->assertDontSee('Other Company Active');
            $response->assertDontSee('{{date1}}');
            $response->assertDontSee('{{date2}}');
        } finally {
            Carbon::setTestNow();
        }
    }
}
