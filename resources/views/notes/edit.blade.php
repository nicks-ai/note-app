<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note</title>
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_back,home" />
</head>
<body>
    <div class="container">
        <h1>Edit Note</h1>
        
        <div class="actions">
            <a href="{{ route('notes.show', $note->id) }}">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <a href="{{ route('notes.index') }}">
                <span class="material-symbols-outlined">home</span>
            </a>
        </div>

        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('notes.update', $note->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $note->title) }}" required>
            </div>

            <div>
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" required>{{ old('notes', $note->notes) }}</textarea>
            </div>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
