<!DOCTYPE html>
<html lang="en">
<head>
    <title>News: {{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #ccc;
        }
        .newspaper, .info {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: "Lucida Console", Courier, monospace;
        }
        .info {
            background-color: #000;
            color: #fff;
            margin-bottom: 12px;
            font-weight: bold;
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

<div class="info">
    The following news item has been submitted to the AIoD API:
</div>

<div class="newspaper">
    <h1>{{ $title }}</h1>
    <h2>{{ $headline }}</h2>
    <h3>Section: {{ $section }}</h3>
    <small>Date posted: {{ $date }}</small><br>
    <small>Unique identifier: {{ $id }}</small>
    <p>{{ $body }}</p>
</div>

</body>
</html>
