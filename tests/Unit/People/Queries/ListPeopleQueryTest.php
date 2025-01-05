<?php

namespace Tests\Unit\People\Queries;

use Tests\TestCase;
use App\Queries\People\ListPeopleQuery;
use App\Handlers\Queries\People\ListPeopleHandler;
use App\Models\People;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListPeopleQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_people_through_query(): void
    {
        People::factory()->count(3)->create();

        $query = new ListPeopleQuery();
        $handler = new ListPeopleHandler();

        $result = $handler->handle($query);

        $this->assertCount(3, $result);
        $this->assertInstanceOf(People::class, $result->first());
    }

    public function test_returns_empty_collection_when_no_people(): void
    {
        $query = new ListPeopleQuery();
        $handler = new ListPeopleHandler();

        $result = $handler->handle($query);

        $this->assertCount(0, $result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
}
