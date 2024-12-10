<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Purchase;
use App\Enum\PurchaseStatusEnum;
use App\Service\Interface\PurchaseServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PurchaseController extends AbstractController
{
    public function __construct(
        private PurchaseServiceInterface $service,
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

        $purchase = $this->serializer->deserialize(
            $request->getContent(),
            Purchase::class,
            'json'
        );
        $this->service->insert($purchase, $data->customer_id, $data->product_ids);

        return $this->json($purchase, Response::HTTP_CREATED);
    }

    public function update(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $purchase = $this->service->find($id);
        $purchase->setDistance((float) ($data->distance ?? $purchase->getDistance()));
        $purchase->setStatus($data->status ? PurchaseStatusEnum::from($data->status) : $purchase->getStatus());

        $item = $this->service->update($purchase);

        return $this->json($item, Response::HTTP_OK);
    }
}
