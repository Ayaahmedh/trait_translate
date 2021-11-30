@extends('admin.layouts.app')
@section('breadcrumbs')
    {!! Breadcrumbs::render('categories.index') !!}
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>Create Ctegory </h2>
                </div>
                {{Form::open(['action'=>'','id'=>'basic-form'])}}

                <div class="body">
                    {!! BootForm::select('model', 'Module name ',['all'=>'All','courses'=>'E-learning','tenders'=>'Tenders','companies'=>'Companies','news'=>'News','projects'=>'Projects','publications'=>'Publications','galleries'=>'Galleries'], null, $errors,['required','class'=>'form-control']) !!}

                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#Home">English</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Profile">العربية</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="Home">
                            <div class="form-group">
                                {!! BootForm::input('text', 'en[name]', null, 'Name (en)', $errors,['required','class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! BootForm::input('text', 'en[tag]', null, 'Category Tag (en)', $errors,['required','class'=>'form-control']) !!}
                            </div>
                            <br>
                        </div>
                        <div class="tab-pane" id="Profile">
                            <div class="form-group">
                                {!! BootForm::input('text', 'ar[name]', null, 'Name (ar)', $errors,['required','class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! BootForm::input('text', 'ar[tag]', null, 'Category Tag (ar)', $errors,['required','class'=>'form-control']) !!}
                            </div>
                            <br>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/vendor/parsleyjs/js/parsley.min.js') }}"></script>
    <script>
        $(function() {
            // initialize after multiselect
            $('#basic-form').parsley();
        });
    </script>
@endsection