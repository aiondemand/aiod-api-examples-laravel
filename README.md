# AIoD API Examples: Laravel

This repository contains a few easy to follow examples build for [Laravel](https://laravel.com/), which consume the AI-on-Demand API ([AIoD API](https://github.com/aiondemand/AIOD-rest-api) version [0.3.20220501](https://github.com/aiondemand/AIOD-rest-api/releases/tag/0.3.20220501)) to send and/or retrieve data. 

## Index

- [Prerequisites](./README.md#prerequisites)
- [Installation](./README.md#installation)
  - Clone, install & run the AIoD API repository
  - Clone the aiod-api-examples-laravel repository
  - Create and install the API client libraries
  - Install the Laravel project
- [Testing](./README.md#testing)
- [Creating a function that posts a News item to the API](./README.md#creating-a-function-that-posts-a-news-item)
- [Retrieve all the News items from the API](./README.md#retrieve-all-the-news-items-from-the-api)
- [See all the methods in action](./README.md#see-all-the-methods-in-action)

## Prerequisites

- Some experience with the Laravel PHP framework
- A local development environment which allows the execution of PHP code. [DDEV](https://ddev.com/) is strongly recommended ([installation guide](https://ddev.readthedocs.io/en/latest/users/install/ddev-installation/))
- [Docker](https://www.docker.com/) to install & run the AI-on-Demand API ([AIoD API](https://github.com/aiondemand/AIOD-rest-api)) & DDEV

## Installation

### 1. Clone, install & run the AIoD API repository

Clone the [AIoD API repository](https://github.com/aiondemand/AIOD-rest-api) to your local machine:
```shell
git clone git@github.com:aiondemand/AIOD-rest-api.git
```

Change to the newly created folder:
```shell
cd AIOD-rest-api
```

Switch to version 0.3.20220501 of the API:
```shell
git checkout tags/0.3.20220501 -b 0.3.20220501
```

Make sure that Docker is running on your machine and then install the AIoD API by following the [installation instructions](https://github.com/aiondemand/AIOD-rest-api/blob/0.3.20220501/README.md#installation).
```shell
docker network create sql-network
docker run -e MYSQL_ROOT_PASSWORD=ok --name sqlserver --network sql-network -d mysql
docker build --tag ai4eu_server_demo:latest -f Dockerfile .
docker run --network sql-network -it -p 8000:8000 --name apiserver ai4eu_server_demo
```

>**TIP**: *There's no reason to repeat this process after the initial installation, as you can start the AIoD API by running `docker start sqlserver` followed by `docker start apiserver`.*

At this point you should be able to access the API using your web browser at [localhost:8000](http://localhost:8000). This instance operates at your own machine independently of the actual AIoD API, ensuring that real data is not accidentally modified or corrupted. In other words, it's the ideal playground for development or testing!

---

### 2. Clone the 'AIoD API Examples: Laravel' repository

Assuming you are still at the `AIOD-rest-api` folder, change to the parent directory:

```shell
cd ..
````

Clone the aiod-api-examples-laravel repository to your local machine by running the following command:
```shell
git clone git@github.com:aiondemand/aiod-api-examples-laravel.git
```

An `aiod-api-examples-laravel` folder will be created with the contents of this repository.

Change to the newly created folder:
```shell
cd aiod-api-examples-laravel
```

### 3. Create and install the API client libraries with Swagger Codegen

Using your web browser:

- **Get the OpenAPI Spec for the AIoD API:** Download the OpenAPI Spec for your local instance of the AIoD API via [localhost:8000/openapi.json](http://localhost:8000/openapi.json). You can save it at any location that is convenient for you to find and access the file.
- **Create the API client libraries with Swagger Codegen:** Use the online tool at [editor.swagger.io](https://editor.swagger.io/) to process the `openapi.json` file, which is required for the creation of the libraries:
  - Go to [editor.swagger.io](https://editor.swagger.io/) using your web browser. 
  - Click on **File > Import File**. 
  - Select & upload the `openapi.json` file that you've previously downloaded from the AIoD API. 
  - Generate the client for PHP using the **Generate Client > php** menu option. 
  - Download the `php-client-generated.zip` file and **save it on the [app/Api](./app/Api) folder of this repository** (`/app/Api/php-client-generated.zip`).

Now, go back to the CLI and assuming you are still at the root of this repository (the `aiod-api-examples-laravel` folder), **change** to the [app/Api](./app/Api) subdirectory:
```shell
cd app/Api
```

**Unzip** the contents of the downloaded `php-client-generated.zip` archive:
```shell
unzip php-client-generated.zip
```

After the extraction is complete, the `SwaggerClient-php` will be created under [app/Api](./app/Api), which contains all the required libraries that we are going to use.

Go back to the root folder of this repository:
```shell
cd ../..
```

### 4. Install the Laravel project

It is strongly recommended to use [DDEV](https://ddev.com/) to quickly set up and initialise this Laravel project. Alternatively you can follow your preferred method of installing this Laravel project, which involves running composer and manually setting up an env file and generating your environment key.

#### Installing the project via DDEV.

Simply run the following chain of commands at the root folder of this repository:

```shell
ddev config &&
ddev composer install &&
ddev exec "php artisan key:generate" &&
```

### Testing

Time for some testing using Laravel's built-in test features, to "assert" if the installation was successful and everything is running as expected.

>**NOTE:** (If you have not installed this Laravel project using DDEV, please make sure to replace the `ddev` command with `php` on all the following prompts. Keep in mind though that most of the tests have been created with the assumption that Laravel is running under DDEV and the API containers are accessible via http://host.docker.internal:8000).

Test if the SwaggerClient-php has been installed correctly:
```shell
ddev artisan test --filter ApiSwaggerTest 
```
If the Swagger class can load successfully using the default configuration, this is the expected outcome:
```
  PASS  Tests\Feature\ApiSwaggerTest
  ✓ the swagger class is loading
```

Test if the API docker containers are accessible:
```shell
ddev artisan test --filter ApiDockerTest 
```
```
  PASS  Tests\Feature\ApiDockerTest
  ✓ the api docker containers are accessible
```

### Creating a function that posts a News item

We are going to use the installed Swagger libraries to create a simplified page that creates and submits an object that represents a News item to the AIoD API, while parsing and displaying the response. Since this is not a real app, we are simply going to utilise a controller to create the object and post it directly to the API, while the response is going to be displayed on a View with a basic Blade template.

First, let's add the path to [routes/web.php](./routes/web.php):

```php
Route::get('/post/news', [NewsController::class, 'postNews'])->name('post.news');
```

Then, let's create a `postNews` method that resides under the **NewsController** class at [app/Http/Controllers/NewsController](./app/Http/Controllers/NewsController.php):

```php
public function postNews(): View
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
}
```

Finally, let's create a View at [resources/views/postNews.blade.php](./resources/views/postNews.blade.php) that confirms the successful posting of a News item via the AIoD API.

```html
<div class="newspaper">
    <h1>{{ $title }}</h1>
    <h2>{{ $headline }}</h2>
    <h3>Section: {{ $section }}</h3>
    <small>Date posted: {{ $date }}</small><br>
    <small>Unique identifier: {{ $id }}</small>
    <p>{{ $body }}</p>
</div>
```

Please make sure to check the linked files for the code of the fully working example.

### Retrieve all the News items from the API

In a similar way, the [NewsController](./app/Http/Controllers/NewsController.php) can be extended to also allow retrieving News items directly from the API:

```php
public function getNews(): View
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
}
```

Then, with an addition of an additional [route](./routes/web.php) and a [getNews Blade template](./resources/views/getNews.blade.php), the user of our app can easily read all the News items which are stored in the AIoD API.

Again, please make sure to check all the linked fields for the code of the fully working example.

### See all the methods in action

If you are using DDEV you can see all the described methods in action by visiting https://aiod-api-examples-laravel.ddev.site/ on your web browser. Alternatively, type the `npm run dev` command on your terminal to run the Laravel project in dev mode. 

