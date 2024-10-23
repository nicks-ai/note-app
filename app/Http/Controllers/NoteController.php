<?php

namespace App\Http\Controllers;

use App\Models\Note; 
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function index()
    {
        $notes = Note::all(); 
        return view('notes.index', compact('notes')); 
    }

    
    public function create()
    {
        return view('notes.create'); 
    }


    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'nullable|max:50',
            'notes' => 'required|max:10000' 
        ], [
            'title.max' => 'The title may not be greater than 50 characters.',
            'notes.required' => 'The notes field is required.',
            'notes.max' => 'The notes may not be greater than 10,000 characters.'
        ]);

        
        $title = $request->title ?: 'Untitled';

        
        Note::create([
            'title' => $title,
            'notes' => $request->notes,
        ]);

        return redirect()->route('notes.index')->with('success', 'Note created successfully!');
    }

   
    public function show($id)
    {
        $note = Note::findOrFail($id); 
        return view('notes.view', compact('note')); 
    }

  
    public function edit($id)
    {
        $note = Note::findOrFail($id); 
        return view('notes.edit', compact('note')); 
    }

   
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'title' => 'nullable|max:50', 
            'notes' => 'required|max:10000',
        ], [
            'title.max' => 'The title may not be greater than 50 characters.', 
            'notes.required' => 'The notes field is required.', 
            'notes.max' => 'The notes may not be greater than 10,000 characters.' 
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
