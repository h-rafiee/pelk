@extends('layouts.pelk')

@section('main')
    <div class="slider blur">
        <div class="swiper-container3">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#">ویرایش نمایه</a>
                            <a href="#" class="active">نمایه‌ی شما</a>
                        </div>
                    </section>
                </div>
            </div>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <section>
                        <form action="{{url("profile/edit")}}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <label for="uploader" class="uploader">
                                <img data-img="img_preview" src="{{((empty($user->profile_pic))?url('img/default-pic.png'):url($user->profile_pic))}}" width="140">
                                <span>انتخاب عکس</span>
                            </label>
                            <input type="file" name="uploader" id="uploader" class="uploadFile" data-point="img_preview">
                            <div class="field_group">
                                <input type="text" name="name" value="{{$user->name}}" class="rtl textbox" placeholder="نام و خانوادگی">
                                {{--<input type="text" name="city" class="rtl textbox" placeholder="شهر">--}}
                                {{--<input type="text" name="birthday" class="rtl textbox" placeholder="سال تولد">--}}
                                {{--<label class="selectbox_container">--}}
                                    {{--<select name="edu" class="rtl">--}}
                                        {{--<option selected disabled>تحصیلات</option>--}}
                                        {{--<option value="1">دبیرستان</option>--}}
                                        {{--<option value="2">دیپلم</option>--}}
                                        {{--<option value="3">کاردانی</option>--}}
                                        {{--<option value="4">کارشناسی</option>--}}
                                        {{--<option value="5">کارشناسی ارشد</option>--}}
                                        {{--<option value="6">دکترا</option>--}}
                                    {{--</select>--}}
                                {{--</label>--}}
                            </div>
                            {{--<input type="checkbox" name="sex" id="sex">--}}
                            {{--<label class="toggle sex" for="sex">مرد<div><div></div></div>زن--}}
                            {{--</label>--}}
                            <div class="field_group">
                                <input type="email" name="email" class="rtl textbox" value="{{$user->email}}" placeholder="ایمیل">
                                <input type="text" name="mobile" class="rtl textbox"  value="{{@$user->mobile}}" placeholder="شماره تلفن">
                            </div>
                            {{--<input type="checkbox" checked="checked" name="email_newsletter" id="email_newsletter">--}}
                            {{--<label class="toggle" for="email_newsletter"><div><div></div></div>--}}
                                {{--<span>دریافت خبرنامه با ایمیل</span>--}}
                            {{--</label>--}}
                            {{--<input type="checkbox" name="sms_newsletter" id="sms_newsletter">--}}
                            {{--<label class="toggle" for="sms_newsletter"><div><div></div></div>--}}
                                {{--<span>دریافت خبرنامه با پیامک</span>--}}
                            {{--</label>--}}
                            <input type="checkbox" name="toggle_password_field" id="toggle_password_field">
                            <div>
                                <label class="toggle nofloat" for="toggle_password_field"><div><div></div></div>
                                    <span>تغییر رمز</span>
                                </label>
                                <div class="field_group hide">
                                    <input type="password" name="password" class="rtl textbox" placeholder="رمز جدید">
                                    {{--<input type="password" name="check_password" class="rtl textbox" placeholder="تکرار رمز">--}}
                                </div>
                            </div>
                            <input type="submit" class="submit" value="ذخیره">
                        </form>
                    </section>
                </div>
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section>
                        <div class="profile_pic"><img src="{{((empty($user->profile_pic))?url('img/default-pic.png'):url($user->profile_pic))}}" width="140"></div>
                        <h2 class="form_title">{{$user->name}}</h2>
                        {{--<div class="field_group">--}}
                            {{--<h2 class="form_title">ساکن رشت</h2>--}}
                            {{--<h2 class="form_title">متولد ۱۳۶۱</h2>--}}
                        {{--</div>--}}
                        <div class="field_group">
                            <h3 class="form_title">{{$user->email}}</h3>
                            <h3 class="form_title">شماره تلفن {{$user->mobile}}</h3>
                        </div>
                        @if($books->count()>0)
                        <a href="#" class="book_preview">
                            @foreach($books as $item)
                            <div><img src="{{asset($item->book->image)}}"></div>
                            @endforeach
                        </a>
                        <div class="book_counter">تعداد کتاب‌های شما <div>{{$user->books->count()}}</div></div>
                        <br>
                        @endif
                        @if($magazines->count()>0)
                        <a href="#" class="book_preview">
                            @foreach($magazines as $item)
                                <div><img src="{{asset($item->magazine->image)}}"></div>
                            @endforeach
                        </a>
                        <div class="book_counter">تعداد کتاب‌های شما <div>{{$user->magazines->count()}}</div></div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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
                    };
                }
            }
        });
    </script>
@endsection