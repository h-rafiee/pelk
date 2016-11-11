<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags  = \App\Tag::orderBy('created_at','DESC')->paginate(10);
        $data=[
            'model'=>$tags,
        ];
        return view('admin.tags',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tag_create');
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
            'value'=>'required',
        ]);

        $tag = new \App\Tag();
        $tag->value = $request->get('value');
        $tag->save();
        return redirect('admin/tags')->with('success', 'برچسب جدید اضافه شد.');
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
        $tag = \App\Tag::find($id);
        if(empty($tag)){
            abort(404);
            return;
        }
        $data = [
            'model' => $tag
        ];
        return view('admin.tag_create',$data);
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
        $tag = \App\Tag::find($id);
        if(empty($tag)){
            abort(404);
            return;
        }
        $this->validate($request,[
            'value'=>'required',
        ]);
        $tag->value = $request->get('value');
        $tag->save();
        return redirect('admin/tags')->with('success', 'برچسب ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = \App\Tag::find($id);
        if(empty($tag)){
            echo "ERROR";
            return;
        }
        $tag->delete();
        echo "DESTORY";
        return;
    }
}
