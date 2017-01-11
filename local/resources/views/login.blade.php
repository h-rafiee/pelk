@extends('layouts.pelk')

@section('main')
    <div class="slider blur">
        <div class="swiper-container3">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#">ثبت نام</a>
                            <a href="#" class="active">ورود به حساب</a>
                        </div></section></div></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <section>
                        <div class="avatar ic-user"></div>
                        <h2 class="form_title">ثبت نام</h2>
                        <form action="" method="POST">
                            <div class="field_group">
                                {!! csrf_field() !!}
                                <input type="email" name="email" class="rtl textbox" placeholder="پست الکترونیک">
                                <input type="password" name="password" class="rtl textbox" placeholder="رمز جدید">
                                <input type="password" name="password" class="rtl textbox" placeholder="تکرار رمز">
                            </div>
                            <label for="tos" class="form_link nofloat">شرایط استفاده از خدمات را مطالعه کنید</label>
                            <input type="submit" class="submit" value="ادامه">
                        </form>
                    </section>
                </div>
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section>
                        <div class="avatar ic-user"></div>
                        <h2 class="form_title">ورود به حساب کاربری</h2>
                        <form action="" method="POST">
                            {!! csrf_field() !!}
                            <div class="field_group">
                                <input type="email" name="email" class="rtl textbox" placeholder="پست الکترونیک">
                                <input type="password" name="password" class="rtl textbox" placeholder="رمز ورود">
                            </div>
                            <input type="checkbox" name="remember" id="remember">
                            <label class="toggle" for="remember"><div><div></div></div>
                                <span>یادآوری</span>
                            </label>
                            <input type="submit" class="submit" value="ورود">
                            <a href="" class="form_link">راهنمایی</a>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <input type="checkbox" name="tos" id="tos">
    <div class="tos">
        <label for="tos">
            <section>
                @include('datas.terms')
            </section>
        </label>
    </div>
@endsection