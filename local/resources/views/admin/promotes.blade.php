@extends('admin.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/jquery-alertable/css/jquery.alertable.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h1>{!! trans('admin.sliders') !!}</h1>
                <a href="sliders/create"><span
                            class="fa fa-file-o"></span> {!! trans('global.create_new',['name'=>trans('admin.slider')]) !!}
                </a>
            </div>
        </div>
    </div>

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

    <div class="row">
        <div class="col-lg-12">
            @if(count($model)>0)
                <table width="100%" class="table table-striped table-bordered table-hover dataTable">
                    <thead>
                    <th>#</th>
                    <th width="50%">عنوان</th>
                    <th>تاریخ ایجاد</th>
                    <th colspan="2" class="text-center">{!! trans('global.access') !!}</th>
                    </thead>
                    <tbody class="tahoma">
                    <?php $i = 1; ?>
                    @foreach($model as $value)
                        <tr id="rowID{{$value->id}}">
                            <td>{{$i++}}</td>
                            <td>{{$value->title}}</td>
                            <td>{{date("M Y j",strtotime($value->created_at))}}</td>
                            <td><a href="{{url("admin/sliders/{$value->id}/edit")}}" class="text-warning"><span
                                            class="fa fa-pencil"></span> {!! trans('global.edit') !!}</a></td>
                            <td><a class="remove confirm text-danger" href="javascript.void(0)"
                                   data-remove="rowID{{$value->id}}" data-token="{{csrf_token()}}"
                                   data-title="{!! trans('admin.confirm_delete',['val'=>$value->title]) !!}"
                                   data-link="{{url("admin/sliders/{$value->id}")}}"><span
                                            class="fa fa-trash"></span> {!! trans('global.delete') !!}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$model->links()}}
            @else
                <div class="well">
                    <p class="text-center">{!! trans('admin.non_item_found') !!}</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.objects.scripts_jqueryalertable')
@endsection
