@extends('admin.layouts.app')
@section('name','News')
@section('styles')
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ url('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('breadcrumbs')
    {!! Breadcrumbs::render('news.index') !!}
@stop
@section('content')
    <div class="row clearfix">

        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>Create News </h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs">
                        @foreach($locales as $k=>$local)
                            <li class="nav-item"><a class="nav-link {{($k==0)?'active show':''}}" data-toggle="tab"
                                                    href="#{{ $local->code}}_tab">{{ $local->local_name}}</a></li>
                        @endforeach
                    </ul>
                    {!! BootForm::open('create', ['url'=>route('news.store'),'novalidate','id'=>'basic-form','files'=>true]) !!}
                    <div class="tab-content">
                        @foreach($locales as $k=>$local)
                            <div class="tab-pane {{($k==0)?'active show':''}}" id="{{ $local->code}}_tab">
                                {!! BootForm::input('text', $local->code.'[title]', null, 'Title '.$local->code, $errors,['required','class'=>'form-control']) !!}
                                {!! BootForm::input('text', $local->code.'[tag]', null, 'Tag '.$local->code, $errors,['required','class'=>'form-control']) !!}

                                {!! BootForm::textarea( $local->code.'[description]', null, 'Description '.$local->code, $errors,['required','id'=>'my-editor'.$local->code]) !!}
                            </div>
                        @endforeach
                        {!! BootForm::select('category_id','Category',$categories,null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'date', null, 'Date', $errors, ['class'=>'form-control', "data-date-autoclose"=>"true",'id'=>'date']) !!}
                        {!! BootForm::file('thumb_image', ' Image', $errors, ['accept'=>'jpg,jpeg,svg,png']) !!}
                        {!! BootForm::checkbox('push_to','  Push to 2nd website WPE',1) !!}
                        {!! BootForm::submit() !!}
                    </div>
                    {!! BootForm::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/vendor/parsleyjs/js/parsley.min.js') }}"></script>
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
        };
    </script>
    <script>
        @foreach($locales as $k=>$local)
        CKEDITOR.replace('my-editor{{$local->code}}', options);
        @endforeach
    </script>
    <!-- datepicker -->
    <script src="{{ url('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script>
        $('#date').datepicker({
            format: 'yyyy-mm-dd'
        });
        $(function () {
            // initialize after multiselect
            $('#basic-form').parsley();
        });
    </script>


@stop