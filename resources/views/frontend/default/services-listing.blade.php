@extends('frontend.default.layouts.app')

@section('content')

<section class="py-5">
    <div class="container">

        <form id="service-filter-form" action="" method="GET">
                <div class="row gutters-15">
                    <div class="col-xl-3 col-lg-4">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-lg z-1035">
                            <div class="card rounded-0 border-0 collapse-sidebar c-scrollbar-light shadow-none">
                                <div class="card-header border-0 pl-lg-0">
                                    <h5 class="mb-0 fs-21 fw-700">{{ translate('Filter By') }}</h5>
                                    <button class="btn btn-sm p-2 d-lg-none filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" type="button">
                                        <i class="las la-times la-2x"></i>
                                    </button>
                                </div>
                                <div class="card-body pl-lg-0">
                                    <div class="mb-5">
                                        <h6 class="text-left mb-3 fs-14 fw-700">
                                            <span class="bg-white pr-3">{{ translate('Categories') }}</span>
                                        </h6> 
                                         <div class="">
                                            <select class="rounded-2 select2 form-control aiz-selectpicker" name="category_id" onchange="location = this.value;" data-toggle="select2" data-live-search="true">  
                                                <option value="{{ route('services.category', '') }}">{{ translate('All Categories') }}</option> 
                                                @foreach(\App\Models\ProjectCategory::all() as $category)
                                                    <option value="{{ route('services.category', $category->slug) }}" @if (isset($_GET['category'])&& $_GET['category'] == $category->slug )
                                                        selected
                                                    @endif>
                                                    {{$category->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8">
                        <div class="mb-lg-0">
                            <input type="hidden" name="type" value="service"> 
                            <div class="row gutters-15">
                                    @foreach($services as $service)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-transparent rounded-2 border-gray-light overflow-hidden hov-box">
                                            <a href="{{ route('service.show', $service->slug) }}">
                                                @if($service->image != null)
                                                    <img src="{{ custom_asset($service->image) }}" class="card-img-top" alt="service_image" height="212" style="border-radius: 16px 16px 0px 0px;">
                                                @else
                                                    <img src="{{ my_asset('assets/frontend/default/img/placeholder-service.jpg') }}" class="card-img-top" alt="{{ translate('Service Image') }}" height="212" style="border-radius: 16px 16px 0px 0px;">
                                                @endif
                                            </a>
                                            <div class="card-body hov-box-body">
                                                <div class="d-flex mb-2">
                                                    <span class="mr-2">
                                                        @if ($service->user->photo != null)
                                                            <img src="{{ custom_asset($service->user->photo) }}" alt="{{ translate('image') }}" height="35" width="35" class="rounded-circle">
                                                        @else
                                                            <img src="{{ my_asset('assets/frontend/default/img/avatar-place.png') }}" alt="{{ translate('image') }}" height="35" width="35" class="rounded-circle">
                                                        @endif
                                                    </span>
                                                    <span class="d-flex flex-column justify-content-center">
                                                        <a href="{{ route('freelancer.details', $service->user->user_name) }}" class="text-secondary fs-14"><span class="font-weight-bold">{{ $service->user->name }}</span></a>
                                                    </span>
                                                </div>
                                                
                                                <a href="{{ route('service.show', $service->slug) }}" class="text-dark"  title="{{ $service->title }}">
                                                    <h5 class="card-title fs-16 fw-700 h-40px">{{ \Illuminate\Support\Str::limit($service->title, 45, $end='...') }}</h5>
                                                </a>
                                                <div class="text-warning">
                                                    <span class="rating rating-lg rating-mr-1">
                                                        {{ renderStarRating(getAverageRating($service->user->id)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                @endforeach    
                            </div>   
                            <div class="aiz-pagination aiz-pagination-center flex-grow-1">
                                <ul class="pagination">
                                    {{ $services->links() }}
                                </ul>
                            </div> 
                        </div>
                    </div>
                </div>
            </form> 
    </div>
</section>

@endsection

@section('modal')
    @include('admin.default.partials.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function applyFilter(){
            $('#service-filter-form').submit();
        }
    </script>
@endsection
