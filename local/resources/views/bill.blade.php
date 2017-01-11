@extends('layouts.pelk')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/grid.css')}}">
    <style>
        .gap{
            height: 20px;
        }
        .well{
            margin-top: 20px;
            overflow: hidden;
            border: 1px solid #eee;
            box-shadow: 0px 0px 3px #ccc;
            padding: 25px 10px;
            background: #fff;
        }
        .legend {
            margin-top: 0px;
            line-height: 1em;
            border-bottom: 1px solid #333;
            padding-bottom : 10px;
        }
        .legend small{
            font-size : 60%;
            color : #999999;
        }
        .payment label{
            position: relative;
            cursor: pointer;
        }
        .payment label input {
            position: absolute;
            top: 5px;
            right : 5px;
            visibility: hidden;
        }
        .payment label img{
            height: 30px;
            border-radius: 5px;
            padding : 4px;
            border : 2px solid transparent;
        }
        .payment label img.active {
            border : 2px solid #60D560;
            background-color: #ececec;
        }
        table.bill {
            width: 100%;
        }
        table.bill tbody tr td {
            padding : 10px 0px;
            border-bottom: 1px solid #e0e0e0;
        }
        table.bill {
            margin-bottom: 10px;
        }
        hr.style-two { border: 0; height: 1px; background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0)); }
        .btn {
            cursor: pointer;
            background: #27c70e;
            background-image: -webkit-linear-gradient(top, #27c70e, #2bb82f);
            background-image: -moz-linear-gradient(top, #27c70e, #2bb82f);
            background-image: -ms-linear-gradient(top, #27c70e, #2bb82f);
            background-image: -o-linear-gradient(top, #27c70e, #2bb82f);
            background-image: linear-gradient(to bottom, #27c70e, #2bb82f);
            -webkit-border-radius: 4;
            -moz-border-radius: 4;
            border-radius: 4px;
            color: #ffffff;
            font-size: 18px;
            padding: 10px 16px 10px 16px;
            text-decoration: none;
            border: 0px;
        }

        .btn:hover {
            background: #168516;
            background-image: -webkit-linear-gradient(top, #168516, #278016);
            background-image: -moz-linear-gradient(top, #168516, #278016);
            background-image: -ms-linear-gradient(top, #168516, #278016);
            background-image: -o-linear-gradient(top, #168516, #278016);
            background-image: linear-gradient(to bottom, #168516, #278016);
            text-decoration: none;
        }
        .btn.block {
            width: 100%;
        }
        .img-responsive{
            width: 100%;
        }
    </style>
@endsection

@section('main')
    <div class="row" dir="rtl">
        <div class="col-2"></div>
        <div class="col-8">

            <div class="well">
                <div class="col-3">
                    <?php $count = 0 ; ?>
                    @foreach($model->books as $book)
                        <img src="{{url($book->image)}}" class="img-responsive">
                        <a href="{{url("book/{$book->slug}/{$book->title}")}}">{{ $book->title  }}</a>
                        <?php $count++; ?>
                    @endforeach

                    @foreach($model->magazines as $magazine)
                        <img src="{{url($magazine->image)}}" class="img-responsive">
                        <a href="{{url("magazine/{$magazine->slug}/{$magazine->title}")}}">{{ $magazine->title  }}</a>
                        <?php $count++; ?>
                    @endforeach

                </div>
                <div class="col-9">
                    <h1 class="legend" >فاکتور خرید شما <br>
                        <small>
                            شماره فاکتور : {{$model->code}}
                        </small>
                    </h1>
                    <table class="bill">
                        <tbody>
                        <tr>
                            <td class="text-bold" width="30%">
                                نام و نام خانوادگی
                            </td>
                            <td>
                                {{$model->user->name}}
                            </td>
                        </tr>
                        {{--<tr>--}}
                            {{--<td  width="30%">--}}
                                {{--تعداد کالا--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--{{$count}} عدد--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td  width="30%">
                                وضعیت پرداخت
                            </td>
                            <td>
                                @if($model->pay == 0)
                                    پرداخت نشده
                                @elseif($model->pay == 1)
                                    پرداخت شده
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td  width="30%">
                                قیمت مجموع
                            </td>
                            <td>
                                {!! number_format($model->price) !!} تومان
                            </td>
                        </tr>
                        @if($model->pay==1)
                            <tr>
                                <td>
                                    درگاه پرداخت
                                </td>
                                <td>
                                    {{$payments->payment->title}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    @if($model->pay == 0)
                    <form method="post">
                        {!! csrf_field() !!}
                        <div class="payment">
                            @foreach($payments as $payment)
                                <label for="{{$payment->slug}}">
                                    <?php $setting = jsone_decode($payment->params); ?>
                                    <input type="radio" name="payment" value="{{$payment->slug}}" id="{{$payment->slug}}" checked >
                                    <img class="active" src="{{url($setting->logo)}}" alt="">
                                </label>
                            @endforeach
                        </div>
                        <div class="gap"></div>
                        <button class="btn block">پرداخت</button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
        <div class="col-2"></div>
    </div>
@endsection