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

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Gestión de Personas",
 *     description="API para gestionar información de personas"
 * )
 */
class PeopleController extends Controller
{
    use ExceptionHandler;

    public function __construct(
        private PeopleMediator $mediator
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/people",
     *     summary="Obtener lista de personas",
     *     tags={"Personas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de personas obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="first_name", type="string"),
     *                     @OA\Property(property="last_name", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="phone", type="string", nullable=true),
     *                     @OA\Property(property="birthdate", type="string", format="date", nullable=true),
     *                     @OA\Property(property="address", type="string", nullable=true)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/people",
     *     summary="Crear una nueva persona",
     *     tags={"Personas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name","last_name","email"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="phone", type="string", nullable=true),
     *             @OA\Property(property="birthdate", type="string", format="date", nullable=true),
     *             @OA\Property(property="address", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Persona creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/people/{id}",
     *     summary="Obtener una persona específica",
     *     tags={"Personas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la persona",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona encontrada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/people/{id}",
     *     summary="Actualizar una persona existente",
     *     tags={"Personas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la persona",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name","last_name","email"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="phone", type="string", nullable=true),
     *             @OA\Property(property="birthdate", type="string", format="date", nullable=true),
     *             @OA\Property(property="address", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona actualizada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/people/{id}",
     *     summary="Eliminar una persona",
     *     tags={"Personas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la persona",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Persona eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada"
     *     )
     * )
     */
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
