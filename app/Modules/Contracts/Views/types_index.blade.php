@extends('BaseData.Views.managerlayout')
@section('title', 'Типы договоров')
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container-fluid" >

        <a href="{{route('manager.contract_types.create')}}" class="btn btn-success float-right m-5" >Добавить тип договора</a>

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr>
                <th scope="col">ID шаблона</th>
                <th scope="col">Статус</th>
                <th scope="col">Название</th>
                <th scope="col">Литера</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($contract_types as $contract_type)
                <tr>
                    <th scope="row" class="pl-5">{{$contract_type->ct_id}}</th>
                    <td>
                        <input type="checkbox" data-toggle="toggle" class="js_status_change"
                               data-id="{{$contract_type->ct_id}}" data-current="{{$contract_type->ct_status}}"
                               data-field="ct_status"
                               data-url = "{{route('manager.contract_types.status')}}"
                               data-on="Вкл" data-off="Выкл"
                               data-size="sm" data-onstyle="info"
                               @if($contract_type->ct_status) checked @endif>
                    </td>
                    <td>{{$contract_type->ct_name}}</td>
                    <td class="pl-1">{{$contract_type->ct_litera}}</td>
                    <td>
                        <a href="{{route('manager.contract_types.show', $contract_type->ct_id)}}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="{{route('manager.contract_types.destroy', $contract_type->ct_id)}}" class="ml-2 js_ajax_delete">
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
            {{$contract_types->links('paging')}}
        </div>
    </div>
@endsection
