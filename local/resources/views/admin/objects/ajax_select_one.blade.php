<li class="item-template">
    <i class="fa fa-bars drag-handle"></i>
    <br>
    @if(!empty($banner))
    <input type="hidden" name="item[has-banner][]" value="yes" >
    <div class="form-group">
        <input type="file" data-point="preview1" name="item[banner][]" class="uploadFile hidden" >
        <a href="javascript:void(0)" class="clickable">
            <img class="img-holder img-thumbnail  img-responsive"  data-img="preview1" src="holder.js/100px150?font=FontAwesome&text=&#xf03e&size=40" height="150">
        </a>
    </div>
    @else
        <input type="hidden" name="item[has-banner][]" value="no" >
    @endif
    <input type="hidden" class="old-file" name="item[banner-file][]" value="empty">
    <div class="form-group">
        <label>{{$label}}</label>
        <select class="mselectize" name="item[value][]">
            @foreach($model as $key => $value)
                <?php
                $itemTxt = '';
                if(!empty($value->title)){
                    $itemTxt = $value->title;
                }
                if(!empty($value->value)){
                    $itemTxt = $value->value;
                }
                if(!empty($value->name)){
                    $itemTxt = $value->name;
                }
                ?>
                <option value="{{$value->id}}">{{$itemTxt}}</option>
            @endforeach
        </select>
        <input type="hidden" name="item[type][]" value="{{$type}}">
    </div>
    <div class="clearfix"></div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</li>

