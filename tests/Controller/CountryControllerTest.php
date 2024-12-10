<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryControllerTest extends WebTestCase
{
    public const string BASE_URL = '/api/countries';

    public function testGetListOfCountriesIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL);

        $responseBody = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsArray($responseBody);
        $this->assertCount(6, $responseBody);
        $this->assertEquals('Argentina', $responseBody[0]->name);
        $this->assertEquals('Brasil', $responseBody[1]->name);
    }
}
