@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-12">
                @if(empty($model))
                    <h2>
                        {!! trans('global.create_new',['name'=>trans('admin.category')]) !!}
                    </h2>
                @else
                    <h2>
                        {!! trans('global.edit').' '.trans('admin.category') !!} [ {{$model->title}} ]
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
                <form action="{{url('admin/categories')}}" method="post" class="form">
                @else
                <form action="{{url('admin/categories/'.$model->id)}}" method="post" class="form">
                {{ method_field('PUT') }}
                @endif
                {{csrf_field()}}
                <div class="form-group">
                    <label for="title">عنوان دسته بندی :</label>
                    <input type="text" class="form-control" value="{{@$model->title}}" name="title" id="title" placeholder="عنوان دسته بندی ..." required>
                </div>
                <hr>
                <div class="form-group">
                    <button class="btn btn-primary"><span class="fa fa-save"></span> {!! trans('global.save').' '.trans('admin.category') !!}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection