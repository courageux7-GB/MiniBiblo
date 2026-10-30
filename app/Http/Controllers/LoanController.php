<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a list of active loans.
     * Supports optional filtering by member via query parameter ?member_id=.
     */
    public function index(Request $request)
    {
        $query = Loan::whereNull('return_date');
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->input('member_id'));
        }
        $loans = $query->with(['book', 'member'])->orderBy('due_date')->get();
        $members = Member::all();
        return view('loans.index', compact('loans', 'members'));
    }

    /**
     * Mark a loan as returned.
     */
    public function return(Request $request, Loan $loan)
    {
        if ($loan->return_date) {
            return back()->with('error', 'Cet emprunt est déjà retourné');
        }
        $loan->return_date = now();
        $loan->save();
        // Increment available copies of the book
        Book::where('id', $loan->book_id)->increment('available_copies');
        return back()->with('success', 'Livre retourné avec succès');
    }
}
