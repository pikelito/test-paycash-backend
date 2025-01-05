<?php

namespace App\Handlers\Queries\People;

use App\Queries\People\GetPersonQuery;
use App\Models\People;

class GetPersonHandler
{
    public function handle(GetPersonQuery $query): ?People
    {
        return People::find($query->id);
    }
}
