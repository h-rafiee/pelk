@extends('admin.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/selectize/css/selectize.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/selectize/css/selectize.default.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/pwtdatepicker/css/persian-datepicker-0.4.5.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/jquery-alertable/css/jquery.alertable.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-12">
                @if(empty($model))
                    <h2>
                        {!! trans('global.create_new',['name'=>trans('admin.book')]) !!}
                    </h2>
                @else
                    <h2>
                        {!! trans('global.edit').' '.trans('admin.book') !!} [ {{$model->title}} ]
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
                <form action="{{url('admin/books')}}" method="post" onsubmit="return dosubmit()" class="form" enctype="multipart/form-data">
                @else
                <form action="{{url('admin/books/'.$model->id)}}" onsubmit="return dosubmit()" method="post" class="form" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                @endif
                        {{csrf_field()}}
                        <div class="col-md-8">
                            <div class="panel panel-default">
                                @if(@$model)
                                    <div class="panel-heading">
                                        فایل کتاب <a href="{!! url($model->file) !!}" target="_blank">{!! $model->title !!}</a>
                                    </div>
                                @endif
                                <div class="panel-body clickable" data-click="fileToUpload">
                                    <div class="text-muted text-center">
                                        <i class="fa fa-file-o"></i>
                                        <span class="text-file">
                                            فایل کتاب را انتخاب کنید
                                        </span>
                                        @if(@$model)
                                            <input type="text" id="file" name="file" class="input-hidden" >
                                        @else
                                            <input type="text" id="file" name="file" class="input-hidden" required>
                                        @endif
                                    </div>
                                </div>
                                <div class="panel-footer hidden" id="progress_position">
                                    <div class="message hidden">
                                        <i class="fa fa-check fa-lg text-success"></i>
                                        فایل آپلود شد
                                    </div>
                                    <div class="progress hidden" style="margin-bottom: 0px;height: 5px;">
                                        <div id="progressbar" class="hid progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                            <span class="sr-only">45% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title">عنوان :</label>
                                <input type="text" class="form-control" value="{{@$model->title}}" name="title" id="title" placeholder="عنوان ..." required>
                            </div>
                            <div class="form-group">
                                <label for="tag">برچسب :</label>
                                <select name="tags[]" class="tag-selectize" multiple placeholder="برچسب را انتخاب کنید...">
                                    @if(@$model)
                                        @foreach($model->tags as $key=>$value)
                                            <option value="{{$value->id}}" selected="selected">{{$value->value}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="panel panel-default">
                                @if(@$model->file_demo)
                                    <div class="panel-heading">
                                        نمونه فایل کتاب <a href="{!! url($model->file_demo) !!}" target="_blank">{!! $model->title !!}</a>
                                    </div>
                                @endif
                                <div class="panel-body clickable" data-click="fileDemoToUpload">
                                    <div class="text-muted text-center">
                                        <i class="fa fa-file-o"></i>
                                        <span class="text-file">
                                            نمونه فایل کتاب را انتخاب کنید
                                        </span>
                                        <input type="text" id="file_demo" name="file_demo" class="input-hidden" >
                                    </div>
                                </div>
                                <div class="panel-footer hidden" id="progress_position_demo">
                                    <div class="message hidden">
                                        <i class="fa fa-check fa-lg text-success"></i>
                                        فایل آپلود شد
                                    </div>
                                    <div class="progress hidden" style="margin-bottom: 0px;height: 5px;">
                                        <div id="progressbar_demo" class="hid progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                            <span class="sr-only">45% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="writers">نویسنده :</label>
                                <select name="writers[]" class="writer-selectize" required multiple placeholder="نویسنده را انتخاب کنید...">
                                    @if(@$model)
                                        @foreach($model->writers as $key=>$value)
                                            <option value="{{$value->id}}" selected="selected">{{$value->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="translators">مترجم :</label>
                                <select name="translators[]" class="translator-selectize" multiple placeholder="مترجم را انتخاب کنید...">
                                    @if(@$model)
                                        @foreach($model->translators as $key=>$value)
                                            <option value="{{$value->id}}" selected="selected">{{$value->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="publication">ناشر :</label>
                                <select name="publication" class="publication-selectize" required placeholder="ناشر را انتخاب کنید...">
                                    @if(@$model)
                                        <option value="{{$model->publication->id}}" selected="selected">{{$model->publication->title}}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="isbn">شابک :</label>
                                <input type="text" class="form-control" value="{{@$model->isbn}}" name="isbn" id="isbn" placeholder="شابک ..." required>
                            </div>
                            <div class="form-group">
                                <label for="publish_date">تاریخ انتشار :</label>
                                <input type="text" class="form-control pwtdatepicker" value="{{@$model->publish_date}}" name="publish_date" id="publish_date" placeholder="تاریخ انتشار ..." required>
                            </div>
                            <div class="form-group">
                                <label for="price">قیمت :</label>
                                <input type="number" class="form-control" value="{{@$model->price}}" name="price" id="price" placeholder="قیمت ..." min="0">
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="description">خلاصه متن :</label>
                                <textarea class="form-control" name="description" id="description" placeholder="خلاصه متن ..." required >{{@$model->description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="text">شرح متن :</label>
                                <textarea class="tinyMCE form-control" name="text" id="text" placeholder="شرح متن ..." >{{@$model->text}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <p>
                                    @if(@$model)
                                        <label for="active"><input type="checkbox" id="active" name="active" value="is_active" {{(@$model->active==1)?'checked':''}}> انتشار</label>
                                    @else
                                        <label for="active"><input type="checkbox" id="active" name="active" value="is_active" checked> انتشار</label>
                                    @endif
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="category">دسته بندی :</label>
                                <select class="mselectize" name="category" placeholder="دسته بندی را انتخاب کنید..." id="category"  required>
                                    <option></option>
                                    @foreach($categories as $category)
                                        @if(@$model->category_id == $category->id)
                                            <option value="{{$category->id}}" selected="selected">{{$category->title}}</option>
                                        @else
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="post_image">تصویر شاخص : </label>
                                <input type="file" data-point="preview1" name="image" id="post_image" class="uploadFile hidden" >
                                <br>
                                <div class="img-thumbnail text-center col-sm-12 link clickable" data-click="post_image">
                                    @if(@$model->image)
                                        <img src="{{url($model->image)}}" class="img-thumbnail"  data-img="preview1" width="150" height="230">
                                    @else
                                        <img class="img-thumbnail"  data-img="preview1" src="holder.js/150x230?font=FontAwesome&text=&#xf03e&size=40" width="150" height="230">
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <button class="btn btn-primary"><span class="fa fa-save"></span> {!! trans('global.save').' '.trans('admin.book') !!}</button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <input type="file" id="fileToUpload" class="hidden">
    <input type="file" id="fileDemoToUpload" class="hidden">
@endsection

@section('scripts')
    @include('admin.objects.scripts_tinymce')
    @include('admin.objects.scripts_selectize',[
    'tags'=>$tags,
    'publications'=>$publications,
    'writers'=>$writers,
    'translators'=>$translators])
    @include('admin.objects.scripts_pwtdatepicker')
    @include('admin.objects.scripts_jqueryalertable')
    <script src="{{asset('assets/admin/vendor/holder/holder.js')}}"></script>
    @include('admin.objects.script_create_post')
@endsection