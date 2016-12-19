<?php

namespace App\Http\Controllers\Client;

use Faker\Provider\zh_TW\DateTime;
use Illuminate\Http\Request;
use File;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MagazineController extends Controller
{
    public function magazinesByCategory($title){
        $category = \App\Category::where('title',$title)->first();
        if(empty($category))
            abort(404);
        $category_id = $category->id;
        $magazines = \App\Magazine::with(['publication','category','tags'])
            ->whereHas('category',function($query) use ($category_id){
                $query->where('id',$category_id);
            })
            ->where('active',1)
            ->skip(0)->take(20)
            ->orderBy('created_at','DESC')
            ->get();

        $data = [
            'title'=> trans('global.title_magazines',['cat'=>$category->title]),
            'filter' => $category,
            'model' => $magazines
        ];

        return view('magazines_filter',$data);
    }
    
    public function magazinesByTag($title){
        $tag = \App\Tag::where('value',$title)->first();
        if(empty($tag))
            abort(404);
        $tag_id = $tag->id;
        $magazines = \App\Magazine::with(['publication','category','tags'])
            ->whereHas('tags',function($query) use ($tag_id){
                $query->where('id',$tag_id);
            })
            ->where('active',1)
            ->skip(0)->take(20)
            ->orderBy('created_at','DESC')
            ->get();

        $data = [
            'title'=> trans('global.title_magazines',['cat'=>$tag->value]),
            'filter' => $tag,
            'model' => $magazines
        ];

        return view('magazines_filter',$data);
    }

    public function magazinesByPublication($title){
        $pub = \App\Publication::where('title',$title)->first();
        if(empty($pub))
            abort(404);
        $magazines = \App\Magazine::with(['publication','category','tags'])
            ->where('publication_id',$pub->id)
            ->where('active',1)
            ->skip(0)->take(20)
            ->orderBy('created_at','DESC')
            ->get();

        $data = [
            'title'=> trans('global.title_magazines',['cat'=>$pub->title]),
            'filter' => $pub,
            'model' => $magazines
        ];

        return view('magazines_filter',$data);
    }

    function magazine($slug,$title=''){
        $magazine = \App\Magazine::with([
            'category',
            'publication',
            'tags'
        ])->where('slug',$slug)->first();

        if(empty($magazine))
            abort(404);

        $tag_ids = $magazine->tags()->pluck('id');
        $related = \App\Magazine::whereHas('tags', function($q) use ($tag_ids) {
            $q->whereIn('id', $tag_ids);
        })
            ->where('id','!=',$magazine->id)
            ->orderBy('created_at')
            ->take(8)
            ->get();

        $publication_id = $magazine->publication_id;
        $publication_magazines= \App\Magazine::where('publication_id',$publication_id)
            ->where('id','!=',$magazine->id)
            ->orderBy('created_at')
            ->take(8)
            ->get();
        $data = [
            'model' => $magazine,
            'type' => 'magazine',
            'title' => $magazine->title,
            'related'=> $related,
            'publication_magazines'=>$publication_magazines
        ];

        return view('magazine',$data);
    }

}