<?php

namespace App\Http\Controllers\Auth;

use Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\EmailManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

      public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        // $request->user()->sendEmailVerificationNotification();
        $array['view'] = 'emails.verification';
        $array['subject'] = "Email Verification";
        $array['from'] = env('MAIL_USERNAME');
        $array['content'] =  translate('Please click the button below to verify your email address.');
        $array['link'] = route('email.verification.confirmation', $request->user()->verification_code);

        try {
            Mail::to($request->user()->email)->send(new EmailManager($array));
        } catch (\Exception $e) {
            // dd($e);
        }
        return back()->with('resent', true);
    }

    public function verification_confirmation($code){
        $user = User::where('verification_code', $code)->first();
        if($user != null){
            $user->email_verified_at = Carbon::now();
            $user->save();
            auth()->login($user, true);
            flash(translate('Your email has been verified successfully'))->success();
        }
        else {
            flash(translate('Sorry, we could not verifiy you. Please try again'))->error();
        }

        if($user->user_type == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
