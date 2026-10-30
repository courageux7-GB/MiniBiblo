<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'total_copies' => 'required|integer|min:1',
        ]);
        $validated['available_copies'] = $validated['total_copies'];
        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Livre ajouté avec succès');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'total_copies' => 'required|integer|min:1',
        ]);
        // Adjust available_copies if total_copies changed
        if ($validated['total_copies'] < $book->available_copies) {
            $book->available_copies = $validated['total_copies'];
        }
        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Livre mis à jour');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Livre supprimé');
    }

    // Borrow a book – show form
    public function borrowForm(Book $book)
    {
        $members = \App\Models\Member::all();
        return view('books.borrow', compact('book', 'members'));
    }

    // Store loan
    public function borrow(Request $request, Book $book)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);
        if ($book->available_copies < 1) {
            return back()->with('error', 'Aucun exemplaire disponible');
        }
        $borrowDate = now();
        $dueDate = $borrowDate->copy()->addDays(14);
        \App\Models\Loan::create([
            'book_id' => $book->id,
            'member_id' => $validated['member_id'],
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
        ]);
        $book->decrement('available_copies');
        return redirect()->route('books.index')->with('success', 'Emprunt enregistré');
    }
}
