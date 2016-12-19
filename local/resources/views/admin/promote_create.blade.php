@extends('admin.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/jquery-alertable/css/jquery.alertable.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="col-sm-12">
                @if(empty($model))
                    <h2>
                        {!! trans('global.create_new',['name'=>trans('admin.slider')]) !!}
                    </h2>
                @else
                    <h2>
                        {!! trans('global.edit').' '.trans('admin.slider') !!} [ {{$model->value}} ]
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
                <form action="{{url('admin/web/sliders')}}" method="post" class="form" enctype="multipart/form-data">
                @else
                <form action="{{url('admin/web/sliders/'.$model->id)}}" method="post" class="form" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                @endif
                {{csrf_field()}}
                <div class="form-group">
                    <label for="title">عنوان :</label>
                    <input type="text" class="form-control" value="{{@$model->title}}" name="title" id="title" placeholder="عنوان ..." required>
                </div>
                <div class="form-group">
                    <label for="content">متن :</label>
                    <input type="text" class="form-control" value="{{@$model->content}}" name="content" id="content" placeholder="متن ..." >
                </div>
                <div class="form-group">
                    <label for="link">لینک :</label>
                    <input type="text" class="form-control" value="{{@$model->link}}" name="link" id="link" placeholder="لینک ..." >
                </div>
                <div class="form-group">
                    <label for="position">محل قرارگیری :</label>
                    <input type="number" min="0" class="form-control" value="{{@$model->position}}" name="position" id="position" placeholder="محل قرارگیری ..." >
                </div>
                <div class="form-group">
                    <label for="post_image">تصویر : </label>
                    <input type="file" data-point="preview1" name="image" id="post_image" class="uploadFile hidden" >
                    <br>
                    <div class="img-thumbnail text-center col-sm-12 link clickable" data-click="post_image">
                        @if(@$model->image)
                            <img src="{{url($model->image)}}" class="img-thumbnail"  data-img="preview1" width="400" height="140">
                        @else
                            <img class="img-thumbnail"  data-img="preview1" src="holder.js/400x140?font=FontAwesome&text=&#xf03e&size=40" width="400" height="140">
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr>
                <div class="form-group">
                    <button class="btn btn-primary"><span class="fa fa-save"></span> {!! trans('global.save').' '.trans('admin.slider') !!}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('assets/admin/vendor/holder/holder.js')}}"></script>
    @include('admin.objects.scripts_jqueryalertable')
    <script>
        $(".clickable").on('click',function(e){
            e.preventDefault();
            $("#"+$(this).attr('data-click')).click();
        });

        $(".uploadFile").on("change", function()
        {
            var select = $(this);
            var point = $(this).attr('data-point');
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test( files[0].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                var  img;
                reader.readAsDataURL(files[0]); // read the local file
                reader.onloadend = function(){ // set image data as background of div
                    img = new Image();
                    img.src = this.result;
                    img.onload = function () {
                            $("img[data-img='"+point+"']").attr('src',img.src);
                        }
                    };
                }
            }
        });
    </script>
@endsection
