<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <title>All Notes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        .highlight {
            background-color: yellow; /* Highlight color */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const notesList = document.getElementById('notes-list');
            const originalNotes = Array.from(notesList.getElementsByTagName('li')); // Store original notes

            // Search functionality
            searchInput.addEventListener('keyup', () => {
                const query = searchInput.value.toLowerCase();
                const matchingNotes = [];

                originalNotes.forEach(note => {
                    const title = note.querySelector('.note-title');
                    const content = note.querySelector('.note-content');
                    const createdAt = note.querySelector('.note-created-at');

                    // Remove previous highlights
                    title.innerHTML = title.innerHTML.replace(/<span class="highlight">(.*?)<\/span>/g, '$1');
                    content.innerHTML = content.innerHTML.replace(/<span class="highlight">(.*?)<\/span>/g, '$1');
                    createdAt.innerHTML = createdAt.innerHTML.replace(/<span class="highlight">(.*?)<\/span>/g, '$1');

                    const titleText = title.innerText.toLowerCase();
                    const contentText = content.innerText.toLowerCase();
                    const createdAtText = createdAt.innerText.toLowerCase();

                    // Check if the note matches the query
                    if (titleText.includes(query) || contentText.includes(query) || createdAtText.includes(query)) {
                        const highlightedTitle = titleText.replace(new RegExp(`(${query})`, 'gi'), '<span class="highlight">$1</span>');
                        const highlightedContent = contentText.replace(new RegExp(`(${query})`, 'gi'), '<span class="highlight">$1</span>');
                        const highlightedCreatedAt = createdAtText.replace(new RegExp(`(${query})`, 'gi'), '<span class="highlight">$1</span>');

                        title.innerHTML = highlightedTitle;
                        content.innerHTML = highlightedContent;
                        createdAt.innerHTML = highlightedCreatedAt;

                        matchingNotes.push(note); // Add to matching notes
                    }
                });

                // Clear the notes list and append matching notes
                notesList.innerHTML = '';
                matchingNotes.forEach(note => notesList.appendChild(note));
                
                // Reset notes to original state if input is cleared
                if (query === '') {
                    notesList.innerHTML = '';
                    originalNotes.forEach(note => notesList.appendChild(note));
                }
            });

            // Confirm delete functionality
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    const confirmation = confirm("Are you sure you want to delete this note?");
                    if (!confirmation) {
                        event.preventDefault(); // Prevent form submission if not confirmed
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>All Notes</h1>

        <!-- Success message display -->
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}    
            </div>
        @endif

        <input type="text" id="search-input" placeholder="Search notes..." />

        @if ($notes->isNotEmpty())
            <ul id="notes-list">
                @foreach ($notes->sortByDesc('created_at') as $note)
                    <li>
                        <a href="{{ route('notes.show', $note->id) }}" class="note-link">
                            <div class="note-title">{{ $note->title }}</div>
                            <p class="note-created-at">{{ $note->created_at->timezone('Asia/Manila')->format('Y-m-d h:i A') }}</p>
                            <p class="note-content">{{ Str::limit($note->notes, 30) }}</p>  
                        </a>
                        <div class="button-container">
                            <!-- Edit button -->
                            <a href="{{ route('notes.edit', $note->id) }}" class="edit-button" title="Edit Note">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete button -->
                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button" title="Delete Note">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
        @if ($notes->isEmpty())
            <p>No notes available.</p>
        @endif
    </div>
    <div class="fixed-button-container">
        <a href="{{ route('notes.create') }}" class="create-button">
            <i class="fas fa-plus"></i>
        </a>
    </div>
</body>
</html>
