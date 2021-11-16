@extends('BaseData.Views.managerlayout')
@section('title', 'Список поставщиков')
@include('components.bread-crumbs', with($breadcrumbs))

@section('content')

    <div class="container-fluid" >

        <a href="{{route('manager.companies.create')}}" class="btn btn-success float-right m-5" >Добавить клиента</a>

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr>
                <th scope="col">ID поставщика</th>
                <th scope="col">Статус</th>
                <th scope="col">Название</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $company)
                <tr>
                    <th scope="row" class="pl-5">{{$company->company_id}}</th>
                    <td>
                        <input type="checkbox" data-toggle="toggle" class="js_status_change"
                               data-id="{{$company->company_id}}" data-current="{{$company->company_status}}"
                               data-field="company_status"
                               data-url = "{{route('manager.companies.status')}}"
                               data-on="Вкл" data-off="Выкл"
                               data-size="sm" data-onstyle="info"
                               @if($company->company_status) checked @endif>
                    </td>
                    <td>
                        {{$company->company_name}}
                    </td>
                    <td>
                        <a href="{{route('manager.companies.show', $company->company_id)}}">
                            <i class="fas fa-pen"></i>
                        </a>
{{--                        <a href="{{route('manager.companies.destroy', $company->company_id)}}" class="ml-2 js_ajax_delete">--}}
{{--                            <i class="fas fa-trash-alt icon_danger"></i>--}}
{{--                        </a>--}}
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 100px 0;">
                        <h2>Нет данных для вывода</h2>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-5">
            {{$companies->links('paging')}}
        </div>
    </div>

@endsection

