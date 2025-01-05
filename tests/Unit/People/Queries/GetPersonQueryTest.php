<?php

namespace Tests\Unit\People\Queries;

use Tests\TestCase;
use App\Queries\People\GetPersonQuery;
use App\Handlers\Queries\People\GetPersonHandler;
use App\Models\People;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetPersonQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_person_through_query(): void
    {
        $person = People::factory()->create();

        $query = new GetPersonQuery($person->id);
        $handler = new GetPersonHandler();

        $result = $handler->handle($query);

        $this->assertInstanceOf(People::class, $result);
        $this->assertEquals($person->id, $result->id);
        $this->assertEquals($person->email, $result->email);
    }

    public function test_returns_null_for_nonexistent_person(): void
    {
        $query = new GetPersonQuery(999);
        $handler = new GetPersonHandler();

        $result = $handler->handle($query);

        $this->assertNull($result);
    }
}
