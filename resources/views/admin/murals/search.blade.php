
@foreach ($subjects as $item)
    <option value="{{$item->id}}">{{$item->title}} ({{$item->type}})</option>
@endforeach
