<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Swagger\Client\Api\DefaultApi;
use Swagger\Client\ApiException;
use Swagger\Client\Configuration;
use Tests\TestCase;

class ApiDockerTest extends TestCase
{
    public function testTheApiDockerContainersAreAccessible()
    {
        $config = Configuration::getDefaultConfiguration();
        $config->setHost('http://host.docker.internal:8000');
        // Attempt to access the API:
        try {
            $apiInstance = new DefaultApi(new Client(), $config);
            // Send a request to the API
            $response = $apiInstance->homeGetWithHttpInfo();
            $this->assertStringContainsString('<!DOCTYPE html>', $response[0]);
            $this->assertEquals(200, $response[1]);
            $this->assertArrayHasKey('server', $response[2]);
            $this->assertEquals('uvicorn', $response[2]['server'][0]);

        } catch (ApiException $e) {
            // Handle any exceptions that occur during the API request
            $this->fail('Failed to access the API: ' . $e->getMessage());
        }
    }

}
