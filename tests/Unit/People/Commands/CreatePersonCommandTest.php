<?php

namespace Tests\Unit\People\Commands;

use Tests\TestCase;
use App\Commands\People\CreatePersonCommand;
use App\Handlers\Commands\People\CreatePersonHandler;
use App\Models\People;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePersonCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_person_through_command(): void
    {
        $command = new CreatePersonCommand(
            first_name: 'Miguel',
            last_name: 'Mariño',
            email: 'miguel@example.com',
            phone: '1234567890',
            birthdate: '1990-01-01',
            address: 'Street 123'
        );

        $handler = new CreatePersonHandler();
        $result = $handler->handle($command);

        $this->assertInstanceOf(People::class, $result);
        $this->assertEquals('Miguel', $result->first_name);
        $this->assertEquals('miguel@example.com', $result->email);
        $this->assertDatabaseHas('people', [
            'email' => 'miguel@example.com'
        ]);
    }

    public function test_can_create_without_optional_fields(): void
    {
        $command = new CreatePersonCommand(
            first_name: 'Miguel',
            last_name: 'Mariño',
            email: 'miguel@example.com'
        );

        $handler = new CreatePersonHandler();
        $result = $handler->handle($command);

        $this->assertInstanceOf(People::class, $result);
        $this->assertNull($result->phone);
        $this->assertNull($result->birthdate);
        $this->assertNull($result->address);
    }
}
