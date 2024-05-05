<?php

namespace App\Http\Controllers;

use App\Http\Requests\customerFormRequest;
use App\Jobs\MailJob;
use App\Mail\OrderMail;
use App\Models\order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use ShoppingCart;

class OrderController extends Controller
{
   public function store(customerFormRequest $request){

       $order = new order();
       $order->fill($request->all());
       $order->user_id = auth()->check() ? auth()->user()->id : 0;
       $order->total_price = ShoppingCart::totalPrice();
       $order->payment_method = 'paytr';

       $order->save();

       $items = ShoppingCart::all();

       foreach ($items as $item){ //her order için orderdetail yapar
           $orderDetail = new OrderDetail();
           $orderDetail->order_id = $order->id;
           $orderDetail->product_id = $item->id;
           $orderDetail->per_price = $item->price;
           $orderDetail->qty = $item->qty;
           $orderDetail->subtotal = $item->price * $item->qty;
           $orderDetail->save();
       }

       $emails = User::pluck('email')->toArray();//emailler alıyor

       foreach ($emails as $email){
           MailJob::dispatch($email,$order);
       }
       ShoppingCart::destroy(); // alışveriş sepetini temizler


       $merchant_id = 'XXXXXX';
       $merchant_key = 'YYYYYYYYYYYYYY';
       $merchant_salt = 'ZZZZZZZZZZZZZZ';

       $email = "erenkas1316@gmail.com";
       $payment_amount = $order->total_price * 100; //tahsil edilecek tutar , 100 ile çarpılmasını istiyor kendisi
       $merchant_oid = $order->id; // benzersiz olan bir sipariş numarası istiyor
       $user_name = $order->name.''.$order->surname;
       $user_address = $order->address;
       $user_phone = "0 551 555 5555";
       $merchant_ok_url = route('orders.success'); // ödeme başarılı olduğu zaman geri dönüş yapılacak url
       $merchant_fail_url = route('orders.fail'); ; // ödeme başarısız olduğu zaman geri dönüş yapılacak url
       $user_basket = "";

       $orderdetails = $order->details()->get();
       $basketItems = [];
       foreach ($orderdetails as $detail){
           $basketItems[] = array($detail->product->name,$detail->per_price, $detail->qty);
       }

       $user_basket = base64_encode(json_encode($basketItems)); //sepetteki ürünlerin içeriği

       if (isset($_SERVER["HTTP_CLIENT_IP"])) {
           $ip = $_SERVER["HTTP_CLIENT_IP"];
       } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
           $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
       } else {
           $ip = $_SERVER["REMOTE_ADDR"];
       }

       $user_ip = $ip;
       $timeout_limit = "30";
       $debug_on = 1;
       $test_mode = 1;  // test için 1 yapmamız lazım
       $no_installment = 0; // taksit yapılmasını istemiyorsak tek çekim için 1 yapmamız lazım
       $max_installment = 0;
       $currency = "TL";

       //Bu kısımda bir değişiklik yapılmasına gerek yok
       $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
       $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
       $post_vals = array(
           'merchant_id' => $merchant_id,
           'user_ip' => $user_ip,
           'merchant_oid' => $merchant_oid,
           'email' => $email,
           'payment_amount' => $payment_amount,
           'paytr_token' => $paytr_token,
           'user_basket' => $user_basket,
           'debug_on' => $debug_on,
           'no_installment' => $no_installment,
           'max_installment' => $max_installment,
           'user_name' => $user_name,
           'user_address' => $user_address,
           'user_phone' => $user_phone,
           'merchant_ok_url' => $merchant_ok_url,
           'merchant_fail_url' => $merchant_fail_url,
           'timeout_limit' => $timeout_limit,
           'currency' => $currency,
           'test_mode' => $test_mode
       );

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
       curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
       curl_setopt($ch, CURLOPT_TIMEOUT, 20);

       $result = @curl_exec($ch);

       if (curl_errno($ch))
           die("PAYTR IFRAME connection error. err:" . curl_error($ch));

       curl_close($ch);

       $result = json_decode($result, 1);

       if ($result['status'] == 'success')
           $token = $result['token']; // sanal pos un tokenı
       else
           die("PAYTR IFRAME failed. reason:" . $result['reason']);


       return view('shopping.checkout',compact('token'));
   }

   public function success(){
       dd("başarılı");
   }

   public function fail(){

       dd("başarısız");
   }
}
