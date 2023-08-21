<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;
use Auth;
use session;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show wallet history'])->only('admin_index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth::user()->id)->latest()->paginate(10);
        return view('frontend.default.user.wallet.index', compact('wallets'));
    }

    public function admin_index()
    {
        $wallets = Wallet::latest()->paginate(10);
        return view('admin.default.wallet_recharge_history.index', compact('wallets'));
    }
    

    public function rechage(Request $request)
    {
        // $data['amount'] = round($request->amount);
        // $data['payment_method'] = $request->payment_option;
        // $data['payment_type'] = 'wallet_payment';

        // $request->session()->put('payment_data', $data);
        // if($request->payment_option == 'paypal'){
        //     $paypal = new PayPalController;
        //     return $paypal->getCheckout();
        // }
        // elseif ($request->payment_option == 'stripe') {
        //     $stripe = new StripePaymentController;
        //     return $stripe->index();
        // }
        // elseif ($request->payment_option == 'sslcommerz') {
        //     $sslcommerz = new PublicSslCommerzPaymentController;
        //     return $sslcommerz->index($request);
        // }
        // elseif ($request->payment_option == 'paystack') {
        //     $paystack = new PaystackController;
        //     return $paystack->redirectToGateway($request);
        // }
        // elseif ($request->payment_option == 'instamojo') {
        //     $instamojo = new InstamojoController;
        //     return $instamojo->pay($request);
        // }
        // elseif ($request->payment_option == 'paytm') {
        //     $paytm = new PaytmController;
        //     return $paytm->index();
        // }


        $deposit_slip = null;
        
         if($request->hasFile("deposit_slip")){
           $deposit_slip = time().'-'.rand(11111,99999).'.'.$request->file('deposit_slip')->getClientOriginalExtension();    
           $request->file('deposit_slip')->move(public_path('uploads/deposit_slip'), $deposit_slip);
        }

        $wallet = new Wallet;
        $wallet->user_id = Auth::user()->id;
        $wallet->amount = $request->amount;
        $wallet->bank_name = $request->bank_name;
        $wallet->status = 0;
        $wallet->deposit_slip = $deposit_slip;
        $wallet->save();
        
        flash(translate('Recharge request send successfully.'))->success();
        return redirect()->route('wallet.index');


    }

    public function wallet_payment_done($payment_data, $payment_details)
    {
        $user = Auth::user();
        $user_profile = $user->profile;
        $user_profile->balance = $user_profile->balance + $payment_data['amount'];
        $user_profile->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data['amount'];
        $wallet->payment_method = $payment_data['payment_method'];
        $wallet->payment_details = $payment_details;
        $wallet->type = "Recharge";
        $wallet->save();

        Session::forget('payment_data');
        Session::forget('payment_type');

        flash(translate('Payment completed'))->success();
        return redirect()->route('wallet.index');
    }

    public function wallet_payment_by_admin(Request $request)
    {
        $data['amount'] = (float) $request->amount;
        $data['payment_method'] = "Offline Payment"; 

        $user = User::where('id', $request->user_id)->first();
        $user_profile = $user->profile;
        $user_profile->balance = $user_profile->balance + $data['amount'];
        $user_profile->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $data['amount'];
        $wallet->payment_method = $data['payment_method'];
        $wallet->payment_details = "Offline Payment";
        $wallet->type = "Recharge By " . Auth::user()->name;
        $wallet->save();

        flash(translate('Wallet balance has been added'))->success();
        return back();
    }

    public function wallet_transaction_history(Request $request)
    {
        $user_id = '';
        $date_range = '';
        $wallet_status = '';

        if ($request->user_id) {
            $user_id = $request->user_id;
        }

        $users_with_wallet = User::whereIn('id', function ($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        
        if($request->wallet_status){
           if($request->wallet_status == 0 || $request->wallet_status == 1){
            $wallet_history = $wallet_history->where('status', $request->wallet_status);
           } 
        }
        if ($user_id) {
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }

        $wallets = $wallet_history->paginate(10);

        return view('admin.default.wallet_requests_history', compact('wallets', 'users_with_wallet', 'user_id', 'date_range', 'wallet_status'));
    }

    public function waletApprove(Request $request)
    {
        $wallet = Wallet::findOrFail($request->wallet_id);
        $wallet->status = 1;
        $wallet->save();
        
        $profile = $wallet->user->profile;
        $profile->balance = $profile->balance + $wallet->amount;
        $profile->save();
     

        flash(translate('Recharge approved successfully.'))->success();
        return redirect()->back();
    }

    public function waletReject(Request $request)
    {
        $wallet = Wallet::findOrFail($request->wallet_id);
        $wallet->status = 2;
        $wallet->save();

        flash(translate('Recharge rejected successfully.'))->success();
        return redirect()->back();
    }
}
