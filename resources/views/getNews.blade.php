<!DOCTYPE html>
<html lang="en">
<head>
    <title>Read the latest news!</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #ccc;
        }
        .newspaper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: "Lucida Console", Courier, monospace;
        }
        .newspaper.item {
            margin-top: 20px;
        }
        .newspaper h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .newspaper h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .newspaper h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .newspaper small {
            font-size: 12px;
            color: #777;
        }
        .newspaper p {
            margin-top: 40px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

@foreach ($newsItems as $newsItem)
    <div class="newspaper item">
        <h1>{{ $newsItem->getTitle() }}</h1>
        <h2>{{ $newsItem->getHeadline() }}</h2>
        <h3>Section: {{ $newsItem->getSection() }}</h3>
        <small>Date posted: {{ $newsItem->getDateModified()->format('Y-m-d, H:i:s') }}</small><br />
        <small>Unique identifier: {{ $newsItem->getIdentifier() }}</small>
        <p>{{ $newsItem->getBody() }}</p>
    </div>
@endforeach

</body>
</html>
