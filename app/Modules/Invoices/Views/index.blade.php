@extends('BaseData.Views.managerlayout')
@section('title', 'Счета')
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container-fluid" >


        <table class="table table-striped" style="margin-top: 70px;">
            <thead>
            <tr>
                <th scope="col" class="pl-5">Cчет</th>
                <th scope="col">Статус</th>
                <th scope="col">Договор</th>
                <th scope="col">Клиент</th>
                <th scope="col">Дата отправки</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td scope="row" class="pl-5">{{$invoice->invoice_name}}</td>
                    <td>

                            @if($invoice->invoice_sended == 0)
                                <span class="badge badge-pill badge-info">Ожидает отправки</span>
                            @elseif($invoice->invoice_sended == 1)
                                <span class="badge badge-pill badge-success">Отправлен</span>
                                <br>
                                @if($invoice->invoice_payed)
                                    <span class="badge badge-pill badge-success">Оплачен</span>
                                @else
                                    <span class="badge badge-pill badge-info">Ожидает оплаты</span>
                                @endif
                            @elseif($invoice->invoice_sended == 2)
                                <span class="badge badge-pill badge-danger">Отменен</span>
                            @elseif($invoice->invoice_sended == 3)
                                <span class="badge badge-pill badge-info">Скачан</span>
                        @elseif($invoice->invoice_sended == 5)
                                <span class="badge badge-pill badge-secondary">Отправлен повторно</span>
                            @endif
{{--                        <input type="checkbox" data-toggle="toggle" class="js_status_change"--}}
{{--                               data-id="{{$invoice->invoice_id}}" data-current="{{$invoice->invoice_send_active}}"--}}
{{--                               data-field="invoice_send_active"--}}
{{--                               data-url = "{{route('manager.invoices.status')}}"--}}
{{--                               data-on="Вкл" data-off="Выкл"--}}
{{--                               data-size="sm" data-onstyle="info"--}}
{{--                               @if($invoice->invoice_send_active) checked @endif>--}}
{{--                        </div>--}}
                    </td>
                    <td>
                        @if($invoice->invoice_contract_id)
                            <a href="{{route('manager.contracts.show', $invoice->invoice_contract_id)}}">{{$invoice->invoice_contract_name}}</a>
                        @else
                            {{$invoice->invoice_custom_contract}}
                        @endif
                    </td>
                    <td>
                        @if($invoice->invoice_client_id)
                            <a href="{{route('manager.clients.show', $invoice->invoice_client_id)}}">{{$invoice->invoice_client_name}}</a>
                        @else
                            {{$invoice->invoice_custom_client}}
                        @endif
                    </td>
                    <td>
                        {{date('d.m.y', strtotime($invoice->invoice_date))}}
                    </td>
                    <td>
                        @if($invoice->invoice_sended == 0)
                            <button type="button" class="btn btn-outline-danger redo_send_invoice" title="Отменить отправку"
                                    data-url="{{route('manager.invoices.off_send',$invoice->invoice_id)}}" style="border-radius: 25px">
                                X
                            </button>
                        @endif
                        @if($invoice->invoice_sended == 1)
                                <button type="button" class="btn btn-outline-success redo_send_invoice" title="Отметить оплаченным"
                                        data-url="{{route('manager.invoices.check_payed',$invoice->invoice_id)}}" style="border-radius: 25px">
                                    <i class="fa fa-donate"></i>
                                </button>
                        @endif
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
