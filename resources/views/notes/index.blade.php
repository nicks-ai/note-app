<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <title>All Notes</title>
    <script src="{{ asset('js/index.js') }}" defer></script>
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
            const selectAllCheckbox = document.getElementById('select-all');
            const selectButton = document.getElementById('select-button');
            const deleteButton = document.getElementById('delete-button');
            const noteCheckboxes = document.querySelectorAll('.note-checkbox');
            const selectIcons = document.querySelectorAll('.select-icon');
            const selectAllLabel = document.getElementById('select-all-label');
            const originalNotes = Array.from(notesList.getElementsByTagName('li')); // Store original notes

            deleteButton.style.display = 'none'; // Hide delete button initially
            selectAllCheckbox.style.display = 'none'; // Keep the select all checkbox hidden
            selectAllLabel.style.display = 'none'; // Keep the select all label hidden
            noteCheckboxes.forEach(checkbox => checkbox.style.display = 'none'); // Hide note checkboxes initially

            // Search functionality
            searchInput.addEventListener('keyup', () => {
                const query = searchInput.value.toLowerCase();
                const matchingNotes = [];
                const remainingNotes = [];

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
                    } else {
                        remainingNotes.push(note); // Add to remaining notes
                    }
                });

                // Clear the notes list and append matching notes first
                notesList.innerHTML = '';
                matchingNotes.forEach(note => notesList.appendChild(note));
                remainingNotes.forEach(note => notesList.appendChild(note));
                
                // Reset notes to original state if input is cleared
                if (query === '') {
                    notesList.innerHTML = '';
                    originalNotes.forEach(note => notesList.appendChild(note));
                }
            });

            // Handle select button click
            selectButton.addEventListener('click', () => {
                selectAllCheckbox.style.display = 'inline-block'; // Show select all checkbox
                selectAllLabel.style.display = 'inline-block'; // Show the label for select all
                selectAllCheckbox.checked = false; // Reset select all checkbox
                selectIcons.forEach(icon => icon.style.display = 'inline-block'); // Show select icons
                noteCheckboxes.forEach(checkbox => checkbox.style.display = 'inline-block'); // Show note checkboxes
                deleteButton.style.display = 'block'; // Show delete button
            });

            // Handle select all checkbox
            selectAllCheckbox.addEventListener('change', () => {
                const isChecked = selectAllCheckbox.checked;
                noteCheckboxes.forEach(noteCheckbox => {
                    noteCheckbox.checked = isChecked; // Set each checkbox to the state of the select all checkbox
                });
                deleteButton.style.display = isChecked ? 'block' : 'none'; // Show delete button if any checkbox is checked
            });

            // Individual note checkbox change
            noteCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const anyChecked = Array.from(noteCheckboxes).some(cb => cb.checked);
                    deleteButton.style.display = anyChecked ? 'block' : 'none'; // Show or hide delete button based on checked state
                });
            });

            // Hide select all when clicking outside
            document.addEventListener('click', (event) => {
                const selectButtonClicked = event.target.closest('#select-button');
                const selectAllCheckboxClicked = event.target.closest('#select-all') || event.target.closest('#select-all-label');

                if (!selectButtonClicked && !selectAllCheckboxClicked) {
                    // Reset all states to default
                    selectAllCheckbox.style.display = 'none'; // Hide select all checkbox
                    selectAllLabel.style.display = 'none'; // Hide select all label
                    noteCheckboxes.forEach(checkbox => {
                        checkbox.style.display = 'none'; // Hide note checkboxes
                        checkbox.checked = false; // Uncheck all note checkboxes
                    });
                    selectIcons.forEach(icon => {
                        icon.style.display = 'none'; // Hide select icons
                    });
                    deleteButton.style.display = 'none'; // Hide delete button
                }
            });
        });

        // Confirmation for delete
        function confirmDelete() {
            return confirm("Are you sure you want to delete all selected notes? This action cannot be undone.");
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>All Notes</h1>

        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <button type="button" id="delete-button" class="delete-button" onclick="if(confirmDelete()) { document.getElementById('bulk-actions-form').submit(); }">
                <i class="fas fa-trash"></i> Delete Selected
            </button>
            <input type="text" id="search-input" placeholder="Search notes..." style="padding: 10px; width: 100%; max-width: 400px;" />
            <button id="select-button" class="select-button" style="margin-left: 20px;">
                <i class="fas fa-check"></i> Select
            </button>
        </div>

        <div>
            <input type="checkbox" id="select-all" class="hidden-checkbox" style="display: none;"> <!-- Hidden Select All checkbox -->
            <label for="select-all" id="select-all-label" class="select-all-label" style="display: none;">Select All</label> <!-- Select All Label -->
        </div>

        @if ($notes->isNotEmpty())
            <form id="bulk-actions-form" method="POST" action="{{ route('notes.destroySelected') }}" class="bulk-actions-form">
                @csrf
                <ul id="notes-list">
                    @foreach ($notes->sortByDesc('created_at') as $note)
                        <li>
                            <input type="checkbox" class="note-checkbox hidden-checkbox" name="selected_notes[]" value="{{ $note->id }}" style="display: none;">
                            <i class="fas fa-check select-icon" style="display: none;"></i>
                            <a href="{{ route('notes.show', $note->id) }}" style="text-decoration: none; color: inherit;" class="note-link">
                                <div class="note-title">{{ $note->title }}</div>
                                <p class="note-created-at">{{ $note->created_at->format('Y-m-d h:i A') }}</p>
                                <p class="note-content">{{ Str::limit($note->notes, 30) }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </form>
        @endif

        @if ($notes->isEmpty())
            <p>No notes available.</p>
        @endif
    </div>

    <div class="fixed-button-container" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 1000;">
        <a href="{{ route('notes.create') }}" class="create-button" style="display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; border-radius: 50%; background-color: green; color: white; text-decoration: none; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
            <i class="fas fa-plus" style="font-size: 24px;"></i>
        </a>
    </div>
</body>
</html>
