<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\CountryFixtures;
use App\DataFixtures\ProductFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class ProductControllerTest extends WebTestCase
{
    public const string BASE_URL = '/api/products';

    public function testGetAListOfProductsIsSuccessful(): void
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
        $this->assertEquals('Quinta dos Bons Ventos', $responseBody[0]->name);
        $this->assertEquals('17aefa1d-1df9-31ee-ab79-c8434b4a3761', $responseBody[0]->id);
        $this->assertEquals('Casillero del Diablo', $responseBody[1]->name);
    }

    public function testGetOneProductByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL . '/' . ProductFixtures::ID_1);

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Quinta dos Bons Ventos', $responseBody->name);
        $this->assertEquals('17aefa1d-1df9-31ee-ab79-c8434b4a3761', $responseBody->id);
    }

    public function testRemoveAProductByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, self::BASE_URL . '/' . ProductFixtures::ID_4);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCreateANewProductByIdIsSuccessful(): void
    {
        $id = Uuid::v4()->toString();

        $client = static::createClient();
        $client->request(Request::METHOD_POST, self::BASE_URL,
            content: json_encode([
                'id' => $id,
                'price' => "99.91",
                'category_id' => CategoryFixtures::ID_3,
                'country_id' => CountryFixtures::ID_2,
                'name' => 'Cachaça Caranguejo',
                'weight' => 350,
            ])
        );

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Cachaça Caranguejo', $responseBody->name);
        $this->assertEquals(99.91, $responseBody->price);
        $this->assertEquals($id, $responseBody->id);
    }

    public function testUpdateAProductByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_PATCH, self::BASE_URL . '/' . ProductFixtures::ID_3,
            content: json_encode([
                'price' => 0.01,
            ])
        );

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsObject($responseBody);
        $this->assertEquals(0.01, $responseBody->price);
        $this->assertEquals(ProductFixtures::ID_3, $responseBody->id);
    }
}
