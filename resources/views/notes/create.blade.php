<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Note</title>
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        /* Add this CSS for the textarea to be responsive */
        textarea {
            width: 100%; /* Full width */
            padding: 10px; /* Padding for inputs */
            border: 1px solid #ccc; /* Border for inputs */
            border-radius: 4px; /* Rounded corners */
            font-size: 1rem; /* Responsive font size */
            min-height: 20vh; /* Minimum height */
            max-height: 50vh; /* Maximum height */
            resize: none; /* Disable manual resizing */
            overflow-y: auto; /* Allow vertical scrolling */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Note</h1>
        <div class="back-link-container">
            <a href="{{ route('notes.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> 
            </a>
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

        <form action="{{ route('notes.store') }}" method="POST" class="note-form">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Title" value="{{ old('title') }}">
            </div>
            
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" placeholder="Notes" required>{{ old('notes') }}</textarea>
            </div>
            
            <div id="charCount">0/10000</div> <!-- Display character count -->
            <div id="limitWarning" style="color: red; display: none;">You have reached the 10,000 character limit!</div>
            
            <button type="submit" class="submit-button">Create</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notesTextarea = document.getElementById('notes');
            const charCountDisplay = document.getElementById('charCount');
            const limitWarning = document.getElementById('limitWarning');
            const maxChars = 10000;

            // Function to update the character count
            const updateCharCount = function() {
                const currentLength = notesTextarea.value.length;
                charCountDisplay.textContent = `${currentLength}/${maxChars}`;

                if (currentLength >= maxChars) {
                    limitWarning.style.display = 'block';
                } else {
                    limitWarning.style.display = 'none';
                }

                // Auto-resize the textarea
                notesTextarea.style.height = 'auto'; // Reset height
                notesTextarea.style.height = notesTextarea.scrollHeight + 'px'; // Set height to scrollHeight
            };

            // Add event listener to count characters and adjust height as the user types
            notesTextarea.addEventListener('input', updateCharCount);

            // Initialize character count and height when page loads
            updateCharCount();
        });
    </script>
</body>
</html>
