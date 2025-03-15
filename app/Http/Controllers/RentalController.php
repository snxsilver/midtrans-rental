<?php

namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\Order;
use App\Http\Requests\RentalRequest;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use DB;

class RentalController extends Controller
{
    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function index(){
        $data['services'] = Service::get();
        return view('rental', $data);
    }

    public function checkout(RentalRequest $request)
    {
        $request->validated();
        // dd('hello');
        $req = $request->all();

        DB::beginTransaction();
        $uuid = Uuid::uuid4()->getHex();
        $order = Order::create([
            'uuid' => $uuid,
            'service_id' => $req['service_id'],
            'date' => Carbon::parse($request['date']),
            'total' => $req['total'],
            'status' => 0,
        ]);


        $phone = $req['phone'];
        if (substr($phone,0,2) == "08"){
            $phone = "62".substr($phone,1);
        } elseif(substr($phone,0,1) == "8") {
            $phone = "62".$phone;
        }

        $orderId = $uuid;
        $amount = $req['total'];
        $customer = [
            'first_name' => $req['first_name'],
            'last_name' => $req['last_name'],
            'email' => $req['email'].'@gmail.com',
            'phone' => $phone,
        ];

        $service = Service::where('id', $req['service_id'])->first();
        $details = [
            'id' => $service->id,
            'price' => $req['total'],
            'quantity' => 1,
            'name' => $service->name,
        ];
        DB::commit();

        $snapToken = $this->midtrans->createTransaction($orderId, $amount, $customer, $details);

        return redirect()->back()->withInput()->with(['snapToken' => $snapToken, 'orderId' => $orderId]);
    }

    public function check(Request $req){
        $check = $this->midtrans->checkTransaction($req->uuid);
        // dd($check);
        // echo json_encode($check);
        if ($check){
            echo json_encode(['status' => 200, 'data' => $check], 200);
        } else {
            echo json_encode(['status' => 400, 'message' => 'Data tidak ditemukan'], 400);
        }
        // if ($check['status_code'] == 404){
        //     return;
        // }
    }
}
