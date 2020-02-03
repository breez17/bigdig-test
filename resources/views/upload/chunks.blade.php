<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload chunks</title>
</head>
<body>
<form action="{{ route('upload.chunks.handler') }}" method="post" id="upload__form">
    @csrf
    <label for="upload__file">Large file</label>
    <input name="file" required id="upload__file" type="file">

    <label for="upload__entity">Entity</label>
    <select name="entity_id" id="upload__entity">
        @foreach($entities as $entity)
            <option value="{{ $entity->id  }}">{{ $entity->name }}</option>
        @endforeach
    </select>

    <div id="upload__progress"></div>

    <button>Upload</button>
</form>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
