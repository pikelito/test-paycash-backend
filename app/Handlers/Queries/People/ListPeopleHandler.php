<?php
namespace App\Handlers\Queries\People;

use App\Queries\People\ListPeopleQuery;
use App\Models\People;

class ListPeopleHandler
{
    public function handle(ListPeopleQuery $query)
    {
        return People::all();
    }
}