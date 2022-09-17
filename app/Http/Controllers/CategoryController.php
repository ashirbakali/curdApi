<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return $this->sendResponse($categories, 'categories retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
            'cat_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $request['image'] = $this->upload('category',$request->file('cat_image'));
        $category = Category::create($request->all());
        return $this->sendResponse($category, 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category not found.',[],404);
        }
        return $this->sendResponse($category, 'Category retrieved successfully.');
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
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category not found.',[],404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,'.$category->id,
            'cat_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),404);
        }
        $request['image'] = $this->upload('category',$request->file('cat_image'));

        $category->update($request->all());

        return $this->sendResponse($category, 'category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category not found.',[],404);
        }
        $category->delete();

        return $this->sendResponse([], 'Category deleted successfully.');
    }
}
