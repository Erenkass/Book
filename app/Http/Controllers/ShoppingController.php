<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use ShoppingCart;

class ShoppingController extends Controller
{
    public function index()
    {
        $books = ShoppingCart::all();
        return view('cart', compact('books'));

    }

    public function addToCart($id)
    {
        $books = Book::notDeleted()->find($id);
        ShoppingCart::add($books->id, $books->name, 1, $books->price, []);

        return redirect()->back();

    }

    public function removeFromCart($raw_id)
    {
        ShoppingCart::remove($raw_id);

        return redirect()->back();
    }

    public function updateCart($raw_id,$type)
    {
        $item = ShoppingCart::get($raw_id);
        //dd($item);
        if($type == 'increase'){
            ShoppingCart::update($raw_id, $item->qty+1);
        }
        else{
            ShoppingCart::update($raw_id, $item->qty-1);
        }

        return redirect()->back();
    }
}
