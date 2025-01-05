<?php

namespace App\Http\Controllers;

use App\Commands\People\CreatePersonCommand;
use App\Commands\People\UpdatePersonCommand;
use App\Commands\People\DeletePersonCommand;
use App\Queries\People\GetPersonQuery;
use App\Queries\People\ListPeopleQuery;
use App\Mediators\PeopleMediator;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Traits\ExceptionHandler;

class PeopleController extends Controller
{
    use ExceptionHandler;

    public function __construct(
        private PeopleMediator $mediator
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $query = new ListPeopleQuery();
            $result = $this->mediator->send($query);
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(StorePersonRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $command = new CreatePersonCommand(
                first_name: $validated['first_name'],
                last_name: $validated['last_name'],
                email: $validated['email'],
                phone: $validated['phone'] ?? null,
                birthdate: $validated['birthdate'] ?? null,
                address: $validated['address'] ?? null
            );

            $result = $this->mediator->send($command);
            return response()->json($result, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $query = new GetPersonQuery($id);
            $result = $this->mediator->send($query);

            if (!$result) {
                return response()->json([
                    'message' => 'Persona no encontrada',
                    'error' => 'Not Found'
                ], 404);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function update(UpdatePersonRequest $request, int $id): JsonResponse
    {
        try {
            $query = new GetPersonQuery($id);
            $person = $this->mediator->send($query);

            if (!$person) {
                return response()->json([
                    'message' => 'Persona no encontrada',
                    'error' => 'Not Found'
                ], 404);
            }

            $validated = $request->validated();

            $command = new UpdatePersonCommand(
                id: $id,
                first_name: $validated['first_name'],
                last_name: $validated['last_name'],
                email: $validated['email'],
                phone: $validated['phone'] ?? null,
                birthdate: $validated['birthdate'] ?? null,
                address: $validated['address'] ?? null
            );

            $result = $this->mediator->send($command);
            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $query = new GetPersonQuery($id);
            $person = $this->mediator->send($query);

            if (!$person) {
                return response()->json([
                    'message' => 'Persona no encontrada',
                    'error' => 'Not Found'
                ], 404);
            }

            $command = new DeletePersonCommand($id);
            $this->mediator->send($command);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
