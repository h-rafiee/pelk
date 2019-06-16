@extends('layouts.pelk')

@section('title','سامانه نشر الکترونیک'.$title)

@section('main')
    <?php
    $itemTxt = '';
    if(!empty($filter->title)){
        $itemTxt = $filter->title;
    }
    if(!empty($filter->value)){
        $itemTxt = $filter->value;
    }
    if(!empty($filter->name)){
        $itemTxt = $filter->name;
    }
    ?>
    <div class="slider">
        <div class="swiper-container0 bookshelf">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#" class="active">{{$itemTxt}}</a>
                        </div>
                    </section>
                </div>
            </div>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <section>
                        @foreach($model as $item)
                            <div><a href="{{url("book/{$item->slug}/{$item->title}")}}"><img src="{{url($item->image)}}"></a></div>
                        @endforeach
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

