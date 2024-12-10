<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Service\Interface\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryServiceInterface $service,
        private SerializerInterface $serializer
    ) {
    }

    public function getList(): JsonResponse
    {
        return $this->json(
            $this->service->findAll()
        );
    }

    public function get(string $id): JsonResponse
    {
        return $this->json(
            $this->service->find($id)
        );
    }

    public function remove(string $id): JsonResponse
    {
        $this->service->remove($id);

        return new JsonResponse(
            status: Response::HTTP_NO_CONTENT
        );
    }

    public function create(Request $request): JsonResponse
    {
        $category = $this->serializer->deserialize(
            $request->getContent(),
            Category::class,
            'json'
        );
        $this->service->insert($category);

        return $this->json($category, Response::HTTP_CREATED);
    }

    public function update(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $category = $this->service->find($id);
        $category->setName($data->name ?? $category->getName());
        $category->setDescription($data->description ?? $category->getDescription());

        $item = $this->service->update($category);

        return $this->json($item, Response::HTTP_OK);
    }
}
