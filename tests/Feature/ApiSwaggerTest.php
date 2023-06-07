<?php

namespace Tests\Feature;

use Exception;
use Swagger\Client\Api\DefaultApi;
use Swagger\Client\Configuration;
use Tests\TestCase;

class ApiSwaggerTest extends TestCase
{

    public function testTheSwaggerClassIsLoading()
    {
        // Attempt to load the Swagger class:
        try {
            $config = Configuration::getDefaultConfiguration();
            $swaggerClient = new DefaultApi(null, $config);

            // Assert that the Swagger class is loaded successfully:
            $this->assertInstanceOf(DefaultApi::class, $swaggerClient);
        } catch (Exception $e) {
            // Handle any exceptions that occur during loading:
            $this->fail('Failed to load the Swagger class: ' . $e->getMessage());
        }
    }

}


