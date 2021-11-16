@extends('BaseData.Views.managerlayout')

@section('title', 'Список договоров')
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container-fluid" >

        <a href="{{route('manager.contracts.create')}}" class="btn btn-success float-right m-5" >Добавить договор</a>

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr>
                <th scope="col">ID договора</th>
                <th scope="col">Статус</th>
                <th scope="col">Номер договора</th>
                <th scope="col">Дата</th>
                <th scope="col">Контрагент</th>
                <th scope="col"></th>
            </tr>
            <tr>
                <th scope="col"><input type="text" class="table-filter" data-type="search" name="contract_id"></th>
                <th scope="col"><select class="table-filter" data-type="filter" name="contract_status">
                        <option value="">Все</option>
                        <option value="0">Отключенные</option>
                        <option value="1">Активные</option>
                    </select>
                    </th>
                <th scope="col"><input type="text" data-type="search" class="table-filter" name="contract_number"></th>
                <th scope="col"><input type="text" data-type="less" class="table-filter table-date_picker datepicker" name="contract_date"></th>
                <th scope="col">
                    <select class="table-filter" data-type="filter" name="contract_client">
                        <option value="">Все</option>
                        @isset($clients)
                            @foreach($clients as $client)
                                <option value="{{$client->client_id}}">{{$client->client_name}}</option>
                            @endforeach

                        @endisset
                    </select>
                </th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody class="table-data">
                @include('Contracts.Views.components.contracts_table')
            </tbody>
        </table>
        <div class="mt-5 paging-data">
            {{$contracts->links('paging')}}
        </div>
    </div>
@endsection
