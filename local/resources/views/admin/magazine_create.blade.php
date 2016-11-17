@extends('admin.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/selectize/css/selectize.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/pwtdatepicker/css/persian-datepicker-0.4.5.min.css')}}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-12">
                @if(empty($model))
                    <h2>
                        {!! trans('global.create_new',['name'=>trans('admin.magazine')]) !!}
                    </h2>
                @else
                    <h2>
                        {!! trans('global.edit').' '.trans('admin.magazine') !!} [ {{$model->title}} ]
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
                <form action="{{url('admin/magazines')}}" method="post" class="form" enctype="multipart/form-data">
                @else
                    <form action="{{url('admin/magazines/'.$model->id)}}" method="post" class="form" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @endif
                        {{csrf_field()}}
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
                            <label for="title">عنوان :</label>
                            <input type="text" class="form-control" value="{{@$model->title}}" name="title" id="title" placeholder="عنوان ..." required>
                        </div>
                        <div class="form-group">
                            <label for="category">دسته بندی :</label>
                            <select class="form-control" name="category" id="category"  required>
                                <option {{(@$model)?'':'selected="selected"'}} disabled>لطفا دسته مورد نظر را انتخاب کنید.</option>
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
                            <label for="tag">برچسب :</label>
                            <select name="tags[]" id="select-tools" multiple placeholder="برچسب را انتخاب کنید...">
                                @if(@$model)
                                    @foreach($model->tags as $key=>$value)
                                        <option value="{{$value->id}}" selected="selected">{{$value->value}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="publication">ناشر :</label>
                            <input type="text" class="form-control" value="{{@$model->publication->title}}" name="publication" id="publication" placeholder="ناشر ..." required>
                        </div>
                        <div class="form-group">
                            <label for="code">شماره مجله :</label>
                            <input type="text" class="form-control" value="{{@$model->code}}" name="code" id="code" placeholder="شماره مجله ...">
                        </div>
                        <div class="form-group">
                            <label for="publish_date">تاریخ انتشار :</label>
                            <input type="text" class="form-control pwtdatepicker" value="{{@$model->publish_date}}" name="publish_date" id="publish_date" placeholder="تاریخ انتشار ..." required>
                        </div>
                        <div class="form-group">
                            <label for="price">قیمت :</label>
                            <input type="number" class="form-control" value="{{@$model->price}}" name="price" id="price" placeholder="قیمت ..." min="0">
                        </div>
                        <div class="form-group">
                            <label for="file">فایل کتاب :</label>
                            <input type="file" name="file" id="file" placeholder="فایل کتاب ..." min="0">
                            @if(@$model)
                            <span id="helpBlock" class="help-block">فایل کتاب <a href="{!! url($model->file) !!}" target="_blank">{!! $model->title !!}</a></span>
                            @endif
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
                        <hr>
                        <div class="form-group">
                            <label for="post_image">تصویر شاخص : </label>
                            <input type="file" data-point="preview1" name="image" id="post_image" class="uploadFile" >
                            <br>
                            <div class="imagePreview" data-img="preview1" {{(@$model)?'style=display:inline-block;background-image:url('.url(@$model->image).');':''}}></div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <button class="btn btn-primary"><span class="fa fa-save"></span> {!! trans('global.save').' '.trans('admin.magazine') !!}</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.objects.scripts_tinymce')
    @include('admin.objects.scripts_selectize',['tags'=>$tags])
    @include('admin.objects.scripts_pwtdatepicker')
    <script>
        $(".uploadFile").on("change", function()
        {
            var point = $(this).attr('data-point');
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test( files[0].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function(){ // set image data as background of div
                    $(".imagePreview[data-img='"+point+"']").show();
                    $(".imagePreview[data-img='"+point+"']").css("background-image", "url("+this.result+")");
                }
            }
        });
    </script>
@endsection