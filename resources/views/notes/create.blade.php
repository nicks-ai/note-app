<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
    <script src="{{ asset('js/create.js') }}" defer></script> <!-- Link to external JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('notes.index') }}" class="back-link">
                <i class="fas fa-chevron-left"></i> Back
            </a>
            <h1>Create Note</h1>
        </div>

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('notes.store') }}" method="POST">
            @csrf
            
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Title" value="{{ old('title') }}"> <!-- Title is nullable -->
            
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" placeholder="Notes" required rows="10" cols="50">{{ old('notes') }}</textarea>
            
            <div id="charCount">0/10000</div> <!-- Display character count -->
            <div id="limitWarning" style="color: red; display: none;">You have reached the 10,000 character limit!</div>
            
            <button type="submit">Create</button>
        </form>
    </div>
</body>
</html>
