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
@section('name','news')
@section('breadcrumbs')
    {!! Breadcrumbs::render('news.index') !!}
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <a id="addToTable" class="btn btn-primary m-b-15" href="{{route('news.create')}}">
                        <i class="icon wb-plus" aria-hidden="true"></i> Add row
                    </a>
                    <form action="{{ route('news.destroyAll') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="post">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" cellspacing="0"
                               id="addrowExample">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Id</th>
                                <th>Title En</th>
                                <th>Title Ar</th>
                                <th>Date</th>
                                <th>Actions</th>

                            </tr>
                            </thead>

                        </table>
                    </div>
                        <button class="btn btn-danger">Delete Checked</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            table = $('#addrowExample').DataTable({
                processing: true,
                serverSide: true,
                ServerMethod: "GET",
                ajax: "{{ route('news.index') }}",
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'title_ar', name: 'title_ar'},
                    {data: 'date', name: 'date'},
                    {data: 'action', name: 'action'}

                ]
            });
        });

    </script>
    <!--bootbox -->
    <script src="{{ url('assets/vendor/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/js/index.js') }}"></script>

@endsection