<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories  = \App\Category::orderBy('created_at','DESC')->paginate(10);
        $data=[
            'model'=>$categories
        ];
        return view('admin.categories',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
        ]);

        $category = new \App\Category();
        $category->title = $request->get('title');
        $category->save();
        return redirect('admin/categories')->with('success', 'دسته بندی جدید اضافه شد.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = \App\Category::find($id);

        if(empty($category)){
            abort(404);
            return;
        }
        $data = [
            'model'=>$category
        ];
        return view('admin.category_create',$data);
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
        $category = \App\Category::find($id);

        if(empty($category)){
            abort(404);
            return;
        }

        $this->validate($request,[
            'title'=>'required',
        ]);

        $category->title = $request->get('title');
        $category->save();
        return redirect('admin/categories')->with('success', 'دسته بندی ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = \App\Category::find($id);
        if(empty($category)){
            echo "ERROR";
            return;
        }
        $category->delete();
        echo "DESTORY";
        return;
    }
}
