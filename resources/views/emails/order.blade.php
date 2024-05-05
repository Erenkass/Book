<x-mail::message>
# Introduction

Sipariş Detayları

    <x-mail::table>
        | Ürün Adı       | Adet         | Fiyat  |
        | ------------- |:-------------:| --------:|
        @foreach($order->details as $detail)
            | {{$detail->product->name}}      | {{$detail->qty}} | {{$detail->per_price}}   |
        @endforeach
    </x-mail::table>

<x-mail::button :url="''">
    Siteye git
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
