<?php
namespace App\Commands\People;

class DeletePersonCommand
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}