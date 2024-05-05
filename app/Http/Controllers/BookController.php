<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    public function index(){
        $user = auth()->user(); //giriş yapan kullanıcıyı alıyor
        $books = $user->books()->get();//kullanıcıya göre kitabları getiriyor
        return view('books.index',compact('books'));

    }
    public function create(){
        return view('books.create');
    }
    public function store(BookStoreRequest $request){

        $books  = new Book();
        $books->name = $request->name;
        $books->price = $request->price;
        $books->user_id = auth()->id();

        $books->save();

        Cache::delete('books');
        return redirect()->back();
    }

    public function edit($id){
        $user= auth()->user();
        $book = $user->books()->findOrFail($id);//kullanıcın kitaplarına ulaşmak için başka kullanıcının kitabına ulaşamaz bu sayede
        return view('books.edit', compact('book'));

    }

    public function update($id,Request $request){
        $user = auth()->user();
        $book = $user->books()->findOrFail($id);//bunun yapılma sebebi Kullanıcın kendisine ait olmayan kitavı değiştirememesi incele kısmında yetkisiz post atamaması için
        $book->name = $request->name;
        $book->price = $request->price;
        $book->save();

        Cache::delete('books');

        return redirect()->back();
    }

    public function delete($id){

        Book::find($id)->delete();

        Cache::delete('books');

        return redirect()->back();
    }
}
