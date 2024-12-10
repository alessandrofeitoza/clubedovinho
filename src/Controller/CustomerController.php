<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use App\Service\Interface\CustomerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerController extends AbstractController
{
    public function __construct(
        private CustomerServiceInterface $service,
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
        $customer = $this->serializer->deserialize(
            $request->getContent(),
            Customer::class,
            'json'
        );
        $this->service->insert($customer);

        return $this->json($customer, Response::HTTP_CREATED);
    }

    public function update(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $customer = $this->service->find($id);
        $customer->setPhone($data->phone ?? $customer->getPhone());

        $item = $this->service->update($customer);

        return $this->json($item, Response::HTTP_OK);
    }
}
