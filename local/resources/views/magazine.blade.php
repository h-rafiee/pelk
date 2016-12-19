@extends('layouts.pelk')

@section('title','کتابخوان | '.trans('admin.'.$type).' | '.$title)

@section('main')
    <div class="slider">
        <div class="swiper-container4">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#">مشخصات</a>
                            <a href="#" class="active">شرح</a>
                        </div>
                    </section>
                </div>
            </div>
            <div class="swiper-wrapper">
                <div class="swiper-slide book_detail technical_detail">
                    <section>
                        <div>
                            <div class="book_cover"><img src="{{url($model->image)}}" alt="{{$model->title}}" width="158px"></div>
                            <article>
                                <h2>{{$model->title}}</h2>
                                <div class="book_credit">
                                    {{--<div><span class="ic ic-logo">عنوان اصلی</span>A Song Of Fire And Ice: A Game Of Thrones</div>--}}
                                    <div>
                                        <span class="ic ic-cat">دسته‌بندی</span>
                                        <a href="{{url("magazines/categories/{$model->category->title}")}}">{{$model->category->title}}</a>
                                    </div>
                                    <div>
                                        <span class="ic ic-files">ناشر</span>
                                        <a href="{{url("magazines/publications/{$model->publication->title}")}}">{{$model->publication->title}}</a>
                                    </div>
                                    <div>
                                        <span class="ic ic-flag">چاپ اول</span>
                                        {{$model->publish_date}}
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>
                </div>
                <div class="swiper-slide book_detail">
                    <section>
                        <div>
                            <div class="book_cover"><img src="{{url($model->image)}}" alt="{{$model->title}}" width="158px"></div>
                            <article>
                                <h2>{{$model->title}}</h2>
                                <div>
                                    <p>{!! $model->description  !!}</p>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="action_btn">
                                    <a href="#" class="orange"><span>خرید</span>{{number_format($model->price)}} تومان</a>
                                    <a href="#" class="brown"><span>دریافت نمونه {!! trans('admin.'.$type) !!}</span>رایگان</a>
                                </div>
                                <div class="book_credit">
                                    <div>
                                        <span class="ic ic-cat">دسته‌بندی</span>
                                        <a href="{{url("magazines/categories/{$model->category->title}")}}">{{$model->category->title}}</a>
                                    </div>
                                    <div>
                                        <span class="ic ic-files">ناشر</span>
                                        <a href="{{url("magazines/publications/{$model->publication->title}")}}">{{$model->publication->title}}</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="slider">
        <div class="swiper-container3 bookshelf">
            <div class="tabs">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#">از همین ناشر</a>
                            <a href="#" class="active">مجله‌های مشابه</a>
                        </div></section></div></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <section>
                        <div class="evenmore">
                            <section>
                                <a href="{{url("magazines/publications/{$model->publication->title}")}}" class="ic ic-arrow">بیشتر</a>
                            </section>
                        </div>
                        @foreach($publication_magazines as $book)
                            <div><a href="{{url("magazine/{$book->slug}/{$book->title}")}}"><img src="{{url($book->image)}}"></a></div>
                        @endforeach
                    </section>
                </div>
                <div class="swiper-slide">
                    <section>
                        <div class="evenmore"><section>
                            </section></div>
                        @foreach($related as $book)
                            <div><a href="{{url("magazine/{$book->slug}/{$book->title}")}}"><img src="{{url($book->image)}}"></a></div>
                        @endforeach
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

