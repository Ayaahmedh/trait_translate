@extends('admin.layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
    <style>
        td.details-control {
            background: url('{{ asset('assets/images/details_open.png') }}') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('{{ asset('assets/images/details_close.png') }}') no-repeat center center;
        }
    </style>
@endsection
@section('name','Categories')
@section('breadcrumbs')
    {!! Breadcrumbs::render('categories.index') !!}
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            <div class="card">
                <div class="body">
                    <a id="addToTable" class="btn btn-primary m-b-15" href="{{route('categories.create')}}">
                        <i class="icon wb-plus" aria-hidden="true"></i> Add row
                    </a>

                    <a id="addToTable" class="btn btn-primary m-b-15" href="{{route('categories.export')}}">
                        <i class="icon wb-plus" aria-hidden="true"></i> Export to Excel
                    </a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" cellspacing="0"
                               id="addrowExample">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Module</th>
                                <th>Actions</th>

                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/tables/editable-table.js') }}"></script>
    <script src="{{ asset('assets/vendor/editable-table/mindmup-editabletable.js') }}"></script> <!-- Editable Table Plugin Js -->
    <script type="text/javascript">
        $(function () {
            $('#addrowExample').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('categories.datatable') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'model', name: 'model'},
                    {data: 'action', name: 'action'}

                ]
            });
        });
    </script>
    <!--bootbox -->

    <script src="{{ url('assets/vendor/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/js/index.js') }}"></script>

@endsection