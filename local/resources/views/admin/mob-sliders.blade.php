@extends('admin.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/selectize/css/selectize.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/selectize/css/selectize.default.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/jquery-alertable/css/jquery.alertable.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-12">
                <h2>
                    {!! trans('admin.mobile').' | '.trans('admin.sliders') !!}
                </h2>
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
                <form action="{{url('admin/mobile/sliders')}}" onsubmit="return dosubmit()" method="post" class="form" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="col-md-8">
                    <div class="well ">
                        از قسمت چپ موارد دلخواه را اضافه کنید.
                    </div>
                    <ul class="list-unstyled" id="items">
                        @foreach(@$template as $key=> $value)
                            <li class="item-template">
                                <i class="fa fa-bars drag-handle"></i>
                                <br>
                                @if($value['banner']=='yes')
                                    <input type="hidden" name="item[has-banner][]" value="yes" >
                                    <div class="form-group">
                                        <input type="file" data-point="preview1" name="item[banner][]" class="uploadFile hidden" >
                                        <a href="javascript:void(0)" class="clickable">
                                            <img class="img-holder img-thumbnail  img-responsive"  data-img="preview1" src="{{url($value['file'])}}" height="150">
                                        </a>
                                    </div>
                                @else
                                    <input type="hidden" name="item[has-banner][]" value="no" >
                                @endif
                                <input type="hidden" class="old-file" name="item[banner-file][]" value="{{@$value['file']}}">
                                <div class="form-group">
                                    <label>{{$data[$value['type'].'-label']}}</label>
                                    <select class="mselectize" name="item[value][]">
                                        @foreach($data[$value['type']] as $key => $tvalue)
                                            <?php
                                            $itemTxt = '';
                                            if(!empty($tvalue->title)){
                                                $itemTxt = $tvalue->title;
                                            }
                                            if(!empty($tvalue->value)){
                                                $itemTxt = $tvalue->value;
                                            }
                                            if(!empty($tvalue->name)){
                                                $itemTxt = $tvalue->name;
                                            }
                                            ?>
                                            <option value="{{$tvalue->id}}" {!! ($tvalue->id == $value['value'])?'selected="selected"':'' !!}>{{$itemTxt}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="item[type][]" value="{{$value['type']}}">
                                </div>
                                <div class="clearfix"></div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="form-group">
                        <button class="btn btn-primary"><span class="fa fa-save"></span> {!! trans('global.save').' '.trans('admin.home-template') !!}</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <p>
                            <a href="javascript:void(0)" data-item="publications" data-banner="has" class="addItem btn btn-default"><i class="fa fa-plus"></i> ناشر</a>
                            <a href="javascript:void(0)" data-item="writers" data-banner="has"  class="addItem btn btn-default"><i class="fa fa-plus"></i> نویسنده</a>
                            <a href="javascript:void(0)" data-item="translators" data-banner="has" class="addItem btn btn-default"><i class="fa fa-plus"></i> مترجم</a>
                        </p>
                        {{--<p>--}}
                            {{--<a href="javascript:void(0)" data-item="books" data-banner="has" class="addItem btn btn-default"><i class="fa fa-plus"></i> بنر کتاب</a>--}}
                            {{--<a href="javascript:void(0)" data-item="magazines" data-banner="has" class="addItem btn btn-default"><i class="fa fa-plus"></i> بنر مجله</a>--}}
                        {{--</p>--}}
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.objects.scripts_jqueryalertable')
    <script src="{{asset('assets/admin/vendor/selectize/js/selectize.min.js')}}"></script>
    <script src="{{url('assets/admin/vendor/sortable/sortable.min.js')}}"></script>
    <script src="{{asset('assets/admin/vendor/holder/holder.js')}}"></script>
    <script>
        var el = document.getElementById('items');
        var sortable = Sortable.create(el,{
            handle: '.drag-handle',
        });

        function dosubmit(){
            if($("#items li").length < 1){
                $.alertable.alert('خطا در حداقل یک مورد باید اضافه شود !');
                return false;
            }
            return true;
        }

        $('select.mselectize').each(function(){
            $(this).selectize({
                create: false,
            });
        });

        $(".addItem").on('click',function(e){
            e.preventDefault();
            var item  = $(this).attr('data-item');
            var banner = $(this).attr('data-banner');
            if(banner.trim() == "has"){
                item= item+"/banner";
            }
            $.ajax({
                url: "{{url('admin/ajax')}}/"+item,
                method : "GET",
                dataType: "html"
            }).done(function(res){
                $("#items").append(res);
                $('select.mselectize').each(function(){
                    $(this).selectize({
                        create: false,
                    });
                });
                Holder.run({
                   images:".img-holder"
                });
            }).fail(function(jqXHR, textStatus){
                $.alertable.alert('دوباره تلاش کنید . مشکلی در سیستم بوجود آمده است !');
            });
//            $("#items").append('<li> <input type="text" name="value[\'value\'][]" class="form-control" > <input type="hidden" name="value[\'type\'][]">   </li>');
        })
        $("#items").on('click','.close',function(){
           $(this).parent().remove();
        });
        $("#items").on("change",".uploadFile", function()
        {
            var select = $(this);
            var point = $(this).attr('data-point');
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test( files[0].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader

                var _URL = window.URL;
                var  img;
                var ratio = "";
                reader.readAsDataURL(files[0]); // read the local file
                reader.onloadend = function(){ // set image data as background of div
                    img = new Image();
                    img.src = this.result;
                    img.onload = function () {
                        select.parents('li').children("input[type='hidden'].old-file").val('empty');
                        select.parent().children("a").children("img[data-img='"+point+"']").attr('data-src',"");
                        select.parent().children("a").children("img[data-img='"+point+"']").attr('src',img.src)
                    };
                }
            }
        });

        $("#items").on('click','a.clickable',function(e){
            e.preventDefault();
            $(this).parent().children("input[type='file']").click();
        });

    </script>
@endsection