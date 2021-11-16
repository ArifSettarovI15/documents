@extends('BaseData.Views.managerlayout')
@section('title', 'Счета')
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container-fluid" >

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr class="text-center">
                <th scope="col">Cчет</th>
                <th scope="col">Статус отправки</th>
                <th scope="col">Договор</th>
                <th scope="col">Клиент</th>
                <th scope="col">Дата отправки</th>
            </tr>
            <tr class="text-center">
                <th scope="col"></th>
                <th scope="col">
                    <select name="invoice_sended" class="table-filter" data-type="filter">
                        <option value="">Все</option>
                        <option value="1">Отправлен</option>
                        <option value="2">Отменен</option>
                        <option value="3">Ручной</option>
                    </select>
                    <select name="invoice_payed" class="table-filter" data-type="filter">
                        <option value="">Все</option>
                        <option value="0">Не оплачен</option>
                        <option value="1">Оплачен</option>
                    </select>
                </th>
                <th scope="col">
                    <select name="invoice_contract_id" class="table-filter" data-type="filter">
                        <option value="">Все</option>
                        @foreach($contracts as $contract)
                            <option value="{{$contract->contract_id}}">Договор № {{$contract->contract_number}} от {{$contract->contract_date}}</option>
                        @endforeach
                    </select>
                </th>
                <th scope="col">
                    <select name="invoice_client_id" class="table-filter" data-type="filter">
                        <option value="">Все</option>
                        @foreach($clients as $client)
                            <option value="{{$client->client_id}}">{{$client->client_name}}</option>
                        @endforeach
                    </select>
                </th>
                <th scope="col">
                    <input type="text" class="table-filter table-date_picker datepicker" name="invoice_date" data-type="filter">
                </th>
            </tr>
            </thead>
            <tbody class="table-data">
                @include('Invoices.Views.history_table')
            </tbody>
        </table>
        <div class="paging-data mt-5">
            {{$invoices->links('paging')}}
        </div>
    </div>
@endsection
