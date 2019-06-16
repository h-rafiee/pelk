@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="col-sm-12">
                @if(empty($model))
                    <h2>
                        {!! trans('global.create_new',['name'=>trans('admin.tag')]) !!}
                    </h2>
                @else
                    <h2>
                        {!! trans('global.edit').' '.trans('admin.tag') !!} [ {{$model->value}} ]
                    </h2>
                @endif

                <hr>

                {{--Messages--}}
                @if (count($errors) > 0)
                    @foreach ($errors->all() as $error)
                        @include('admin.objects.alert_danger',['error'=>$error])
                    @endforeach
                @endif
                @if (session('success'))
                    @include('admin.objects.alert_success',['success'=>session('success')])
                @endif
                {{--END MESSAGES--}}

                @if(empty($model))
                <form action="{{url('admin/tags')}}" method="post" class="form">
                @else
                <form action="{{url('admin/tags/'.$model->id)}}" method="post" class="myForm">
                {{ method_field('PUT') }}
                @endif
                {{csrf_field()}}
                <div class="form-group">
                    <label for="value">عنوان برچسب :</label>
                    <input type="text" class="form-control" value="{{@$model->value}}" name="value" id="value" placeholder="عنوان برچسب ..." required>
                </div>
                <hr>
                <div class="form-group">
                    <button class="btn btn-primary"><span class="fa fa-save"></span> {!! trans('global.save').' '.trans('admin.tag') !!}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
