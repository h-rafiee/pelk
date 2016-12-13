<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PromoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotes  = \App\Promote::orderBy('created_at','DESC')->paginate(10);
        $data=[
            'model'=>$promotes,
        ];
        return view('admin.promotes',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.promote_create');
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
            'image'=>'required|mimes:jpg,jpeg,bmp,png',
        ]);


        $fileURL = null;
        if ($request->hasFile('image')) {
            $desPath = "uploads/others/".date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(10);
            $fileName = $rand.".".$request->file('image')->getClientOriginalExtension();
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(10);
                $fileName = $rand.".".$request->file('image')->getClientOriginalExtension();
            }
            if($request->file('image')->move($desPath, $fileName)){
                $fileURL = $desPath.'/'.$fileName;
            }
        }
        if(empty($fileURL))
            return redirect('admin/sliders')->withError(['image_upload'=>'خطا در آپلود تصویر دوباره تلاش کنید.']);

        $slider = new \App\Promote();
        $slider->title = $request->input('title');
        $slider->image = $fileURL;
        $slider->content = (empty($request->input('content')))? null  : $request->input('content') ;
        $slider->link = (empty($request->input('link')))? null  : $request->input('link') ;
        $slider->position = (empty($request->input('position')))? 0  : $request->input('position') ;
        $slider->save();
        return redirect('admin/sliders')->with('success', 'اسلایدر جدید اضافه شد.');

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
        $promote = \App\Promote::find($id);
        if(empty($promote)){
            abort(404);
            return;
        }
        $data = [
            'model' => $promote
        ];
        return view('admin.promote_create',$data);
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

        $slider = \App\Promote::find($id);
        if(empty($slider)){
            abort(404);
            return;
        }

        $this->validate($request,[
            'title'=>'required',
            'image'=>'mimes:jpg,jpeg,bmp,png',
        ]);


        $fileURL = null;
        if ($request->hasFile('image')) {
            $desPath = "uploads/others/".date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(10);
            $fileName = $rand.".".$request->file('image')->getClientOriginalExtension();
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(10);
                $fileName = $rand.".".$request->file('image')->getClientOriginalExtension();
            }
            if($request->file('image')->move($desPath, $fileName)){
                $fileURL = $desPath.'/'.$fileName;
            }
        }
        $slider->title = $request->input('title');
        if(!empty($fileURL)){
            unlink(public_path($slider->image));
            $slider->image = $fileURL;
        }
        $slider->content = (empty($request->input('content')))? null  : $request->input('content') ;
        $slider->link = (empty($request->input('link')))? null  : $request->input('link') ;
        $slider->position = (empty($request->input('position')))? 0  : $request->input('position') ;
        $slider->save();
        return redirect('admin/sliders')->with('success', 'اسلایدر جدید اضافه شد.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promote = \App\Promote::find($id);
        if(empty($promote)){
            echo "ERROR";
            return;
        }
        $promote->delete();
        echo "DESTORY";
        return;
    }

    private function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}
