<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthorController extends Controller
{
    public function index()
    {
        try {
            $authors = \DB::select("SELECT * FROM authors");

            return response()->json(['success' => true, 'data' => $authors, 'Total Records' => count($authors)], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            if (empty($request->name)) {
                throw new \Exception('name is required', 404);
            }

            $author = \DB::select("SELECT * FROM authors WHERE name = '$request->name'");

            if(!empty($author)) {
                throw new \Exception('Same name author has already exist', 404);
            }

            \DB::table('authors')->insert([
                'name' => $request->name,
                'created_at' => \DB::raw('now()'),
                'updated_at' => \DB::raw('now()')
            ]);

            return response()->json(['success' => true, 'message' => 'Author Added Successfully'], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $author = \DB::select("SELECT * FROM authors WHERE id = $id");

            return response()->json(['success' => true, 'data' => $author], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info($request->all());
            $author = \DB::select("SELECT * FROM authors WHERE name = '$request->name' AND id != $id");

            if(!empty($author)) {
                throw new \Exception('Same name author has already exist', 404);
            }

            \DB::table('authors')
                ->where(['id' => $id])
                ->update([
                    'name' => $request->name,
                    'updated_at' => \DB::raw('now()')
                ]);

            return response()->json(['success' => true, 'data' => 'Author Updated Successfully'], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage(). ' Trace :: '.$e->getTraceAsString());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            \DB::table('authors')->where('id', $id)->delete();
            return response()->json(['success' => true, 'data' => 'Author Deleted Successfully'], 200);
        } catch (\Exception $e) {
            Log::info('File :: ' . __FILE__ . ' Line :: ' . __LINE__ . ' Message :: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }
}
