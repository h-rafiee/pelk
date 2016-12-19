@extends('layouts.pelk')

@section('main')
    <div class="slider">
        <div class="swiper-container3 bookshelf">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#">مجله‌ها</a>
                            <a href="#" class="active">کتاب‌ها</a>
                        </div></section></div></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <section>
                        @foreach($categories as $category)
                        @if($category->magazines_four->count()>0)
                        <div class="evenmore">
                            <section>
                                <a href="#" class="ic ic-cat">{{$category->title}}</a>
                                <a href="{{url("magazines/categories/{$category->title}")}}" class="ic ic-arrow">بیشتر</a>
                            </section>
                        </div>
                            @foreach($category->magazines_four as $item)
                            <div>
                                <a href="{{url("magazine/{$item->slug}/{$item->title}")}}"><img src="{{url($item->image)}}"></a>
                            </div>
                            @endforeach
                        @endif
                        @endforeach
                    </section>
                </div>
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section>
                        @foreach($categories as $category)
                            @if($category->books_four->count()>0)
                            <div class="evenmore">
                                <section>
                                    <a href="#" class="ic ic-cat">{{$category->title}}</a>
                                    <a href="{{url("books/categories/{$category->title}")}}" class="ic ic-arrow">بیشتر</a>
                                </section>
                            </div>
                            @foreach($category->books_four as $item)
                                <div>
                                    <a href="{{url("book/{$item->slug}/{$item->title}")}}"><img src="{{url($item->image)}}"></a>
                                </div>
                            @endforeach
                            @endif
                        @endforeach
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection