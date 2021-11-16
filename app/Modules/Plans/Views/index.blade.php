@extends('BaseData.Views.managerlayout')

@section('title', 'Все планы')
@include('components.bread-crumbs', with($breadcrumbs))

@section('content')

    <div class="container-fluid" >

        <a href="{{route('manager.plans.create')}}" class="btn btn-success float-right m-5" >Новый план отправки</a>

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr>
                <th scope="col">ID плана</th>
                <th scope="col">Статус</th>
                <th scope="col">Договор</th>
                <th scope="col">Число отправки</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($plans as $plan)
                <tr>
                    <th scope="row" class="pl-5">{{$plan->plan_id}}</th>
                    <td>
                        <input type="checkbox" data-toggle="toggle" class="js_status_change"
                               data-id="{{$plan->plan_id}}" data-current="{{$plan->plan_status}}"
                               data-field="plan_status"
                               data-url = "{{route('manager.plans.status')}}"
                               data-on="Вкл" data-off="Выкл"
                               data-size="sm" data-onstyle="info"
                               @if($plan->plan_status) checked @endif>
                    </td>
                    <td><a href="{{route('manager.contracts.show', $plan->plan_contract)}}">{{$plan->plan_contract_name}}</a></td>
                    <td>{{$plan->plan_day}}</td>

                    <td>
                        <a href="{{route('manager.plans.show', $plan->plan_id)}}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="{{route('manager.plans.destroy', $plan->plan_id)}}" class="ml-2 js_ajax_delete">
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
            {{$plans->links('paging')}}
        </div>
    </div>

@endsection
