@extends('BaseData.Views.managerlayout')

@section('title', 'Список услуг')
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')

    <div class="container-fluid" >

        <a href="{{route('manager.services.create')}}" class="btn btn-success float-right m-5" >Добавить услугу</a>

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr>
                <th scope="col">ID услуги</th>
                <th scope="col">Статус</th>
                <th scope="col">Название</th>
                <th scope="col">Цена по умолчанию</th>
                <th scope="col">Периодичность</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($services as $service)
                <tr>
                    <th scope="row" class="pl-5">{{$service->service_id}}</th>
                    <td>
                        <input type="checkbox" data-toggle="toggle" class="js_status_change"
                               data-id="{{$service->service_id}}" data-current="{{$service->service_status}}"
                               data-field="service_status"
                               data-url = "{{route('manager.services.status')}}"
                               data-on="Вкл" data-off="Выкл"
                               data-size="sm" data-onstyle="info"
                               @if($service->service_status) checked @endif>
                    </td>
                    <td>{{$service->service_title}}</td>
                    <td>{{$service->service_price}}</td>
                    <td>
                        @switch($service->service_period)
                            @case("monthly")Ежемесячно @break
                            @case("weekly")Еженедельно @break
                            @case("daily")Ежедневно @break
                            @case("once")Однократно @break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{route('manager.services.show', $service->service_id)}}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="{{route('manager.services.destroy', $service->service_id)}}" class="ml-2 js_ajax_delete">
                            <i class="fas fa-trash-alt icon_danger"></i>
                        </a>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 100px 0;">
                        <h2>Нет данных для вывода</h2>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-5">
            {{$services->links('paging')}}
        </div>
    </div>

@endsection
