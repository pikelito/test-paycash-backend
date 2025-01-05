<?php

namespace Tests\Unit\People;

use Tests\TestCase;
use App\Mediators\PeopleMediator;
use App\Commands\People\CreatePersonCommand;
use App\Commands\People\UpdatePersonCommand;
use App\Commands\People\DeletePersonCommand;
use App\Queries\People\GetPersonQuery;
use App\Queries\People\ListPeopleQuery;
use App\Handlers\Commands\People\CreatePersonHandler;
use App\Handlers\Commands\People\UpdatePersonHandler;
use App\Handlers\Commands\People\DeletePersonHandler;
use App\Handlers\Queries\People\GetPersonHandler;
use App\Handlers\Queries\People\ListPeopleHandler;
use Mockery;
use Mockery\MockInterface;

class PeopleMediatorTest extends TestCase
{
    private PeopleMediator $mediator;
    private CreatePersonHandler|MockInterface $createHandler;
    private UpdatePersonHandler|MockInterface $updateHandler;
    private DeletePersonHandler|MockInterface $deleteHandler;
    private GetPersonHandler|MockInterface $getHandler;
    private ListPeopleHandler|MockInterface $listHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createHandler = Mockery::mock(CreatePersonHandler::class);
        $this->updateHandler = Mockery::mock(UpdatePersonHandler::class);
        $this->deleteHandler = Mockery::mock(DeletePersonHandler::class);
        $this->getHandler = Mockery::mock(GetPersonHandler::class);
        $this->listHandler = Mockery::mock(ListPeopleHandler::class);

        $this->mediator = new PeopleMediator(
            $this->createHandler,
            $this->updateHandler,
            $this->deleteHandler,
            $this->getHandler,
            $this->listHandler
        );
    }

    public function test_routes_create_command_to_create_handler(): void
    {
        $command = new CreatePersonCommand(
            first_name: 'Test',
            last_name: 'User',
            email: 'test@example.com'
        );

        $this->createHandler
            ->expects('handle')
            ->once()
            ->with($command);

        $this->mediator->send($command);
        $this->assertTrue(true);
    }

    public function test_routes_update_command_to_update_handler(): void
    {
        $command = new UpdatePersonCommand(
            id: 1,
            first_name: 'Test',
            last_name: 'User',
            email: 'test@example.com'
        );

        $this->updateHandler
            ->expects('handle')
            ->once()
            ->with($command);

        $this->mediator->send($command);
        $this->assertTrue(true);
    }

    public function test_routes_delete_command_to_delete_handler(): void
    {
        $command = new DeletePersonCommand(1);

        $this->deleteHandler
            ->expects('handle')
            ->once()
            ->with($command);

        $this->mediator->send($command);
        $this->assertTrue(true);
    }

    public function test_routes_get_query_to_get_handler(): void
    {
        $query = new GetPersonQuery(1);

        $this->getHandler
            ->expects('handle')
            ->once()
            ->with($query);

        $this->mediator->send($query);
        $this->assertTrue(true);
    }

    public function test_routes_list_query_to_list_handler(): void
    {
        $query = new ListPeopleQuery();

        $this->listHandler
            ->expects('handle')
            ->once()
            ->with($query);

        $this->mediator->send($query);
        $this->assertTrue(true);
    }

    public function test_throws_exception_for_unknown_message(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown message type');

        $this->mediator->send(new \stdClass());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
