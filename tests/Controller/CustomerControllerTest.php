<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class CustomerControllerTest extends WebTestCase
{
    public const string BASE_URL = '/api/customers';

    public function testGetAListOfCustomersIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL);

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsArray($responseBody);
        $this->assertCount(3, $responseBody);
        $this->assertEquals('Alessandro Feitoza', $responseBody[0]->user->name);
        $this->assertEquals('1d17aefa-1f9d-3ee1-a79b-84c34a4b6137', $responseBody[0]->id);
        $this->assertEquals('1d17aefa-1f9d-3ee1-a79b-84c34a4b6137', $responseBody[0]->user->id);
        $this->assertEquals('Chico Caucaia', $responseBody[1]->user->name);
    }

    public function testGetOneCustomerByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL . '/' . UserFixtures::ID_1);

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Alessandro Feitoza', $responseBody->user->name);
        $this->assertEquals('1d17aefa-1f9d-3ee1-a79b-84c34a4b6137', $responseBody->id);
    }

    public function testRemoveACustomerByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, self::BASE_URL . '/' . UserFixtures::ID_3);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCreateANewCustomerByIdIsSuccessful(): void
    {
        $id = Uuid::v4()->toString();

        $client = static::createClient();
        $client->request(Request::METHOD_POST, self::BASE_URL,
            content: json_encode([
                'user' => [
                    'id' => $id,
                    'name' => 'Customer test',
                    'email' => 'customer@test.com',
                    'password' => 'password',
                ],
                'phone' => '8591231231',
            ])
        );

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Customer test', $responseBody->user->name);
        $this->assertEquals('8591231231', $responseBody->phone);
        $this->assertEquals($id, $responseBody->id);
        $this->assertEquals($id, $responseBody->user->id);
    }

    public function testUpdateACustomerByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_PATCH, self::BASE_URL . '/' . UserFixtures::ID_3,
            content: json_encode([
                'phone' => '88901010101',
            ])
        );

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsObject($responseBody);
        $this->assertEquals('88901010101', $responseBody->phone);
        $this->assertEquals(UserFixtures::ID_3, $responseBody->id);
    }
}
