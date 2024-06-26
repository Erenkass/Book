<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'orderdetails';// override yapmak için

    public function product(){
        return $this->belongsTo(Book::class,'product_id','id'); // birebir ilişki olduğu için belongsTo
    }
}
