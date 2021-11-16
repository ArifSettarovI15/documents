@forelse($invoices as $invoice)
    <tr class="text-center">
        <td><a href="{{route('storage',['invoices',$invoice->invoice_file])}}">{{$invoice->invoice_name}}</a></td>
        <td>
            <div>
                @if($invoice->invoice_sended == 0)
                    <span class="badge badge-pill badge-info">Ожидает отправки</span>
                @elseif($invoice->invoice_sended == 1)
                    <span class="badge badge-pill badge-success">Отправлен {{date('d.m.Y',strtotime($invoice->invoice_date))}}</span>
                    <br>
                    @if($invoice->invoice_payed)
                        <span class="badge badge-pill badge-success">Оплачен</span>
                    @else
                        <span class="badge badge-pill badge-info">Ожидает оплаты</span>
                    @endif
                @elseif($invoice->invoice_sended == 2)
                    <span class="badge badge-pill badge-danger">Отменен</span>
                @elseif($invoice->invoice_sended == 3)
                    <span class="badge badge-pill badge-success">Оплачен</span>
                    <span class="badge badge-pill badge-danger">Отменен</span>
                @endif

            </div>
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
            {{date('d.m.Y', strtotime($invoice->invoice_date))}}
        </td>
        <td>
            @if($invoice->invoice_sended==2)
                <button type="button" class="btn btn-outline-success redo_send_invoice" title="Возобновить отправку"
                        data-url="{{route('manager.invoices.redo_send',$invoice->invoice_id)}}" style="border-radius: 25px">
                    <i class="fa fa-redo"></i>
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
