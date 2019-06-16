@extends('layouts.pelk')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
    <div class="slider">
        <div class="swiper-container2 bookshelf">
            <div class="tabs yellow">
                <div class="wrap">
                    <section>
                        <div>
                            <a href="#">جستجوی مجله</a>
                            <a href="#" class="active">جستجوی کتاب</a>
                        </div></section></div></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <section class="search_page">
                        <form action="{{url('search/magazines')}}" method="POST" data-search="magazines" class="search blur" data-result="magazines">
                            <div class="field_group">
                                <input type="text" name="name" class="rtl textbox" placeholder="نام مجله را وارد کنید">
                            </div>
                            <input type="submit" class="submit" value="جستجو">
                        </form>
                        <section class="magazines">

                        </section>
                    </section>
                </div>
                <div class="swiper-slide" class="swiper-slide swiper-slide-visible swiper-slide-active">
                    <section class="search_page">
                        <form action="{{url('search/books')}}" method="POST" data-search="books" class="search blur" data-result="books">
                            <div class="field_group">
                                <input type="text" name="name" class="rtl textbox" placeholder="نام کتاب را وارد کنید">
                            </div>
                            <input type="submit" class="submit" value="جستجو">
                        </form>
                        <section class="books">

                        </section>
                        <div class="clearfix"></div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $("form.search").submit(function(e) {
            var select = $(this);
            var url = select.attr('action');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: select.attr('method'),
                url: url,
                data: select.serialize(), // serializes the form's elements.
                success: function(data)
                {
                    $("."+select.attr('data-result')).html(data);
                }
            });

            e.preventDefault(); // avoid to execute the actual submit of the form.
        });
    </script>
@endsection