@extends('layouts.pelk')

@section('main')
    <section class="intro_container">
        <div class="huge_iphone">
        </div>
        <div class="introduction">
            <h2>همه‌ی کتاب‌ها</h2>
            <h2>در جیب شما!</h2>
            <h3>اپ سامانه نشر الکترونیک برای تبلت و موبایل</h3>
            <div class="intro_link">
                <a href="#" class="brown"><span>نسخه iOS</span>دانلود</a>
                <a href="#" class="orange"><span>نسخه اندروید</span>دانلود</a>
            </div>
        </div>
    </section>
    <div class="slider">
        <div class="swiper-container3 bookshelf">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#">پرفروش‌ها</a>
                            <a href="#" class="active">تازه‌ها</a>
                        </div></section></div></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <section>
                        <div class="evenmore"><section>
                                <a href="#" class="ic ic-arrow">بیشتر</a>
                            </section></div>
                        <div><a href="#"><img src="{{asset('img/sample/book5.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book6.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book7.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book8.png')}}"></a></div>

                        <div><a href="#"><img src="{{asset('img/sample/book3.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book2.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book1.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book4.png')}}"></a></div>

                    </section>
                </div>
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section>
                        <div class="evenmore"><section>
                                <a href="#" class="ic ic-arrow">بیشتر</a>
                            </section></div>


                        <div><a href="#"><img src="{{asset('img/sample/book8.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book6.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book4.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book1.png')}}"></a></div>

                        <div><a href="#"><img src="{{asset('img/sample/book5.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book3.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book7.png')}}"></a></div>
                        <div><a href="#"><img src="{{asset('img/sample/book2.png')}}"></a></div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection