<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Service\Interface\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductServiceInterface $service,
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
        $data = json_decode($request->getContent());

        $product = $this->serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json'
        );
        $this->service->insert($product, $data->category_id, $data->country_id);

        return $this->json($product, Response::HTTP_CREATED);
    }

    public function update(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $product = $this->service->find($id);
        $product->setPrice((float) ($data->price ?? $product->getPrice()));
        $product->setWeight((int) ($data->weight ?? $product->getWeight()));
        $product->setAdditionalInfo($data->additionalInfo ?? $product->getAdditionalInfo());
        $product->setName($data->name ?? $product->getName());

        $item = $this->service->update($product);

        return $this->json($item, Response::HTTP_OK);
    }
}
