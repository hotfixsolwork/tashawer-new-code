@extends('admin.default.layouts.app')

@section('content')

    <div class="aiz-titlebar mt-2 mb-3">
		<div class="row align-items-center">
			<div class="col-md-6">
				<h1 class="h3">{{translate('Recharge Requests')}}</h1>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header row gutters-5">
					<div class="col text-center text-md-left">
						<h5 class="mb-md-0 h6">{{translate('Wallet Recharge')}}</h5>
					</div>
                </div>
                <div class="card-body">
                     <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Username')}}</th>
                            <th>{{  translate('Date') }}</th>
                            <th>{{ translate('Amount')}}</th>
                            <th data-breakpoints="lg" class="text-right">{{ translate('Bank Name')}}</th>
                            <th data-breakpoints="lg" class="text-right">{{ translate('Approval')}}</th>
                            <th data-breakpoints="lg" class="text-right">{{ translate ('Deposit Slip') }}</th>
                            <th data-breakpoints="lg" class="text-right">{{ translate ('Approve') }}</th>
                            <th data-breakpoints="lg" class="text-right">{{ translate ('Reject') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallets as $key => $wallet)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                @if ($wallet->user != null)
                                    <td>{{ $wallet->user->name }}</td>
                                @else
                                    <td>{{ translate('User Not found') }}</td>
                                @endif
                                <td>{{ date('d-m-Y', strtotime($wallet->created_at)) }}</td>
                                <td>{{ single_price($wallet->amount) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $wallet ->bank_name)) }}</td>
                                <td class="">
                                    @if ($wallet->status==0)
                                        <span class="badge badge-inline badge-info">{{translate('Pending')}}</span>
                                    @elseif($wallet->status==1)
                                        <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                                    @elseif($wallet->status==2)
                                        <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{asset('public/uploads/deposit_slip/'.$wallet->deposit_slip)}}"  target="_blank">{{ translate ('View Deposipt Slip') }}</a>
                                </td>
                                
                                @if($wallet->status == 0)
                                <td>
                                    <form action="{{route('wallet.request.approve')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="wallet_id" value="{{$wallet->id}}" />
                                        <button type="submit" onclick="return confirm('Sure to approve this depoist ?')" class="btn btn-success btn-sm">{{ translate ('Approve') }}</button>
                                    </form>
                                </td>
                                @else
                                    <td>---</td>
                                @endif
                                
                                
                                 @if($wallet->status == 0)
                                 <td>
                                    <form action="{{route('wallet.request.reject')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="wallet_id" value="{{$wallet->id}}" />
                                        <button type="submit" onclick="return confirm('Sure to reject this depoist ?')" class="btn btn-danger btn-sm">{{ translate ('Reject') }}</button>
                                    </form>
                                </td>
                                @else
                                 <td>---</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $wallets->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    function sort_projects(el){
        $('#sort_projects').submit();
    }
</script>
@endsection
