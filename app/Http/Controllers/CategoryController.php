<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rules = array(
            'perPage' => 'required|int',
            'page' => 'required|int',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ['status' => 'error', 'message' => $validator->errors()->first()];
        }
        try{
            $perPage = $request->perPage ?? 10;
            $page = $request->page ?? 1;
            $search = $request->categoryName;
            $categories = Category::when($search, function ($q) use($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'page', $page);
            return response()->json(['status' => 'success', 'categories' => $categories],200);
        } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);
            return response()->json(['status' => 'error', 'message' => $message], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string|max:125|unique:categories',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }
        try {
            DB::transaction(function () use ($request) {
                Category::create([
                    'name' => $request->name,
                ]);
            });
            return response()->json(['status' => 'success', 'message' => 'Category stored successfully'], 200);
        } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);
            return response()->json(['status' => 'error', 'message' => $message], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ['id' => $id];
        $rules = array(
            'id' => 'required|int|exists:categories,id',
        );
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }
        try {

            $category = Category::find($id);
            return response()->json(['category' => $category], 200);
        } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);
            return response()->json(['status' => 'error', 'message' => $message], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = array_merge(['id' => $id], $request->all());

        $rules = array(
            'id' => 'required|int|exists:categories,id',
            'name' => 'required|string|max:125',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        try {
            $category = Category::find($id);
            $category->name = $request->name;
            $category->save();

            return response()->json(['status' => 'success', 'message' => 'Category updated successfully', 'category' => $category], 200);
        } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);
            return response()->json(['status' => 'error', 'message' => $message], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|int|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            DB::transaction(function () use ($id) {
                Category::findOrFail($id)->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);

            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], 500);
        }
    }
}
