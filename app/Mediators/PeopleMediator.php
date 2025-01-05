<?php

namespace App\Mediators;

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

class PeopleMediator
{
    public function __construct(
        private CreatePersonHandler $createHandler,
        private UpdatePersonHandler $updateHandler,
        private DeletePersonHandler $deleteHandler,
        private GetPersonHandler $getHandler,
        private ListPeopleHandler $listHandler
    ) {
    }

    public function send($message)
    {
        return match (true) {
            $message instanceof CreatePersonCommand => $this->createHandler->handle($message),
            $message instanceof UpdatePersonCommand => $this->updateHandler->handle($message),
            $message instanceof DeletePersonCommand => $this->deleteHandler->handle($message),
            $message instanceof GetPersonQuery => $this->getHandler->handle($message),
            $message instanceof ListPeopleQuery => $this->listHandler->handle($message),
            default => throw new \InvalidArgumentException('Unknown message type')
        };
    }
}
