@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="col-sm-12">
                @if(empty($model))
                    <h2>
                        {!! trans('global.create_new',['name'=>trans('admin.user')]) !!}
                    </h2>
                @else
                    <h2>
                        {!! trans('global.edit').' '.trans('admin.user') !!} [ {{$model->name}} ]
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
                <form action="{{url('admin/users')}}" method="post" class="myForm">
                @else
                <form action="{{url('admin/users/'.$model->id)}}" method="post" class="form">
                    {{ method_field('PUT') }}
                @endif
                {{csrf_field()}}
                <div class="form-group">
                    <label for="name">نام :</label>
                    <input type="text" class="form-control" value="{{@$model->name}}" name="name" id="name" placeholder="نام ..." required>
                </div>
                <div class="form-group">
                    <label for="mobile">موبایل :</label>
                    <input type="text" class="form-control" value="{{@$model->mobile}}"  name="mobile" id="mobile" placeholder="موبایل ..." required>
                </div>
                <div class="form-group">
                    <label for="username">نام کاربری :</label>
                    <input type="text" class="form-control" value="{{@$model->username}}" name="username" id="username" placeholder="نام کاربری ..." required>
                </div>
                <div class="form-group">
                    <label for="email">ایمیل :</label>
                    <input type="email" class="form-control" value="{{@$model->email}}"  name="email" id="email" placeholder="ایمیل ..." required>
                </div>
                @if(empty($model))
                <div class="form-group">
                    <label for="password">رمز عبور :</label>
                    <input type="password" class="form-control"  name="password" id="password" placeholder="رمز عبور ..." required>
                </div>
                @else
                <div class="form-group">
                    <label for="password">رمز عبور [ برای تغییر فیلد زیر را پر کنید ]:</label>
                    <input type="password" class="form-control"  name="password" id="password" placeholder="رمز عبور [ برای تغییر فیلد زیر را پر کنید ] ..." >
                </div>
                @endif
                <hr>
                <div class="form-group">
                    <button class="btn btn-primary"><span class="fa fa-save"></span> {!! trans('global.save').' '.trans('admin.user') !!}</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection