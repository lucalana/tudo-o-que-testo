<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Opa foi maneiro legal</h1>
    <p>Produto A</p>
    <p>Produto B</p>
    @foreach ($products as $product)
        <p>{{ $product->title }}</p>
    @endforeach
</body>

</html>
