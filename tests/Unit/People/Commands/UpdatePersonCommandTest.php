<?php

namespace Tests\Unit\People\Commands;

use Tests\TestCase;
use App\Commands\People\UpdatePersonCommand;
use App\Handlers\Commands\People\UpdatePersonHandler;
use App\Models\People;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePersonCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_person_through_command(): void
    {
        $person = People::factory()->create();

        $command = new UpdatePersonCommand(
            id: $person->id,
            first_name: 'Updated',
            last_name: 'Name',
            email: 'updated@example.com',
            phone: '0987654321',
            birthdate: '1995-05-05',
            address: 'Street 456'
        );

        $handler = new UpdatePersonHandler();
        $result = $handler->handle($command);

        $this->assertEquals('Updated', $result->first_name);
        $this->assertEquals('updated@example.com', $result->email);
        $this->assertDatabaseHas('people', [
            'email' => 'updated@example.com'
        ]);
    }

    public function test_throws_exception_when_person_not_found(): void
    {
        $this->expectException(\RuntimeException::class);

        $command = new UpdatePersonCommand(
            id: 999,
            first_name: 'Updated',
            last_name: 'Name',
            email: 'updated@example.com'
        );

        $handler = new UpdatePersonHandler();
        $handler->handle($command);
    }

    public function test_can_update_only_required_fields(): void
    {
        $person = People::factory()->create();

        $command = new UpdatePersonCommand(
            id: $person->id,
            first_name: 'Updated',
            last_name: 'Name',
            email: 'updated@example.com'
        );

        $handler = new UpdatePersonHandler();
        $result = $handler->handle($command);

        $this->assertEquals('Updated', $result->first_name);
        $this->assertEquals($person->phone, $result->phone);
        $this->assertEquals($person->birthdate, $result->birthdate);
        $this->assertEquals($person->address, $result->address);
    }
}
