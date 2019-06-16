@extends('layouts.pelk')

@section('slider')
    <div class="slider">
        <div class="swiper-container1">
            <div class="swiper-wrapper">
                @foreach(@$slider as $slide)
                    <div class="swiper-slide"> <a href="{{$slide->link}}"><img src="{{asset($slide->image)}}"></a> </div>
                @endforeach
            </div>
        </div>
        <div class="pagination"></div>
    </div>
@endsection

@section('main')
    <div class="slider">
        <div class="swiper-container2 bookshelf">
            <div class="tabs">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#"></a>
                            <a href="#" class="active">تازه‌ها</a>
                        </div>
                    </section>
                </div>
            </div>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    {{--<div class="evenmore"><section><a href="#" class="ic ic-arrow">بیشتر</a></section></div>--}}
                    {{--<section>--}}
                        {{--<div><a href="#"><img src="{{asset('img/sample/book5.png')}}"></a></div>--}}
                        {{--<div><a href="#"><img src="{{asset('img/sample/book6.png')}}"></a></div>--}}
                        {{--<div><a href="#"><img src="{{asset('img/sample/book7.png')}}"></a></div>--}}
                        {{--<div><a href="#"><img src="{{asset('img/sample/book8.png')}}"></a></div>--}}
                    {{--</section>--}}
                </div>
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <div class="evenmore"><section><a href="{{url('last')}}" class="ic ic-arrow">بیشتر</a></section></div>
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
    <?php
    $hasItem = false;
    foreach($data->items as $item){
        if(!empty($item->model->wbooks_four))
            $item->model->books_four = $item->model->wbooks_four;

        if(!empty($item->model->tbooks_four))
            $item->model->books_four = $item->model->tbooks_four;

        if(!empty($item->model->books_four)){
            $hasItem = true;
            break;
        }
    }
    ?>
    @if($hasItem==true)
    <div class="slider">
        <div class="swiper-container0 bookshelf">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#" class="active">کتاب‌ها</a>
                        </div>
                    </section>
                </div>
            </div>
            <div class="swiper-wrapper">
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section>
                        @foreach($data->items as $item)
                            <?php
                                if(!empty($item->model->wbooks_four))
                                    $item->model->books_four = $item->model->wbooks_four;

                                if(!empty($item->model->tbooks_four))
                                    $item->model->books_four = $item->model->tbooks_four;

                            ?>
                            @if(!empty($item->model->books_four))
                            <?php
                            $itemTxt = '';
                            if(!empty($item->model->title)){
                                $itemTxt = $item->model->title;
                            }
                            if(!empty($item->model->value)){
                                $itemTxt = $item->model->value;
                            }
                            if(!empty($item->model->name)){
                                $itemTxt = $item->model->name;
                            }
                            ?>
                            <div class="evenmore">
                                <section>
                                    @if(!empty($item->model->slug))
                                        <a href="{{url("books/{$item->type}/{$item->model->slug}/{$itemTxt}")}}" class="ic ic-cat">{{$itemTxt}}</a>
                                        <a href="{{url("books/{$item->type}/{$item->model->slug}/{$itemTxt}")}}" class="ic ic-arrow">بیشتر</a>
                                    @else
                                        <a href="{{url("books/{$item->type}/{$itemTxt}")}}" class="ic ic-cat">{{$itemTxt}}</a>
                                        <a href="{{url("books/{$item->type}/{$itemTxt}")}}" class="ic ic-arrow">بیشتر</a>
                                    @endif
                                </section>
                            </div>
                            @foreach($item->model->books_four as $book)
                                <div><a href="{{url("book/{$book->slug}/{$book->title}")}}"><img src="{{url($book->image)}}"></a></div>
                            @endforeach
                            @endif
                        @endforeach
                    </section>
                </div>
            </div>
        </div>
    </div>
    @endif
    <?php
            $hasItem = false;
            foreach($data->items as $item){
                if(!empty($item->model->magazines_four)){
                    $hasItem = true;
                    break;
                }
            }
    ?>
    @if($hasItem==true)
    <div class="slider">
        <div class="swiper-container-1 bookshelf">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#" class="active">مجله‌ها</a>
                        </div>
                    </section>
                </div>
            </div>
            <div class="swiper-wrapper">
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section>
                        @foreach($data->items as $item)
                            @if(!empty($item->model->magazines_four) && ($item->type=='categories' || $item->type=='tags' || $item->type=='publications' ) )
                                <?php
                                $itemTxt = '';
                                if(!empty($item->model->title)){
                                    $itemTxt = $item->model->title;
                                }
                                if(!empty($item->model->value)){
                                    $itemTxt = $item->model->value;
                                }
                                if(!empty($item->model->name)){
                                    $itemTxt = $item->model->name;
                                }
                                ?>
                                <div class="evenmore">
                                    <section>
                                        @if(!empty($item->model->slug))
                                            <a href="{{url("magazines/{$item->type}/{$item->model->slug}/{$itemTxt}")}}" class="ic ic-cat">{{$itemTxt}}</a>
                                            <a href="{{url("magazines/{$item->type}/{$item->model->slug}/{$itemTxt}")}}" class="ic ic-arrow">بیشتر</a>
                                        @else
                                            <a href="{{url("magazines/{$item->type}/{$itemTxt}")}}" class="ic ic-cat">{{$itemTxt}}</a>
                                            <a href="{{url("magazines/{$item->type}/{$itemTxt}")}}" class="ic ic-arrow">بیشتر</a>
                                        @endif
                                    </section>
                                </div>
                                @foreach($item->model->magazines_four as $magazine)
                                    <div><a href="{{url("magazine/{$magazine->slug}/{$magazine->title}")}}"><img src="{{url($magazine->image)}}"></a></div>
                                @endforeach
                            @endif
                        @endforeach
                    </section>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
