<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\ProductFixtures;
use App\DataFixtures\PurchaseFixtures;
use App\DataFixtures\UserFixtures;
use App\Enum\PurchaseStatusEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class PurchaseControllerTest extends WebTestCase
{
    public const string BASE_URL = '/api/purchases';

    public function testGetAListOfPurchasesIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL);

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsArray($responseBody);
        $this->assertCount(4, $responseBody);
        $this->assertEquals(200.41, $responseBody[0]->distance);
        $this->assertEquals('d7aefa19-11df-e1ee-179b-843c4a34ba76', $responseBody[0]->id);
        $this->assertEquals('Casillero del Diablo', $responseBody[1]->products[0]->name);
    }

    public function testGetOnePurchaseByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL . '/' . PurchaseFixtures::ID_1);

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Quinta dos Bons Ventos', $responseBody->products[0]->name);
        $this->assertEquals('d7aefa19-11df-e1ee-179b-843c4a34ba76', $responseBody->id);
    }

    public function testRemoveAPurchaseByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, self::BASE_URL . '/' . PurchaseFixtures::ID_4);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCreateANewPurchaseByIdIsSuccessful(): void
    {
        $id = Uuid::v4()->toString();

        $client = static::createClient();
        $client->request(Request::METHOD_POST, self::BASE_URL,
            content: json_encode([
                'id' => $id,
                'distance' => "10",
                'customer_id' => UserFixtures::ID_1,
                'product_ids' => [
                    ProductFixtures::ID_1,
                ],
            ])
        );

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Quinta dos Bons Ventos', $responseBody->products[0]->name);
        $this->assertEquals(110.0, $responseBody->totalPrice);
        $this->assertEquals($id, $responseBody->id);
    }

    public function testUpdateAPurchaseByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_PATCH, self::BASE_URL . '/' . PurchaseFixtures::ID_3,
            content: json_encode([
                'status' => PurchaseStatusEnum::FINISHED,
            ])
        );

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsObject($responseBody);
        $this->assertEquals(PurchaseStatusEnum::FINISHED->value, $responseBody->status);
        $this->assertEquals(PurchaseFixtures::ID_3, $responseBody->id);
    }
}
