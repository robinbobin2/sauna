<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Balance;
use Illuminate\Support\Facades\Auth;
use App\AccountType;

class TarifsController extends Controller
{
    //
    public function updateTarif(Request $request) {
        $user = Auth::user();
        $type = AccountType::findOrFail($request->type);
        if($user->account_type !== $request->type && $type->price <= $user->balance) {
            $user->account_type()->associate($request->type);
            $user->balance = $user->balance-$type->price;
            $user->payed_until = strtotime("+1 month", strtotime(date('Y.m.d')));
            $user->payed_at = strtotime(date('Y.m.d'));
            $user->save();
            return response()->json($user);
        } else {
            return response()->json(['message' => 'No money']);
        }
    }

    public function changeToBasic(Request $request) {
        $user = User::fidOrFail($request->user_id);
        $user->account_type()->associate(1);
        $user->payed_until = '';
        $user->payed_at = strtotime(date('Y.m.d'));
        $user->save();
        return response()->json($user);
    }
    public function getAccountTypes() {
        $types = AccountType::all();
        return response()->json($types);
    }

    public function pay(Request $request)
    {
        $invoice = strtotime("now");
        $payment = new \Idma\Robokassa\Payment(
            'pgrlink', 'w4DmQbM22acqmzh4H3Sg', 'E4l13cA02gEGCHDcSZGx', false
        );

        $payment
        ->setInvoiceId($invoice)
        ->setSum($request->sum)
        ->setDescription('Пополнение баланса на pgr.link');

        // redirect to payment url
        return response()->json(['redirect'=>$payment->getPaymentUrl()]);
    }
    public function success(Request $request)
    {

        if (isset($request->inv_id)&&isset($request->crc)&&isset($request->SignatureValue)) {
            # code...
            $user = Auth::user();
            return response()->json($user);
            if (Balance::where('SignatureValue', $request->SignatureValue)->first() == null) {

                $user = Auth::user();
                $summa = $request->out_summ;
                $balance = Balance::create(['SignatureValue'=>$request->SignatureValue, 'user_id'=>$user->id,'ammount'=>$summa]);
                $balance->save();

                $sum = $user->balance+$summa;
                $user->update(['balance'=>$sum]);
                $user->save();
                return response()->json($user);
            } else {
                return abort(403, 'Unauthorized action.');
            }

        }
    }

    public function fail()
    {
        return response()->json(['message'=>'Платеж не прошел']);
    }

}
