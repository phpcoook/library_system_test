<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublisherController extends Controller
{
    public function index()
    {
        try {
            $publishers = \DB::select("SELECT * FROM publishers");

            return response()->json([
                'success'       => true,
                'data'          => $publishers,
                'Total Records' => count($publishers)
            ],
                200);
        } catch (\Exception $e) {
            Log::info('File :: '.__FILE__.' Line :: '.__LINE__.' Message :: '
                .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            if (empty($request->name)) {
                throw new \Exception('Publisher Name is required', 404);
            }

            $publisher
                = \DB::select("SELECT * FROM publishers WHERE name = '$request->name'");

            if (!empty($publisher)) {
                throw new \Exception('Same name publisher has already exist',
                    404);
            }

            \DB::table('publishers')->insert([
                'name'       => $request->name,
                'created_at' => \DB::raw('now()'),
                'updated_at' => \DB::raw('now()')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Publisher Added Successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::info('File :: '.__FILE__.' Line :: '.__LINE__.' Message :: '
                .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $author = \DB::select("SELECT * FROM publishers WHERE id = $id");

            return response()->json(['success' => true, 'data' => $author],
                200);
        } catch (\Exception $e) {
            Log::info('File :: '.__FILE__.' Line :: '.__LINE__.' Message :: '
                .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $findPublisher
                = \DB::select("SELECT * FROM publishers WHERE name = '$request->name' AND id != $id");

            if (!empty($findPublisher)) {
                throw new \Exception('Same name publisher has already exist',
                    404);
            }

            \DB::table('publishers')
                ->where('id', $id)
                ->update(['name' => $request->name]);

            return response()->json([
                'success' => true,
                'data'    => 'Publisher Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::info('File :: '.__FILE__.' Line :: '.__LINE__.' Message :: '
                .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            \DB::table('publishers')->where('id', $id)->delete();
            return response()->json([
                'success' => true,
                'data'    => 'Publisher Deleted Successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::info('File :: '.__FILE__.' Line :: '.__LINE__.' Message :: '
                .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function publishBook(Request $request)
    {
        try {
            if (empty($request->name)) {
                throw new \Exception('Publisher name is required', 404);
            } elseif (empty($request->book_title)) {
                throw new \Exception('Please add book', 404);
            } elseif (empty($request->author_name)) {
                throw new \Exception('Please add author', 404);
            } elseif (empty($request->book_publication_at)) {
                throw new \Exception('Please add book published date', 404);
            }

            $authorIds = [];
            $publisherIds = [];

            $publisherNames = implode("','", $request->name);
            $findPublisher
                = \DB::select("SELECT * FROM publishers WHERE name In ('$publisherNames')");
            if (!empty($findPublisher)) {
                throw new \Exception('Same name publisher has already exist',
                    404);
            }

            $authorNames = implode("','", $request->author_name);
            $findAuthor
                = \DB::select("SELECT * FROM authors WHERE name In ('$authorNames')");
            if (!empty($findAuthor)) {
                throw new \Exception('Same name author has already exist', 404);
            }

            $findBook
                = \DB::select("SELECT * FROM books WHERE title = '$request->book_title'");
            if (!empty($findBook)) {
                throw new \Exception('Same name book has already exist', 404);
            }

            $book = \DB::table('books')->insertGetId([
                'title'          => $request->book_title,
                'publication_at' => date('Y-m-d',
                    strtotime($request->book_publication_at)),
                'created_at'     => \DB::raw('now()'),
                'updated_at'     => \DB::raw('now()')
            ]);

            foreach ($request->author_name as $author) {
                $author = \DB::table('authors')->insertGetId([
                    'name'       => $author,
                    'created_at' => \DB::raw('now()'),
                    'updated_at' => \DB::raw('now()')
                ]);
                array_push($authorIds, $author);
            }

            foreach ($request->name as $item) {
                $publisher = \DB::table('publishers')->insertGetId([
                    'name'       => $item,
                    'created_at' => \DB::raw('now()'),
                    'updated_at' => \DB::raw('now()')
                ]);
                array_push($publisherIds, $publisher);
            }

            if (!empty($book) && !empty($authorIds) && !empty($publisherIds)) {

                foreach ($authorIds as $author) {
                    \DB::table('book_authors')->insert([
                        'book_id'   => $book,
                        'author_id' => $author,
                    ]);
                }

                foreach ($publisherIds as $publisher) {
                    \DB::table('book_publishers')->insert([
                        'book_id'      => $book,
                        'publisher_id' => $publisher,
                    ]);
                }
            } else {
                throw new \Exception('SOMETHING WENT WRONG!', 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Book Published Successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::info('File :: '.__FILE__.' Line :: '.__LINE__.' Message :: '
                .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
