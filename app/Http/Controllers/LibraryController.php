<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LibraryController extends Controller
{
    public function index()
    {
        return view('library.index');
    }

    public function show(Request $request)
    {
        try {
            $perPage = 10;
            $page = isset($request->page) ? $request->page : 1;
            $offset = ($page - 1) * $perPage;

            $books = \DB::select("SELECT books.id, books.title, books.publication_at, GROUP_CONCAT(DISTINCT authors.id) AS author_id, GROUP_CONCAT(DISTINCT authors.name) AS authors, GROUP_CONCAT(DISTINCT publishers.id) AS publisher_id, GROUP_CONCAT(DISTINCT publishers.name) AS publishers FROM books
            JOIN book_authors ON books.id = book_authors.book_id
            JOIN authors ON authors.id = book_authors.author_id
            JOIN book_publishers ON books.id = book_publishers.book_id
            JOIN publishers ON publishers.id = book_publishers.publisher_id
            GROUP BY books.id, books.title, books.publication_at
            LIMIT :perPage OFFSET :offset", ['perPage' => $perPage, 'offset' => $offset]);

            $totalCount = \DB::selectOne("SELECT COUNT(*) AS total FROM books")->total;
            $totalPages = ceil($totalCount / $perPage);
            $booksArray = json_decode(json_encode($books), true);

            $view = view('library.listing', [
                'books' => $booksArray,
                'totalPages' => $totalPages,
            ])->render();

            return response()->json(['success' => true, 'view' => $view, 'message' => 'loading success']);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'loading failed']);
        }
    }
}
