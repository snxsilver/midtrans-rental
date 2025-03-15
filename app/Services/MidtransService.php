<?php
namespace App\Services;

use Midtrans\Config;
use Midtrans\Transaction;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction($orderId, $amount, $customer, $details)
    {
        $transaction = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
            ],
            'item_details' => [
              [
                'id' => $details['id'],
                'price' => $details['price'],
                'quantity' => 1,
                'name' => $details['name'],
              ],
            ]
        ];

        return Snap::getSnapToken($transaction);
    }

    public function checkTransaction($orderId){
        // try {
            return Transaction::status($orderId);
        // } catch(e){
        //     return;
        // }
    }
}

?>