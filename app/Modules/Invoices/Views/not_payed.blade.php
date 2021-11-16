@extends('BaseData.Views.managerlayout')
@section('title', 'Счета')
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container-fluid" >

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr>
                <th scope="col" class="pl-5">Cчет</th>
                <th scope="col">Статус оплаты</th>
                <th scope="col">Договор</th>
                <th scope="col">Клиент</th>
                <th scope="col">Дата отправки</th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td scope="row" class="pl-5">{{$invoice->invoice_name}}</td>
                    <td>
                        <div class="ml-1">
                            <input type="checkbox" data-toggle="toggle" class="js_status_change"
                                   data-id="{{$invoice->invoice_id}}" data-current="{{$invoice->invoice_payed}}"
                                   data-field="invoice_payed"
                                   data-url = "{{route('manager.invoices.status')}}"
                                   data-on="Оплачен" data-off="Не оплачен"
                                   data-size="sm" data-onstyle="info"
                                   @if($invoice->invoice_payed) checked @endif>
                        </div>
                    </td>
                    <td>
                        @if($invoice->invoice_contract_id)
                            <a href="{{route('manager.contracts.show', $invoice->invoice_contract_id)}}">{{$invoice->invoice_contract_name}}</a>
                        @endif
                    </td>
                    <td>
                        @if($invoice->invoice_client_id)
                            <a href="{{route('manager.clients.show', $invoice->invoice_client_id)}}">{{$invoice->invoice_client_name}}</a>
                        @endif
                    </td>
                    <td>
                        {{date('d.m.Y', strtotime($invoice->invoice_date))}}
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
            {{$invoices->links('paging')}}
        </div>
    </div>
@endsection
