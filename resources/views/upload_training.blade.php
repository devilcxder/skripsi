<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
    <form action="{{ route('upload.training.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="train">
        <button type="submit">Upload</button>
    </form>
</body>
</html>