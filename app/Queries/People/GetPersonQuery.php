<?php
namespace App\Queries\People;

class GetPersonQuery
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}