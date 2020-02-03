<?php

namespace App\Http\Controllers;

use App\Repository\EntityRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EntitiesController extends Controller
{
    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

    /**
     * EntitiesController constructor.
     * @param EntityRepositoryInterface $entityRepository
     */
    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'date' => 'required|date',
            'image' => 'string'
        ]);

        $name = $request->request->get('name');
        $date = $request->request->get('date');
        $image = $request->request->get('image');

        $entity = $this->entityRepository->create($name, new \DateTime($date), $image);

        return new JsonResponse([
            'entity' => $entity,
        ], Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $entities = $this->entityRepository->findAll();

        return new JsonResponse([
            'entities' => $entities,
        ]);
    }
}
