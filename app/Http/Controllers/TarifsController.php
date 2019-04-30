<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
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

}
