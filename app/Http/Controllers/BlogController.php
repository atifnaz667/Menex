<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\ConvertToHtml;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'Blogs';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $data =  ConvertToHtml::convertEditorJSBlockToHTML(json_decode($request->data));

        // $blog =  new Blog;
        // $blog->user_id = Auth::user()->id;
        // $blog->content = $data;
        // $blog->title = '';
        // $blog->save();

        $getBlog = Blog::first();
        $data =  ConvertToHtml::convertHTMLToEditorJS($getBlog->content);
        return response()->json(['status' => 'success', 'message'=>'Blog saved successfully', 'data' => $data], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function uploadBlogImage(Request $req)
    {
        $rules = array(
            'image' => 'required|mimes:jpg,jpeg,png',
        );
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return ['status' => 'error', 'message' => $validator->errors()->first()];
        }
        try {
            $picName = time().'.'.$req->image->extension();
            $targetDir = "assets/img/uploads/";
            $path = $targetDir . $picName;
            move_uploaded_file($_FILES['image']['tmp_name'], $path);
            $filePath = request()->root() . "/" . $path;

            return response()
            ->json(['success' => 1,
                'file' => [
                    'url'=>$filePath
                ]
            ],
            200);
        } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);
            return response()->json(['status' => 'error', 'message' => $message], 500);
        }
    }
}
