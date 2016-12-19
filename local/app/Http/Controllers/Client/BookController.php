<?php

namespace App\Http\Controllers\Client;

use Faker\Provider\zh_TW\DateTime;
use Illuminate\Http\Request;
use File;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    public function booksByCategory($title){
        $category = \App\Category::where('title',$title)->first();
        if(empty($category))
            abort(404);
        $category_id = $category->id;
        $books = \App\Book::with(['writers','translators','publication','category','tags'])
            ->whereHas('category',function($query) use ($category_id){
                $query->where('id',$category_id);
            })
            ->where('active',1)
            ->skip(0)->take(20)
            ->orderBy('created_at','DESC')
            ->get();

        $data = [
            'title'=> trans('global.title_books',['cat'=>$category->title]),
            'filter' => $category,
            'model' => $books
        ];

        return view('books_filter',$data);
    }

    public function booksByTag($title){
        $tag = \App\Tag::where('value',$title)->first();
        if(empty($tag))
            abort(404);
        $tag_id = $tag->id;
        $books = \App\Book::with(['writers','translators','publication','category','tags'])
            ->whereHas('tags',function($query) use ($tag_id){
                $query->where('id',$tag_id);
            })
            ->where('active',1)
            ->skip(0)->take(20)
            ->orderBy('created_at','DESC')
            ->get();

        $data = [
            'title'=> trans('global.title_books',['cat'=>$tag->value]),
            'filter' => $tag,
            'model' => $books
        ];

        return view('books_filter',$data);
    }

    public function booksByPublication($title){
        $pub = \App\Publication::where('title',$title)->first();
        if(empty($pub))
            abort(404);
        $books = \App\Book::with(['writers','translators','publication','category','tags'])
            ->where('publication_id',$pub->id)
            ->where('active',1)
            ->skip(0)->take(20)
            ->orderBy('created_at','DESC')
            ->get();

        $data = [
            'title'=> trans('global.title_books',['cat'=>$pub->title]),
            'filter' => $pub,
            'model' => $books
        ];

        return view('books_filter',$data);
    }

    public function booksByWriter($title){
        $writer = \App\Author::where('name',$title)->where('type','writer')>first();
        if(empty($writer))
            abort(404);
        $w_id = $writer->id;
        $books = \App\Book::with(['writers','translators','publication','category','tags'])
            ->whereHas('writers',function($query) use ($w_id){
                $query->where('id',$w_id);
            })
            ->where('active',1)
            ->skip(0)->take(20)
            ->orderBy('created_at','DESC')
            ->get();

        $data = [
            'title'=> trans('global.title_books',['cat'=>$writer->name]),
            'filter' => $writer,
            'model' => $books
        ];

        return view('books_filter',$data);
    }

    public function booksByTranslator($title){
        $translator = \App\Author::where('name',$title)->where('type','translator')>first();
        if(empty($translator))
            abort(404);
        $t_id = $translator->id;
        $books = \App\Book::with(['writers','translators','publication','category','tags'])
            ->whereHas('writers',function($query) use ($t_id){
                $query->where('id',$t_id);
            })
            ->where('active',1)
            ->skip(0)->take(20)
            ->orderBy('created_at','DESC')
            ->get();

        $data = [
            'title'=> trans('global.title_books',['cat'=>$translator->name]),
            'filter' => $translator,
            'model' => $books
        ];

        return view('books_filter',$data);
    }

    function book($slug,$title=''){
        $book = \App\Book::with([
            'category',
            'translators',
            'writers',
            'publication',
            'tags'
        ])->where('slug',$slug)->first();
        if(empty($book))
            abort(404);
        $tag_ids = $book->tags()->pluck('id');
        $related = \App\Book::whereHas('tags', function($q) use ($tag_ids) {
            $q->whereIn('id', $tag_ids);
        })
            ->where('id','!=',$book->id)
            ->orderBy('created_at')
            ->take(8)
            ->get();

        $writers_ids = $book->writers()->pluck('id');
        $writer_books =\App\Book::whereHas('writers', function($q) use ($writers_ids) {
            $q->whereIn('id', $writers_ids);
        })
            ->where('id','!=',$book->id)
            ->orderBy('created_at')
            ->take(8)
            ->get();

        $publication_id = $book->publication_id;
        $publication_books= \App\Book::where('publication_id',$publication_id)
            ->where('id','!=',$book->id)
            ->orderBy('created_at')
            ->take(8)
            ->get();
        $data = [
            'model' => $book,
            'type' => 'book',
            'title' => $book->title,
            'related'=> $related,
            'writer_books'=>$writer_books,
            'publication_books'=>$publication_books
        ];

        return view('book',$data);
    }
}