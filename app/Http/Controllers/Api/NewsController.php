<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::all();

        if (count($news) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $news
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $news = News::find($id);

        if (!is_null($news)){
            return response([
                'message' => 'Retrieve News Success',
                'data' => $news
            ], 200);
        }

        return response([
            'message' => 'News Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'title' => 'required|unique:news',
            'date' => 'required|date',
            'text' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $news = News::create($storeData);
        return response([
            'message' => 'Add News Success',
            'data' => $news
        ], 200);
    }

    public function destroy($id)
    {
        $news = News::find($id);

        if (is_null($news)) {
            return response([
                'message' => 'News Not Found',
                'data' => null
            ], 404);
        }

        if ($news->delete()){
            return response([
                'message' => 'Delete News Success',
                'data' => $news
            ], 200);
        }

        return response([
            'message' => 'Delete News Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $news = News::find($id);
        if (is_null($news)){
            return response([
                'message' => 'News Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'title' => ['required', Rule::unique('news')->ignore($news)],
            'date' => 'required|date',
            'text' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $news->title = $updateData['title'];
        $news->date = $updateData['date'];
        $news->text = $updateData['text'];

        if($news->save()) {
            return response([
                'message' => 'Update News Success',
                'data' => $news
            ], 200);
        }

        return response([
            'message' => 'Update News Failed',
            'data' => null,
        ], 400);
    }
}
