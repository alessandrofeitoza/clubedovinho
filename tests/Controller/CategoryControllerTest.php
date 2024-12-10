<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\CategoryFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class CategoryControllerTest extends WebTestCase
{
    public const string BASE_URL = '/api/categories';

    public function testGetAListOfCategoriesIsSuccessful(): void
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
        $this->assertEquals('Vinho', $responseBody[0]->name);
        $this->assertEquals('8daefa17-f1d9-46ee-9b70-4843db40376b', $responseBody[0]->id);
        $this->assertEquals('Cachaça', $responseBody[1]->name);
    }

    public function testGetOneCategoryByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL . '/' . CategoryFixtures::ID_1);

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Vinho', $responseBody->name);
        $this->assertEquals('8daefa17-f1d9-46ee-9b70-4843db40376b', $responseBody->id);
    }

    public function testRemoveACategoryByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, self::BASE_URL . '/' . CategoryFixtures::ID_4);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCreateANewCategoryByIdIsSuccessful(): void
    {
        $id = Uuid::v4()->toString();

        $client = static::createClient();
        $client->request(Request::METHOD_POST, self::BASE_URL,
            content: json_encode([
                'id' => $id,
                'name' => 'Category test',
                'description' => 'description test',
            ])
        );

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Category test', $responseBody->name);
        $this->assertEquals('description test', $responseBody->description);
        $this->assertEquals($id, $responseBody->id);
    }

    public function testUpdateACategoryByIdIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_PATCH, self::BASE_URL . '/' . CategoryFixtures::ID_3,
            content: json_encode([
                'name' => 'Category test 2',
            ])
        );

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsObject($responseBody);
        $this->assertEquals('Category test 2', $responseBody->name);
        $this->assertEquals(CategoryFixtures::ID_3, $responseBody->id);
    }
}
