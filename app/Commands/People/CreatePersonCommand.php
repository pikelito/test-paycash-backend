<?php
namespace App\Commands\People;

class CreatePersonCommand
{
    public string $first_name;
    public string $last_name;
    public string $email;
    public ?string $phone;
    public ?string $birthdate;
    public ?string $address;

    public function __construct(
        string $first_name,
        string $last_name,
        string $email,
        ?string $phone = null,
        ?string $birthdate = null,
        ?string $address = null
    ) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->birthdate = $birthdate;
        $this->address = $address;
    }
}