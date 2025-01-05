<?php

namespace Tests\Unit\People\Commands;

use Tests\TestCase;
use App\Commands\People\DeletePersonCommand;
use App\Handlers\Commands\People\DeletePersonHandler;
use App\Models\People;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePersonCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_person_through_command(): void
    {
        $person = People::factory()->create();

        $command = new DeletePersonCommand($person->id);
        $handler = new DeletePersonHandler();

        $handler->handle($command);

        $this->assertDatabaseMissing('people', [
            'id' => $person->id
        ]);
    }

    public function test_throws_exception_when_person_not_found(): void
    {
        $this->expectException(\RuntimeException::class);

        $command = new DeletePersonCommand(999);
        $handler = new DeletePersonHandler();

        $handler->handle($command);
    }
}
