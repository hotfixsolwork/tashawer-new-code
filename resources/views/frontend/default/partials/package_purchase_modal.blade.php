
<h6>{{ $package->name }} ({{ single_price($package->price) }})</h6>
<form class="form-horizontal mt-2" action="{{ route('purchase_package') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="form-control form-control-sm" name="package_id" value="{{ $package->id }}">
    <div class="form-group">
        <label class="form-label">
            {{translate('Payment System')}}
            <span class="text-danger">*</span>
        </label>
        <div class="form-group">
            <div class="row">
                @if(get_setting('paypal_activation_checkbox'))
                    <div class="col-6 col-md-4">
                        <label class="aiz-megabox d-block mb-3">
                            <input value="paypal" id="payment_option" type="radio" name="payment_option" checked>
                            <span class="d-block p-3 aiz-megabox-elem">
                                <img src="{{ my_asset('assets/frontend/default/img/paypal.png') }}" class="img-fluid mb-2">
                                <span class="d-block text-center">
                                    <span class="d-block fw-600 fs-15">{{ translate('Paypal') }}</span>
                                </span>
                            </span>
                        </label>
                    </div>
                @endif
                @if(get_setting('stripe_activation_checkbox'))
                    <div class="col-6 col-md-4">
                        <label class="aiz-megabox d-block mb-3">
                            <input value="stripe" id="payment_option" type="radio" name="payment_option" checked>
                            <span class="d-block p-3 aiz-megabox-elem">
                                <img src="{{ my_asset('assets/frontend/default/img/stripe.png') }}" class="img-fluid mb-2">
                                <span class="d-block text-center">
                                    <span class="d-block fw-600 fs-15">{{ translate('Stripe') }}</span>
                                </span>
                            </span>
                        </label>
                    </div>
                @endif
                @if(get_setting('sslcommerz_activation_checkbox'))
                    <div class="col-6 col-md-4">
                        <label class="aiz-megabox d-block mb-3">
                            <input value="sslcommerz" id="payment_option" type="radio" name="payment_option" checked>
                            <span class="d-block p-3 aiz-megabox-elem">
                                <img src="{{ my_asset('assets/frontend/default/img/sslcommerz.png') }}" class="img-fluid mb-2">
                                <span class="d-block text-center">
                                    <span class="d-block fw-600 fs-15">{{ translate('sslcommerz') }}</span>
                                </span>
                            </span>
                        </label>
                    </div>
                @endif
                @if(get_setting('paystack_activation_checkbox'))
                    <div class="col-6 col-md-4">
                        <label class="aiz-megabox d-block mb-3">
                            <input value="paystack" id="payment_option" type="radio" name="payment_option" checked>
                            <span class="d-block p-3 aiz-megabox-elem">
                                <img src="{{ my_asset('assets/frontend/default/img/paystack.png') }}" class="img-fluid mb-2">
                                <span class="d-block text-center">
                                    <span class="d-block fw-600 fs-15">{{ translate('Paystack') }}</span>
                                </span>
                            </span>
                        </label>
                    </div>
                @endif
                @if(get_setting('instamojo_activation_checkbox'))
                    <div class="col-6 col-md-4">
                        <label class="aiz-megabox d-block mb-3">
                            <input value="instamojo" id="payment_option" type="radio" name="payment_option" checked>
                            <span class="d-block p-3 aiz-megabox-elem">
                                <img src="{{ my_asset('assets/frontend/default/img/instamojo.png') }}" class="img-fluid mb-2">
                                <span class="d-block text-center">
                                    <span class="d-block fw-600 fs-15">{{ translate('Instamojo') }}</span>
                                </span>
                            </span>
                        </label>
                    </div>
                @endif
                @if(get_setting('paytm_activation_checkbox'))
                    <div class="col-6 col-md-4">
                        <label class="aiz-megabox d-block mb-3">
                            <input value="paytm" id="payment_option" type="radio" name="payment_option" checked>
                            <span class="d-block p-3 aiz-megabox-elem">
                                <img src="{{ my_asset('assets/frontend/default/img/paytm.png') }}" class="img-fluid mb-2">
                                <span class="d-block text-center">
                                    <span class="d-block fw-600 fs-15">{{ translate('Paytm') }}</span>
                                </span>
                            </span>
                        </label>
                    </div>
                @endif
                @if(Auth::user()->profile->balance >= $package->price)
                    <div class="col-6 col-md-4">
                        <label class="aiz-megabox d-block mb-3">
                            <input value="wallet" id="payment_option" type="radio" name="payment_option" checked>
                            <span class="d-block p-3 aiz-megabox-elem">
                                <img src="{{ my_asset('assets/frontend/default/img/wallet.png') }}" class="img-fluid mb-2">
                                <span class="d-block text-center">
                                    <span class="d-block fw-600 fs-15">{{ translate('Wallet') }}</span>
                                </span>
                            </span>
                        </label>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group text-right">
        <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1 rounded-1">{{translate('Pay')}}</button>
    </div>
</form>
