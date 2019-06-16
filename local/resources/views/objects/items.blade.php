@foreach($items as $item)
    <div><a href="{{url("{$type}/{$item->slug}/{$item->title}")}}"><img src="{{url($item->image)}}"></a></div>
@endforeach