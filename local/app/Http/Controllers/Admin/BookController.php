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
        $data=[
            'categories' => $categories,
            'tags'=>$tags->toJson()
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
            'translators'=>'required',
            'isbn'=>'required|unique:books',
            'description'=>'required',
            'text'=>'required',
            'file'=>'required',
            'image' =>   'mimes:jpg,jpeg,bmp,png',
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

        $bookURL = null;
        if ($request->hasFile('file')) {
            $desPath = "uploads/books/".$request->get('isbn').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$request->file('file')->getClientOriginalExtension();
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$request->file('file')->getClientOriginalExtension();
            }
            if($request->file('file')->move($desPath, $fileName)){
                $bookURL = $desPath.'/'.$fileName;
            }
        }

        while(\App\Book::where('slug',$request->get('slug'))->first()){
            $input = $request->all();
            $input['slug'] = "Book-".substr(str_shuffle(last(explode("-",$input['isbn'])).time()),0,10);
            $request->replace($input);

        }

        $publication = \App\Publication::firstOrCreate(['title'=>trim($request->get('publication'))]);

        $book = new \App\Book();

        $book->category_id = $request->get('category');
        $book->publication_id = $publication->id;
        $book->slug = $request->get('slug');
        $book->title = $request->get('title');
        $book->price = $request->get('price');
        $book->file = $bookURL;
        $book->description = $request->get('description');
        $book->text = $request->get('text');
        $book->isbn = $request->get('isbn');
        $book->publish_date = $request->get('publish_date');
        $book->image = $fileURL;
        $book->active = ($request->has('active'))?1:0;
        $book->save();
        $writers = $request->get('writers');
        $writers = explode(",",$writers);
        $saveWR = null;
        $item = null;
        foreach($writers as $key => $value){
            $item =  \App\Author::firstOrCreate(['name' =>trim($value),'type'=>'writer']);
            $saveWR[]=$item->id;
        }
        $translator = $request->get('translators');
        $translator = explode(",",$translator);
        $saveTR = null;
        $item = null;
        foreach($translator as $key => $value){
            $item =  \App\Author::firstOrCreate(['name' =>trim($value),'type'=>'translator']);
            $saveTR[]=$item->id;
        }
        $tags = $request->get('tags');
        if(!empty($tags)){
            $book->tags()->attach($tags);
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

        if(empty($book)){
            abort(404);
            return;
        }

        $writers=null;
        foreach($book->writers as $wr){
            $writers[] = $wr->name;
        }

        $translators=null;
        foreach($book->translators as $tr){
            $translators[] = $tr->name;
        }

        $data = [
            'categories'=>$categories,
            'tags'=>$tags->toJson(),
            'model'=>$book,
            'writers'=>implode(",",$writers),
            'translators'=>implode(",",$translators),
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
        $request->replace($input);
        $rules = [
            'title'=>'required',
            'category' => 'required',
            'publication'=>'required',
            'writers'=>'required',
            'translators'=>'required',
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
        $bookURL = null;
        if ($request->hasFile('file')) {
            $desPath = "uploads/books/".$request->get('isbn').'/'.date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$request->file('file')->getClientOriginalExtension();
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$request->file('file')->getClientOriginalExtension();
            }
            if($request->file('file')->move($desPath, $fileName)){
                $bookURL = $desPath.'/'.$fileName;
            }
        }

        $publication = \App\Publication::firstOrCreate(['title'=>trim($request->get('publication'))]);

        $book->category_id = $request->get('category');
        $book->publication_id = $publication->id;
        $book->title = $request->get('title');
        $book->price = $request->get('price');
        if(!empty($bookURL)){
            $book->file = $bookURL;
        }
        $book->description = $request->get('description');
        $book->text = $request->get('text');
        $book->isbn = $request->get('isbn');
        $book->publish_date = $request->get('publish_date');
        if(!empty($fileURL)){
            $book->image = $fileURL;
        }
        $book->active = ($request->has('active'))?1:0;
        $book->save();
        $writers = $request->get('writers');
        $writers = explode(",",$writers);
        $saveWR = null;
        $item = null;
        foreach($writers as $key => $value){
            $item =  \App\Author::firstOrCreate(['name' =>trim($value),'type'=>'writer']);
            $saveWR[]=$item->id;
        }
        $translator = $request->get('translators');
        $translator = explode(",",$translator);
        $saveTR = null;
        $item = null;
        foreach($translator as $key => $value){
            $item =  \App\Author::firstOrCreate(['name' =>trim($value),'type'=>'translator']);
            $saveTR[]=$item->id;
        }
        $tags = $request->get('tags');
        if(!empty($tags)){
            $book->tags()->sync($tags);
        }
        if(!empty($saveWR)){
            $book->writers()->sync($saveWR);
        }
        if(!empty($saveTR)){
            $book->translators()->sync($saveTR);
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
}
