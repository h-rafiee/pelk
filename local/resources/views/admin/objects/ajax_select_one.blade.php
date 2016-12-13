<li class="item-template">
    <i class="fa fa-bars drag-handle"></i>
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

