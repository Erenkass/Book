<?php

namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;

class PaytrController extends Controller
{
    public function notification(){
        $post = $_POST;

        $merchant_key   = 'YYYYYYYYYYYYYY';
        $merchant_salt  = 'ZZZZZZZZZZZZZZ';

        $hash = base64_encode( hash_hmac('sha256', $post['merchant_oid'].$merchant_salt.$post['status'].$post['total_amount'], $merchant_key, true) );

        if( $hash != $post['hash'] )
            die('PAYTR notification failed: bad hash');

        $order= order::find($post['merchant_oid']);

        if( $post['status'] == 'success' ) {

            $order->is_success = 1;
            $order->save();

        }
        else {

            $order->failed_reason_msg = $post["failed_reason_msg"];
            $order->save();

        }

        echo "OK";
        exit;
    }
}
