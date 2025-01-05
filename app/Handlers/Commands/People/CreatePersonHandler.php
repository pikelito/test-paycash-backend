<?php

namespace App\Handlers\Commands\People;

use App\Commands\People\CreatePersonCommand;
use App\Models\People;

class CreatePersonHandler
{
    public function handle(CreatePersonCommand $command): People
    {
        return People::create([
            'first_name' => $command->first_name,
            'last_name' => $command->last_name,
            'email' => $command->email,
            'phone' => $command->phone,
            'birthdate' => $command->birthdate,
            'address' => $command->address,
        ]);
    }
}
