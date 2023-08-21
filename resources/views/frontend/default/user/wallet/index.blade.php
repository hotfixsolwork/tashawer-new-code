@extends('frontend.default.layouts.app')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">

                @if (isClient())
                    @include('frontend.default.user.client.inc.sidebar')
                @elseif (isFreelancer())
                    @include('frontend.default.user.freelancer.inc.sidebar')
                @endif
                <div class="aiz-user-panel">
                    <div class="aiz-titlebar mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="fs-16 fw-700">{{ translate('My Wallet') }}</h1>
                            </div>
                        </div>
                    </div>

                    <div class="row gutters-10">
                        <div class="col-md-4 mx-auto mb-3">
                            <div class="bg-grad-3 text-white rounded-lg overflow-hidden rounded-2">
                                <span
                                    class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                                    <i class="las la-dollar-sign la-2x text-white"></i>
                                </span>
                                <div class="px-3 pt-3 pb-3">
                                    <div class="h4 fw-700 text-center">{{ single_price(Auth::user()->profile->balance) }}
                                    </div>
                                    <div class="opacity-50 text-center">{{ translate('Wallet Balance') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mx-auto mb-3">
                            <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition rounded-2"
                                onclick="show_wallet_modal()">
                                <span
                                    class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                                    <i class="las la-plus la-3x text-white"></i>
                                </span>
                                <div class="fs-18 text-primary">{{ translate('Recharge Wallet') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-gray-light">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('My Wallet') }}</h5>
                        </div>
                        <div class="card-body">
                            <table class="table aiz-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th data-breakpoints="lg">{{ translate('Date') }}</th>
                                        <th>{{ translate('Amount') }}</th>
                                        <th data-breakpoints="lg">{{ translate('Bank Name') }}</th>
                                        <th>{{ translate('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wallets as $key => $wallet)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ date('d-m-Y', strtotime($wallet->created_at)) }}</td>
                                            <td>{{ single_price($wallet->amount) }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $wallet->bank_name)) }}</td>
                                            <td>
                                                @if($wallet->status == 0)
                                                Pending
                                                @elseif($wallet)
                                                Approved
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="aiz-pagination">
                                {{ $wallets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <div class="modal fade" id="wallet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Recharge Wallet') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="" action="{{ route('wallet.recharge') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{-- <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ translate('Amount')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" lang="en" class="form-control mb-3" min="1" name="amount" placeholder="{{ translate('Amount')}}" required>
                            </div>
                        </div>
                        <div class="row">
                            @if (get_setting('paypal_activation_checkbox'))
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
                            <!--@if (get_setting('stripe_activation_checkbox'))
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
                            @endif-->
                           <!-- @if (get_setting('sslcommerz_activation_checkbox'))
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
                            @endif-->
                           <!-- @if (get_setting('paystack_activation_checkbox'))
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
                            @if (get_setting('instamojo_activation_checkbox'))
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
                            @if (get_setting('paytm_activation_checkbox'))
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
                            @endif-->
                        </div>
                      <div class="form-group text-right">
                          <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1 rounded-1">{{translate('Confirm')}}</button>
                      </div>
                  </div> --}}
                    <div style="padding: 15px">
                        <div>
                            <label>{{ translate('Amount') }} <span class="text-danger">*</span></label>
                        </div>
                        <div>
                            <input type="number" lang="en" class="form-control mb-3 rounded-0" name="amount"
                                placeholder="{{ translate('Amount') }}" required>
                        </div>
                        <div>
                            <div>
                                <label>{{ translate('Bank Name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div>
                                <div class="mb-3">
                                    <select class="form-control selectpicker rounded-0" name="bank_name">
                                        <option value="Saudi Investment Bank"
                                            {{ old('bank_name') == 'Saudi Investment Bank' ? 'selected' : '' }}>
                                            {{ translate('Saudi Investment Bank') }}</option>
                                        <option value="Riyad Bank"
                                            {{ old('bank_name') == 'Riyad Bank' ? 'selected' : '' }}>
                                            {{ translate('Riyad Bank') }}</option>
                                        <option value="Al Rajhi Bank"
                                            {{ old('bank_name') == 'Al Rajhi Bank' ? 'selected' : '' }}>
                                            {{ translate('Al Rajhi Bank') }}</option>
                                        <option value="Al Rajhi Bank"
                                            {{ old('bank_name') == 'Al Rajhi Bank' ? 'selected' : '' }}>
                                            {{ translate('Arab National Bank') }}</option>
                                        <option value="Bank AlBilad"
                                            {{ old('bank_name') == 'Bank AlBilad' ? 'selected' : '' }}>
                                            {{ translate('Bank AlBilad') }}</option>
                                        <option value="Saudi National Bank"
                                            {{ old('bank_name') == 'Saudi National Bank' ? 'selected' : '' }}>
                                            {{ translate('Saudi National Bank') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label>{{ translate('Bank Deposit Slip') }} <span class="text-danger">*</span></label>
                        </div>
                        <div>
                            <input type="file" class="form-control mb-3" name="deposit_slip" required />
                        </div>

                        <div style="text-align:center">
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-0 transition-3d-hover mr-1">{{ translate('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_wallet_modal() {
            $('#wallet_modal').modal('show');
        }
    </script>
@endsection
