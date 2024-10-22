<?php

namespace App\Http\Controllers;

use App\Models\Note; // Ensure you have the Note model
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // Display a listing of the notes
    public function index()
    {
        $notes = Note::all(); // Fetch all notes
        return view('notes.index', compact('notes')); // Pass notes to the view
    }

    // Show the form for creating a new note
    public function create()
    {
        return view('notes.create'); // Return the create view
    }

    // Store a newly created note in the database
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'nullable|max:50', // Allow title to be nullable
            'notes' => 'required|max:10000' // Ensure notes are required
        ], [
            'title.max' => 'The title may not be greater than 50 characters.', // Custom message for title limit
            'notes.required' => 'The notes field is required.', // Custom message for required notes
            'notes.max' => 'The notes may not be greater than 10,000 characters.' // Custom message for notes limit
        ]);

        // Set title to 'Untitled' if not provided
        $title = $request->title ?: 'Untitled';

        // Create a new note
        Note::create([
            'title' => $title,
            'notes' => $request->notes, // Save the content as 'notes'
        ]);

        return redirect()->route('notes.index')->with('success', 'Note created successfully!'); // Redirect with a success message
    }

    // Display a specific note
    public function show($id)
    {
        $note = Note::findOrFail($id); // Find the note by ID
        return view('notes.view', compact('note')); // Pass the note to the show view
    }

    // Show the form for editing a specific note
    public function edit($id)
    {
        $note = Note::findOrFail($id); // Find the note by ID
        return view('notes.edit', compact('note')); // Pass the note to the edit view
    }

    // Update a specific note in the database
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'nullable|max:50', // Title is optional
            'notes' => 'required|max:10000', // Ensure notes are required
        ], [
            'title.max' => 'The title may not be greater than 50 characters.', // Custom message for title limit
            'notes.required' => 'The notes field is required.', // Custom message for required notes
            'notes.max' => 'The notes may not be greater than 10,000 characters.' // Custom message for notes limit
        ]);

        $note = Note::findOrFail($id); // Find the note by ID

        // Set title to 'Untitled' if not provided
        $title = $request->title ?: 'Untitled';

        // Update the note with request data
        $note->update([
            'title' => $title,
            'notes' => $request->notes,
        ]);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully!'); // Redirect with a success message
    }

    // Delete a specific note from the database
    public function destroy($id)
    {
        $note = Note::findOrFail($id); // Find the note by ID
        $note->delete(); // Delete the note

        return redirect()->route('notes.index')->with('success', 'Note deleted successfully!'); // Redirect with a success message
    }

    // Delete all notes from the database
    public function destroyAll()
    {
        Note::truncate(); // Remove all notes
        return redirect()->route('notes.index')->with('success', 'All notes deleted successfully!'); // Redirect with a success message
    }

    // Delete selected notes from the database
    public function destroySelected(Request $request)
    {
        $selectedNotes = $request->input('selected_notes'); // Get selected notes from the request

        if ($selectedNotes && is_array($selectedNotes)) {
            Note::destroy($selectedNotes); // Delete the selected notes
            return redirect()->route('notes.index')->with('success', 'Selected notes have been deleted successfully.');
        }

        return redirect()->route('notes.index')->with('error', 'No notes were selected for deletion.'); // Redirect with error if no notes are selected
    }
}
