@if (count($subjects) > 0)
    @foreach ($subjects as $item)
        <option value="{{ $item->id }}">{{ $item->title }} ({{ $item->type }})</option>
    @endforeach
@else
    <option>Não há disciplinas ativas para esse curso.</option>
@endif
