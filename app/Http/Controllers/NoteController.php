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
}
