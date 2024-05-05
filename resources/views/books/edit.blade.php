@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                          <div class="col-6">{{ __('Kitap Düzenle') }}</div>
                          <div class="col-6 d-flex justify-content-end" ><a class="btn btn-primary" href="{{route('books.index')}}">Kitaplar</a> </div>
                      </div>
                    </div>
                    <div class="card-body">
                        <h1>Kitap Düzenle</h1>
                        <Form action="{{route('books.update',$book->id)}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Kitap Adı</label>
                                <input type="text" name="name" class="form-control" value="{{$book->name}}" placeholder="Kitap Adı">

                            </div>

                            <div class="form-group">
                                <label for="">Fiyatı</label>
                                <input type="text" name="price" value="{{$book->price}}" class="form-control" placeholder="Fiyat">

                            </div>
                            <button type="submit" class="btn btn-success mt-1">Güncelle</button>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

