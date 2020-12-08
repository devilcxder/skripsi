<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Mining</title>
</head>

<body>
    <form action="{{ route('create.model') }}" method="POST">
        @csrf
        <input type="number" required name="pembagian" min="0" value="0" step=".01">
        <input type="text" name="model_name">
        <button type="submit">Create Model</button>
    </form>
</body>

</html>