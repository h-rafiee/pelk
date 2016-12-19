<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function searchBooks(Request $request){
            if($request->has('name')) {
                $query = $request->get('name');
                $books = \App\Book::with(['writers', 'translators', 'tags', 'publication', 'category'])
                    ->where('title', 'like', '%' . $query . '%')
                    ->get();
                $data['items'] = $books;
                $data['type'] = 'book';
                echo view('objects.items',$data);
            }
            return "";
    }

    public function searchMagazines(Request $request){
        if($request->has('name')) {
            $query = $request->get('name');
            $magazines = \App\Magazine::with(['tags', 'publication', 'category'])
                ->where('title', 'like', '%' . $query . '%')
                ->get();
            $data['items'] = $magazines;
            $data['type'] = 'magazines';
            echo view('objects.items',$data);
        }
        return "";
    }
}