
@section('breadcrumbs')
    <div class="breadcrumb-item"><a href="{{url('manager')}}">Главная</a></div>
    @isset($breadcrumbs)
        @foreach($breadcrumbs as $breadcrumb)
            <div class="breadcrumb-item">
                @if(isset($breadcrumb['link']) and url(Route::current()->uri) != $breadcrumb['link'])
                        <a href="{{$breadcrumb['link']}}">{{$breadcrumb['text']}}</a>
                @else
                    <span>{{$breadcrumb['text']}}</span>
                @endif
            </div>
        @endforeach
    @endisset
@endsection
