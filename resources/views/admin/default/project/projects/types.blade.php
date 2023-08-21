@extends('admin.default.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">{{ translate('Project Types') }}</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#typeModal">
                Create New Type
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Type') }}</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($types as $key => $type)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $type->type }}</td>
                                    <td class="text-right">
                                        <a href="#"
                                            data-href="{{ route('project_types.destroy', encrypt($type->id)) }}"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination aiz-pagination-center">
                        {{ $types->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="typeModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h6 class="modal-title">New Type</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('project_types.store') }}" method="POSt" id="typeForm">
                        @csrf
                        <div class="form-group">
                            <label for="">Type</label>
                            <input type="text" name="type" class="form-control" required>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-0">
                    <button type="submit" form="typeForm" class="btn btn-success">Submit</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('modal')
    @include('admin.default.partials.delete_modal')
@endsection
@section('script')
    <script type="text/javascript">
        function sort_projects(el) {
            $('#sort_projects').submit();
        }
    </script>
@endsection
