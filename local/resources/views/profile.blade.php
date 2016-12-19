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
                        <form action="" method="POST">
                            <label for="uploader" class="uploader"><div class="avatar ic-user"></div>انتخاب عکس</label>
                            <input type="file" name="uploader" id="uploader">
                            <div class="field_group">
                                <input type="text" name="name" class="rtl textbox" placeholder="نام و خانوادگی">
                                <input type="text" name="city" class="rtl textbox" placeholder="شهر">
                                <label class="selectbox_container">
                                    <select name="born">
                                        <option selected disabled>سال تولد</option>
                                        <option value="1340">متولد ۱۳۴۰</option>
                                        <option value="1341">متولد ۱۳۴۱</option>
                                        <option value="1342">متولد ۱۳۴۲</option>
                                        <option value="1343">متولد ۱۳۴۳</option>
                                        <option value="1344">متولد ۱۳۴۴</option>
                                        <option value="1345">متولد ۱۳۴۵</option>
                                        <option value="anything">…</option>
                                        <option value="1387">متولد ۱۳۸۷</option>
                                    </select>
                                </label>
                                <label class="selectbox_container">
                                    <select name="edu">
                                        <option selected disabled>تحصیلات</option>
                                        <option value="1">دبیرستان</option>
                                        <option value="2">دیپلم</option>
                                        <option value="3">کاردانی</option>
                                        <option value="4">کارشناسی</option>
                                        <option value="5">کارشناسی ارشد</option>
                                        <option value="6">دکترا</option>
                                    </select>
                                </label>
                            </div>
                            <input type="checkbox" checked="checked" name="sex" id="sex">
                            <label class="toggle sex" for="sex">مرد<div><div></div></div>زن
                            </label>
                            <div class="field_group">
                                <input type="email" name="email" class="rtl textbox" placeholder="ایمیل">
                                <input type="text" name="phone" class="rtl textbox" placeholder="شماره تلفن">
                            </div>
                            <input type="checkbox" checked="checked" name="email_newsletter" id="email_newsletter">
                            <label class="toggle" for="email_newsletter"><div><div></div></div>
                                <span>دریافت خبرنامه با ایمیل</span>
                            </label>
                            <input type="checkbox" name="sms_newsletter" id="sms_newsletter">
                            <label class="toggle" for="sms_newsletter"><div><div></div></div>
                                <span>دریافت خبرنامه با پیامک</span>
                            </label>
                            <input type="checkbox" name="toggle_password_field" id="toggle_password_field">
                            <div>
                                <label class="toggle nofloat" for="toggle_password_field"><div><div></div></div>
                                    <span>تغییر رمز</span>
                                </label>
                                <div class="field_group hide">
                                    <input type="password" name="password" class="rtl textbox" placeholder="رمز جدید">
                                    <input type="password" name="password" class="rtl textbox" placeholder="تکرار رمز">
                                </div>
                            </div>
                            <input type="submit" class="submit" value="ذخیره">
                        </form>
                    </section>
                </div>
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section>
                        <div class="profile_pic"><img src="{{asset('img/sample/profile_picture1.png')}}"></div>
                        <h2 class="form_title">نام و نام خانوادگی</h2>
                        <div class="field_group">
                            <h2 class="form_title">ساکن رشت</h2>
                            <h2 class="form_title">متولد ۱۳۶۱</h2>
                        </div>
                        <div class="field_group">
                            <h3 class="form_title">username@gmail.com</h3>
                            <h3 class="form_title">شماره تلفن 09123456789</h3>
                        </div>
                        <a href="#" class="book_preview">
                            <div><img src="{{asset('img/sample/book1.png')}}"></div>
                            <div><img src="{{asset('img/sample/book2.png')}}"></div>
                            <div><img src="{{asset('img/sample/book3.png')}}"></div>
                            <div><img src="{{asset('img/sample/book4.png')}}"></div>
                            <div><img src="{{asset('img/sample/book5.png')}}"></div>
                            <div><img src="{{asset('img/sample/book6.png')}}"></div>
                            <div><img src="{{asset('img/sample/book7.png')}}"></div>
                            <div><img src="{{asset('img/sample/book8.png')}}"></div>
                            <div><img src="{{asset('img/sample/book1.png')}}"></div>
                        </a>
                        <div class="book_counter">تعداد کتاب‌های شما <div>27</div></div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection