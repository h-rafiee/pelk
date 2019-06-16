@extends('layouts.pelk')

@section('main')
    <div class="slider">
        <div class="swiper-container0 bookshelf">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#" class="active">تازه ها</a>
                        </div></section></div></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section>
                        @foreach($last as $value)
                            @if(strpos($value->slug,"Book")>-1)
                                <div><a href="{{url("book/{$value->slug}/{$value->title}")}}"><img src="{{url($value->image)}}"></a></div>
                            @else
                                <div><a href="{{url("magazine/{$value->slug}/{$value->title}")}}"><img src="{{url($value->image)}}"></a></div>
                            @endif
                        @endforeach
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection