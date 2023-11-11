<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    // callback
    public function callback(Request $request)
    {
        // konfigurasi midtrans
        Config::$serverKey = config('server.midtrans.serverKey');
        Config::$isProduction = config('server.midtrans.isProduction');
        Config::$isSanitized = config('server.midtrans.isSanitized');
        Config::$is3ds = config('server.midtrans.is3ds');

        // buat instance midtrans notification
        $notification = new Notification();

        // assign ke variable untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        // cari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($order_id);

        // handle notifikasi status midtrans
        if ($status == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $transaction->status = 'PENDING';
                }
                else {
                    $transaction->status = 'SUCCESS';
                }
            }
        }
        else if ($status == 'settlement') {
            $transaction->status = 'SUCCESS';
        }
        else if ($status == 'pending') {
            $transaction->status = 'PENDING';
        }
        else if ($status == 'deny') {
            $transaction->status = 'CANCELLED';
        }
        else if ($status == 'expire') {
            $transaction->status = 'CANCELLED';
        }
        else if ($status == 'cancel') {
            $transaction->status = 'CANCELLED';
        }

        // simpan transaksi
        $transaction->save();

    }

    // success
    public function success(Request $request)
    {
        return view('midtrans.success');
    }

    //unfinish
    public function unfinish(Request $request)
    {
        return view('midtrans.unfinish');
    }

    // error
    public function error(Request $request)
    {
        return view('midtrans.error');
    }
}
