@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Kitaplar') }}</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kitap Adı</th>
                                <th scope="col">Fiyatı</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <th scope="row">{{$book->id}}</th>
                                    <td>{{$book->name}}</td>
                                    <td>{{$book->price}}</td>
                                    <td>
                                        <a href="{{route('shopping.addToCart',$book->id)}}" class="btn btn-info ">Sepete Ekle</a>
                                        <a href="{{route('users.books.show',$book->id)}}" class="btn btn-success m-1 ">Ayrıntılar</a>
                                        <a href="{{route('users.books.show',$book->id)}}" class="btn btn-primary  ">Mail Göncer</a>


                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href="{{route('books.create')}}" class="btn btn-success">Ekle</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
