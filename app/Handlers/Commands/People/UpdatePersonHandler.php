<?php
namespace App\Handlers\Commands\People;

use App\Commands\People\UpdatePersonCommand;
use App\Models\People;

class UpdatePersonHandler
{
    public function handle(UpdatePersonCommand $command): People
    {
        $person = People::findOrFail($command->id);

        $updateData = array_filter([
            'first_name' => $command->first_name,
            'last_name' => $command->last_name,
            'email' => $command->email,
            'phone' => $command->phone,
            'birthdate' => $command->birthdate,
            'address' => $command->address,
        ], fn($value) => !is_null($value));

        $person->update($updateData);
        return $person;
    }
}