<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MagazineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $magazines = \App\Magazine::with(['category','publication'])->orderBy('created_at','DESC')->paginate(10);
        $data=[
            'model'=>$magazines,
        ];
        return view('admin.magazines',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \App\Category::all();
        $tags = \App\Tag::select(['id','value'])->get();
        $publications = \App\Publication::select(['id','title'])->get();
        $data=[
            'categories' => $categories,
            'tags'=>$tags->toJson(),
            'publications'=>$publications->toJson(),
        ];
        return view('admin.magazine_create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['title']= trim($input['title']);
        $input['publication']= trim($input['publication']);
        $time = time();
        if(empty($input['slug'])){
            $input['slug'] = "Magazine-".substr(str_shuffle(rand(100000,999999).time()),0,10);
        }
        $input['slug']= trim($input['slug']);
        $request->replace($input);
        $this->validate($request,[
            'title'=>'required',
            'category' => 'required',
            'publication'=>'required',
            'description'=>'required',
            'text'=>'required',
            'file'=>'required',
            'image' =>   'required|mimes:jpg,jpeg,bmp,png',
        ]);

        $fileURL = null;
        if ($request->hasFile('image')) {
            $desPath = "uploads/magazines/".$request->get('slug').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$request->file('image')->getClientOriginalExtension();
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$request->file('image')->getClientOriginalExtension();
            }
            if($request->file('image')->move($desPath, $fileName)){
                $fileURL = $desPath.'/'.$fileName;
            }
        }

        $file = $request->get('file');
        $magURL = null;
        if (!empty($file)) {
            $extension = last(explode(".",$file));
            $desPath = "uploads/magazines/".$request->get('slug').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$extension;
            }
            if(rename($file,$desPath.'/'.$fileName)){
                $magURL = $desPath.'/'.$fileName;
            }
        }

        $file_demo = $request->get('file_demo');
        $magDemoURL = null;
        if (!empty($file_demo)) {
            $extension = last(explode(".",$file));
            $desPath = "uploads/magazines/".$request->get('slug').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand."-DEMO.".$extension;
            }
            if(rename($file_demo,$desPath.'/'.$fileName)){
                $magDemoURL = $desPath.'/'.$fileName;
            }
        }

        while(\App\Magazine::where('slug',$request->get('slug'))->first()){
            $input = $request->all();
            $input['slug'] = "Magazine-".substr(str_shuffle(rand(100000,999999).time()),0,10);
            $request->replace($input);

        }

        if(is_numeric($request->get('publication'))){
            $publication = \App\Publication::find($request->get('publication'));
        }else{
            $publication = \App\Publication::firstOrCreate(['title'=>trim($request->get('publication'))]);
        }
        $magazine = new \App\Magazine();

        $magazine->category_id = $request->get('category');
        $magazine->publication_id = $publication->id;
        $magazine->slug = $request->get('slug');
        $magazine->title = $request->get('title');
        $magazine->price = (empty($request->get('price')))?0:$request->get('price');
        $magazine->file = $magURL;
        $magazine->file_demo = $magDemoURL;
        $magazine->has_demo = (empty($magDemoURL))?0:1;
        $magazine->description = $request->get('description');
        $magazine->text = $request->get('text');
        $magazine->code = $request->get('code');
        $magazine->publish_date = $request->get('publish_date');
        $magazine->image = $fileURL;
        $magazine->active = ($request->has('active'))?1:0;
        $magazine->save();
        $tags = $request->get('tags');
        $tags = $request->get('tags');
        $saveTag = null;
        if(!empty($tags)){
            foreach($tags as $key => $value){
                if(is_numeric($value)){
                    $saveTag[] = $value;
                }else{
                    $item =  \App\Tag::firstOrCreate(['value' =>trim($value)]);
                    $saveTag[]=$item->id;
                }
            }
        }
        if(!empty($saveTag)){
            $magazine->tags()->attach($saveTag);
        }
        return redirect('admin/magazines')->with('success', 'مجله جدید اضافه شد.');
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
        $magazine = \App\Magazine::find($id);
        $categories = \App\Category::all();
        $tags = \App\Tag::select(['id','value'])->get();
        $publications = \App\Publication::select(['id','title'])->get();
        if(empty($magazine)){
            abort(404);
            return;
        }

        $data = [
            'categories'=>$categories,
            'tags'=>$tags->toJson(),
            'publications'=>$publications->toJson(),
            'model'=>$magazine,
        ];

        return view('admin.magazine_create',$data);
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
        $magazine = \App\Magazine::find($id);
        if(empty($magazine)){
            abort(404);
            return;
        }
        $input = $request->all();
        $input['title']= trim($input['title']);
        $input['publication']= trim($input['publication']);
        $request->replace($input);
        $rules = [
            'title'=>'required',
            'category' => 'required',
            'publication'=>'required',
            'description'=>'required',
            'text'=>'required',
            'image' =>   'mimes:jpg,jpeg,bmp,png',
        ];

        $this->validate($request,$rules);


        $fileURL = null;
        if ($request->hasFile('image')) {
            $desPath = "uploads/magazines/".$magazine->slug.'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$request->file('image')->getClientOriginalExtension();
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$request->file('image')->getClientOriginalExtension();
            }
            if($request->file('image')->move($desPath, $fileName)){
                $fileURL = $desPath.'/'.$fileName;
            }
        }

        $file = $request->get('file');
        $magURL = null;
        if (!empty($file)) {
            $extension = last(explode(".",$file));
            $desPath = "uploads/magazines/".$request->get('slug').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$extension;
            }
            if(rename($file,$desPath.'/'.$fileName)){
                $magURL = $desPath.'/'.$fileName;
            }
        }

        $file_demo = $request->get('file_demo');
        $magDemoURL = null;
        if (!empty($file_demo)) {
            $extension = last(explode(".",$file));
            $desPath = "uploads/magazines/".$request->get('slug').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand."-DEMO.".$extension;
            }
            if(rename($file_demo,$desPath.'/'.$fileName)){
                $magDemoURL = $desPath.'/'.$fileName;
            }
        }

        if(is_numeric($request->get('publication'))){
            $publication = \App\Publication::find($request->get('publication'));
        }else{
            $publication = \App\Publication::firstOrCreate(['title'=>trim($request->get('publication'))]);
        }
        $magazine->category_id = $request->get('category');
        $magazine->publication_id = $publication->id;
        $magazine->title = $request->get('title');
        $magazine->price = (empty($request->get('price')))?0:$request->get('price');
        if(!empty($magURL)){
            if(file_exists(public_path($magazine->file)))
                unlink(public_path($magazine->file));
            $magazine->file = $magURL;
        }

        if(!empty($magDemoURL)){
            if(file_exists(public_path($magazine->file_demo)))
                unlink(public_path($magazine->file_demo));
            $magazine->file_demo = $magDemoURL;
            $magazine->has_demo = 1;
        }

        $magazine->description = $request->get('description');
        $magazine->text = $request->get('text');
        $magazine->code = $request->get('code');
        $magazine->publish_date = $request->get('publish_date');
        if(!empty($fileURL)){
            if(file_exists(public_path($magazine->image)))
                unlink(public_path($magazine->image));
            $magazine->image = $fileURL;
        }
        $magazine->active = ($request->has('active'))?1:0;
        $magazine->save();
        $tags = $request->get('tags');
        $saveTag = null;
        if(!empty($tags)){
            foreach($tags as $key => $value){
                if(is_numeric($value)){
                    $saveTag[] = $value;
                }else{
                    $item =  \App\Tag::firstOrCreate(['value' =>trim($value)]);
                    $saveTag[]=$item->id;
                }
            }
        }
        if(!empty($saveTag)){
            $magazine->tags()->sync($tags);
        }else{
            $magazine->tags()->detach();
        }

        return redirect('admin/magazines')->with('success', 'مجله ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $magazine = \App\Magazine::find($id);
        if(empty($magazine)){
            echo "ERROR";
            return;
        }
        $magazine->delete();
        echo "DESTORY";
        return;
    }

    private function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}
