<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Cache;
use JsonLd;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function home(){

        if(!Cache::has('books')){ // cache de yoksa veriyi alacak
            $books = Book::get();
            Cache::put('books',$books);// veri tabanından çekilen veriyi yazdırma

        }
        else{
            $books = Cache::get('books');// cache den veri çeker

        }

        return view('welcome',compact('books'));
    }

    public function show($id){
        $book = Book::findOrFail($id);
        SEOMeta::setTitle($book->name);
        SEOMeta::setDescription($book->name.'isimli kitabı al');
        SEOMeta::setCanonical(url()->current());

        JsonLd::setTitle($book->name);
        JsonLd::setDescription($book->name.'isimli kitabı al');
        JsonLd::setType('Product');

        return view('users.books.show',compact('book'));
    }
}
