<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\People;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PeopleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_person(): void
    {
        $personData = [
            'first_name' => 'Miguel',
            'last_name' => 'Mariño',
            'email' => 'miguel@example.com',
            'phone' => '1234567890',
            'birthdate' => '1990-01-01',
            'address' => 'Street 123'
        ];

        $this->postJson('/api/people', $personData)
            ->assertStatus(201)
            ->assertJson($personData);

        $this->assertDatabaseHas('people', $personData);
    }

    public function test_can_update_person(): void
    {
        $person = People::factory()->create();

        $updateData = [
            'first_name' => 'Miguel',
            'last_name' => 'Mariño',
            'email' => 'miguel@example.com',
            'phone' => '0987654321',
            'birthdate' => '1995-05-05',
            'address' => 'Street 456'
        ];

        $this->putJson("/api/people/{$person->id}", $updateData)
            ->assertStatus(200)
            ->assertJson($updateData);

        $this->assertDatabaseHas('people', $updateData);
    }

    public function test_can_delete_person(): void
    {
        $person = People::factory()->create();

        $this->deleteJson("/api/people/{$person->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('people', ['id' => $person->id]);
    }

    public function test_can_get_single_person(): void
    {
        $person = People::factory()->create();

        $this->getJson("/api/people/{$person->id}")
            ->assertStatus(200)
            ->assertJson($person->toArray());
    }

    public function test_can_list_people(): void
    {
        $people = People::factory()->count(3)->create();

        $response = $this->getJson('/api/people')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'phone',
                        'birthdate',
                        'address',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_email_must_be_unique_on_create(): void
    {
        $existingPerson = People::factory()->create();

        $newPersonData = [
            'first_name' => 'Miguel',
            'last_name' => 'Mariño',
            'email' => $existingPerson->email,
            'phone' => '1234567890',
            'birthdate' => '1990-01-01',
            'address' => 'Street 123'
        ];

        $this->postJson('/api/people', $newPersonData)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_email_must_be_unique_on_update_except_self(): void
    {
        $person1 = People::factory()->create();
        $person2 = People::factory()->create();

        $updateData = [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => $person2->email,
            'phone' => '1234567890',
            'birthdate' => '1990-01-01',
            'address' => 'Kra 123'
        ];

        $this->putJson("/api/people/{$person1->id}", $updateData)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_returns_404_when_person_not_found(): void
    {
        $this->getJson('/api/people/999')
            ->assertStatus(404);

        $this->putJson('/api/people/999', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com'
        ])->assertStatus(404);

        $this->deleteJson('/api/people/999')
            ->assertStatus(404);
    }

    public function test_can_create_person_without_optional_fields(): void
    {
        $personData = [
            'first_name' => 'Miguel',
            'last_name' => 'Mariño',
            'email' => 'miguel@example.com'
        ];

        $this->postJson('/api/people', $personData)
            ->assertStatus(201)
            ->assertJson($personData);

        $this->assertDatabaseHas('people', $personData);
    }
}
