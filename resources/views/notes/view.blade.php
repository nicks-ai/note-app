<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Note - {{ $note->title }}</title>
    <link rel="stylesheet" href="{{ asset('css/view.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=fullscreen" />
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this note? This action cannot be undone.");
        }

        // Function to automatically resize the textarea
        document.addEventListener('DOMContentLoaded', () => {
            const textarea = document.getElementById('notes');
            if (textarea) {
                textarea.addEventListener('input', () => {
                    textarea.style.height = 'auto'; // Reset height
                    textarea.style.height = `${textarea.scrollHeight}px`; // Adjust height
                });
            }
        });
    </script>
</head>
<body>
    <div class="outer-container">
        <div class="container">
            <h1>{{ $note->title }}</h1>
            <div class="actions">
                <div class="left-actions">
                    <a href="{{ route('notes.index') }}" title="Back">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <a href="{{ route('notes.edit', $note->id) }}" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
                <div class="right-actions">
                    <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="note-box">
                <p>{{ $note->created_at->format('m/d/Y h:i A') }}</p>
                <p>{{ $note->notes }}</p>
                <textarea id="notes" style="display: none;">{{ $note->notes }}</textarea> <!-- Hidden textarea for resizing -->
            </div>
        </div>
    </div>
</body>
</html>
