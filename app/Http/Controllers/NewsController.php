<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Swagger\Client\Api\DefaultApi;
use Swagger\Client\Configuration;
use Swagger\Client\Model\AIoDNews;

class NewsController extends Controller
{

    public function getNews(): ?View
    {
        // Configure the API host:
        $config = Configuration::getDefaultConfiguration();
        $config->setHost('http://host.docker.internal:8000');

        // Create an instance of the API client:
        $apiInstance = new DefaultApi(new Client(), $config);
        try {
            $response = $apiInstance->listNewsNewsV0Get();
            return view('getNews', ['newsItems' => $response]);
       } catch (Exception $e) {
            // Handle any exceptions that occur during the API request
            echo 'Exception when calling DefaultApi->listNewsNewsV0Get: ', $e->getMessage(), PHP_EOL;
        }

        return null;
    }

    public function postNews(): ?View
    {
        // Configure the API host:
        $config = Configuration::getDefaultConfiguration();
        $config->setHost('http://host.docker.internal:8000');

        // Create an instance of the API client:
        $apiInstance = new DefaultApi(new Client(), $config);

        // Create an instance of AIoDNews:
        $body = new AIoDNews();
        $body->setTitle('Hello World Advanced Edition!');
        $body->setHeadline('Hello World! The AIoD API salutes you!');
        $body->setSection('Hello World News');
        $body->setBody('This is the main body of the Laravel news item.');
        $body->setDateModified(Carbon::now()->toAtomString());
        $body->setWordCount(Str::wordCount($body->getBody()));
        $body->setSource('AIoD API Examples: PHP');
        $body->setBusinessCategories(['Cloud, Edge and Infrastructure']);
        $body->setKeywords(['API', 'Education']);

        try {
            // Submit the data and get the response:
            $result = $apiInstance->newsNewsV0Post($body);

            // Return the view with the response data:
            return view('postNews', [
                'title' => $result->getTitle(),
                'headline' => $result->getHeadline(),
                'section' => $result->getSection(),
                'body' => $result->getBody(),
                'id' => $result->getIdentifier(),
                'date' => (Carbon::createFromDate($result->getDateModified())->toString()),
                // Add more response data as needed
            ]);

        } catch (Exception $e) {
            // Handle any exceptions that occur during the API request
            echo 'Exception when calling DefaultApi->newsNewsV0Post: ', $e->getMessage(), PHP_EOL;
        }

        return null;

    }
}
