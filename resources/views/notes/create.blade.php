<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Note</title>
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Create Note</h1>
            <div class="back-link-container">
                <a href="{{ route('notes.index') }}" class="back-link" title="Back to Notes">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('notes.store') }}" method="POST" class="note-form">
            @csrf
            
            <div class="form-group">
                <div id="limitWarning" style="color: red; display: none;">You have reached the 10,000 character limit!</div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Title" value="{{ old('title') }}" >
                
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" placeholder="Notes" required>{{ old('notes') }}</textarea>
                
                <div id="charCount">0/10000</div>
            </div>
            
            <button type="submit" class="submit-button">Create</button>
            
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notesTextarea = document.getElementById('notes');
            const charCountDisplay = document.getElementById('charCount');
            const limitWarning = document.getElementById('limitWarning');
            const maxChars = 10000;

            const updateCharCount = function() {
                const currentLength = notesTextarea.value.length;
                charCountDisplay.textContent = `${currentLength}/${maxChars}`;
                limitWarning.style.display = currentLength >= maxChars ? 'block' : 'none';
            };

            notesTextarea.addEventListener('input', updateCharCount);
            updateCharCount();
        });
    </script>
</body>
</html>
