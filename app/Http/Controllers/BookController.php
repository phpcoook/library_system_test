<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index()
    {
        try {
            $books = \DB::select("SELECT * FROM books");

            return response()->json(['success' => true, 'data' => $books, 'Total Records' => count($books)], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            if (empty($request->title)) {
                throw new \Exception('Title is required', 404);
            }

            $book = \DB::select("SELECT * FROM books WHERE title = '$request->title'");

            if(!empty($book)) {
                throw new \Exception('Same name book has already exist', 404);
            }

            \DB::table('books')->insert([
                'title' => $request->title,
                'publication_at' => date('Y-m-d', strtotime($request->publication_at)),
                'created_at' => \DB::raw('now()'),
                'updated_at' => \DB::raw('now()')
            ]);

            return response()->json(['success' => true, 'message' => 'Book Added Successfully'], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $book = \DB::select("SELECT * FROM books WHERE id = $id");

            return response()->json(['success' => true, 'data' => $book], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $book = \DB::select("SELECT * FROM books WHERE title = '$request->title' AND id != $id");

            if(!empty($book)) {
                throw new \Exception('Same name book has already exist', 404);
            }

            \DB::table('books')
                ->where('id', $id)
                ->update(['title' => $request->title]);

            return response()->json(['success' => true, 'data' => 'Book Updated Successfully'], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage(). ' Trace :: '.$e->getTraceAsString());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            \DB::table('books')->where('id', $id)->delete();
            return response()->json(['success' => true, 'data' => 'Book Deleted Successfully'], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }
}
