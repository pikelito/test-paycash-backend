<?php

namespace App\Handlers\Commands\People;

use App\Commands\People\DeletePersonCommand;
use App\Models\People;

class DeletePersonHandler
{
    public function handle(DeletePersonCommand $command): void
    {
        $person = People::findOrFail($command->id);
        $person->delete();
    }
}
