<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = \App\Book::with(['writers','translators','category','publication'])->orderBy('created_at','DESC')->paginate(10);
        $data=[
            'model'=>$books,
        ];
        return view('admin.books',$data);
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
        $writers = \App\Author::where('type','writer')->select(['id','name'])->get();
        $translators = \App\Author::where('type','translator')->select(['id','name'])->get();
        $data=[
            'categories' => $categories,
            'tags'=>$tags->toJson(),
            'publications'=>$publications->toJson(),
            'writers'=>$writers->toJson(),
            'translators'=>$translators->toJson()
        ];
        return view('admin.book_create',$data);
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
            $input['slug'] = "Book-".substr(str_shuffle(last(explode("-",$input['isbn'])).time()),0,10);
        }
        $input['slug']= trim($input['slug']);
        $request->replace($input);
        $this->validate($request,[
            'title'=>'required',
            'category' => 'required',
            'publication'=>'required',
            'writers'=>'required',
            'isbn'=>'required|unique:books',
            'description'=>'required',
            'text'=>'required',
            'file'=>'required',
            'image' =>   'required|mimes:jpg,jpeg,bmp,png',
        ]);

        $fileURL = null;
        if ($request->hasFile('image')) {
            $desPath = "uploads/books/".$request->get('isbn').'/'.date("Ymd");
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

        $bookURL = null;
        if (!empty($file)) {
            $extension = last(explode(".",$file));
            $desPath = "uploads/books/".$request->get('isbn').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$extension;
            }
            if(rename($file,$desPath.'/'.$fileName)){
                $bookURL = $desPath.'/'.$fileName;
            }
        }



        $file_demo = $request->get('file_demo');

        $bookDemoURL = null;
        if (!empty($file_demo)) {
            $extension = last(explode(".",$file_demo));
            $desPath = "uploads/books/".$request->get('isbn').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand."-DEMO.".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand."-DEMO.".$extension;
            }
            if(rename($file_demo,$desPath.'/'.$fileName)){
                $bookDemoURL = $desPath.'/'.$fileName;
            }
        }

        while(\App\Book::where('slug',$request->get('slug'))->first()){
            $input = $request->all();
            $input['slug'] = "Book-".substr(str_shuffle(last(explode("-",$input['isbn'])).time()),0,10);
            $request->replace($input);

        }

        if(is_numeric($request->get('publication'))){
            $publication = \App\Publication::find($request->get('publication'));
        }else{
            $publication = \App\Publication::firstOrCreate(['title'=>trim($request->get('publication'))]);
        }

        $book = new \App\Book();

        $book->category_id = $request->get('category');
        $book->publication_id = $publication->id;
        $book->slug = $request->get('slug');
        $book->title = $request->get('title');
        $book->price = (empty($request->get('price')))?0:$request->get('price');
        $book->file = $bookURL;
        $book->file_demo = $bookDemoURL;
        $book->description = $request->get('description');
        $book->text = $request->get('text');
        $book->isbn = $request->get('isbn');
        $book->publish_date = $request->get('publish_date');
        $book->image = $fileURL;
        $book->active = ($request->has('active'))?1:0;
        $book->save();
        $writers = $request->get('writers');
        $saveWR = null;
        if(!empty($writers)){
            foreach($writers as $key => $value){
                if(is_numeric($value)){
                    $saveWR[] = $value;
                }else{
                    $item =  \App\Author::firstOrCreate(['name' =>trim($value),'type'=>'writer']);
                    $saveWR[]=$item->id;
                }
            }
        }
        $translator = $request->get('translators');
        $saveTR = null;
        if(!empty($translator)){
            foreach($translator as $key => $value){
                if(is_numeric($value)){
                    $saveTR[] = $value;
                }else{
                    $item =  \App\Author::firstOrCreate(['name' =>trim($value),'type'=>'translator']);
                    $saveTR[]=$item->id;
                }
            }
        }
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
            $book->tags()->attach($saveTag);
        }
        if(!empty($saveWR)){
            $book->writers()->attach($saveWR);
        }
        if(!empty($saveTR)){
            $book->translators()->attach($saveTR);
        }

        return redirect('admin/books')->with('success', 'کتاب جدید اضافه شد.');
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
        $book = \App\Book::find($id);
        $categories = \App\Category::all();
        $tags = \App\Tag::select(['id','value'])->get();
        $publications = \App\Publication::select(['id','title'])->get();
        $writers = \App\Author::where('type','writer')->select(['id','name'])->get();
        $translators = \App\Author::where('type','translator')->select(['id','name'])->get();
        if(empty($book)){
            abort(404);
            return;
        }
        $data = [
            'categories'=>$categories,
            'tags'=>$tags->toJson(),
            'publications'=>$publications->toJson(),
            'writers'=>$writers->toJson(),
            'translators'=>$translators->toJson(),
            'model'=>$book,
        ];

        return view('admin.book_create',$data);
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
        $book = \App\Book::find($id);
        if(empty($book)){
            abort(404);
            return;
        }
        $input = $request->all();
        $input['title']= trim($input['title']);
        $input['isbn']= trim($input['isbn']);
        $input['publication']= trim($input['publication']);
        $request->replace($input);
        $rules = [
            'title'=>'required',
            'category' => 'required',
            'publication'=>'required',
            'writers'=>'required',
            'isbn'=>'required',
            'description'=>'required',
            'text'=>'required',
            'image' =>   'mimes:jpg,jpeg,bmp,png',
        ];
        if($book->isbn != $request->get('isbn')){
            $rules['isbn']='required|unique:posts';
        }

        $this->validate($request,$rules);


        $fileURL = null;
        if ($request->hasFile('image')) {
            $desPath = "uploads/books/".$request->get('isbn').'/'.date("Ymd");
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

        $bookURL = null;
        if (!empty($file)) {
            $extension = last(explode(".",$file));
            $desPath = "uploads/books/".$request->get('isbn').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$extension;
            }
            if(rename($file,$desPath.'/'.$fileName)){
                $bookURL = $desPath.'/'.$fileName;
            }
        }

        $file_demo = $request->get('file_demo');

        $bookDemoURL = null;
        if (!empty($file_demo)) {
            $extension = last(explode(".",$file_demo));
            $desPath = "uploads/books/".$request->get('isbn').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand."-DEMO.".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand."-DEMO.".$extension;
            }
            if(rename($file_demo,$desPath.'/'.$fileName)){
                $bookDemoURL = $desPath.'/'.$fileName;
            }
        }

        if(is_numeric($request->get('publication'))){
            $publication = \App\Publication::find($request->get('publication'));
        }else{
            $publication = \App\Publication::firstOrCreate(['title'=>trim($request->get('publication'))]);
        }
        $book->category_id = $request->get('category');
        $book->publication_id = $publication->id;
        $book->title = $request->get('title');
        $book->price = (empty($request->get('price')))?0:$request->get('price');
        if(!empty($bookURL)){
            if(file_exists(public_path($book->file)))
                unlink(public_path($book->file));
            $book->file = $bookURL;
        }
        if(!empty($bookDemoURL)){
            if(file_exists(public_path($book->file_demo)))
                unlink(public_path($book->file_demo));
            $book->file_demo = $bookDemoURL;
        }
        $book->description = $request->get('description');
        $book->text = $request->get('text');
        $book->isbn = $request->get('isbn');
        $book->publish_date = $request->get('publish_date');
        if(!empty($fileURL)){
            if(file_exists(public_path($book->image)))
                unlink(public_path($book->image));
            $book->image = $fileURL;
        }
        $book->active = ($request->has('active'))?1:0;
        $book->save();
        $writers = $request->get('writers');
        $saveWR = null;
        if(!empty($writers)){
            foreach($writers as $key => $value){
                if(is_numeric($value)){
                    $saveWR[] = $value;
                }else{
                    $item =  \App\Author::firstOrCreate(['name' =>trim($value),'type'=>'writer']);
                    $saveWR[]=$item->id;
                }
            }
        }
        $translator = $request->get('translators');
        $saveTR = null;
        if(!empty($translator)){
            foreach($translator as $key => $value){
                if(is_numeric($value)){
                    $saveTR[] = $value;
                }else{
                    $item =  \App\Author::firstOrCreate(['name' =>trim($value),'type'=>'translator']);
                    $saveTR[]=$item->id;
                }
            }
        }
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
            $book->tags()->sync($saveTag);
        }else{
            $book->tags()->detach();
        }
        if(!empty($saveWR)){
            $book->writers()->sync($saveWR);
        }else{
            $book->writers()->detach();
        }
        if(!empty($saveTR)){
            $book->translators()->sync($saveTR);
        }else{
            $book->translators()->detach();
        }

        return redirect('admin/books')->with('success', 'کتاب ویرایش شد.');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = \App\Book::find($id);
        if(empty($book)){
            echo "ERROR";
            return;
        }
        $book->delete();
        echo "DESTORY";
        return;
    }

    private function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function dosome(Request $request){
        dd($request->all());
    }
}
