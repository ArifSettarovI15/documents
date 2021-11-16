
    @forelse($invoices as $invoice)
        <tr>


            <td><a href="{{route('storage', ['invoices', $invoice->invoice_file])}}">{{$invoice->invoice_name}}</a></td>
            <td>{{$invoice->service_title}}</td>
            <td>
                @if($invoice->invoice_sended == 3)
                    <span class="badge badge-pill badge-danger">Отменен</span>
                @elseif($invoice->invoice_sended == 2)
                    <span class="badge badge-pill badge-danger">Отменен</span>
                @elseif($invoice->invoice_sended == 1)
                    <span class="badge badge-pill badge-success">Отправлен</span>
                @elseif($invoice->invoice_sended == 0)
                    <span class="badge badge-pill badge-info">Ожидает</span>
                @endif
            </td>
            <td>
                @if($invoice->invoice_sended == 3)
                    <span class="badge badge-pill badge-success">Оплачен</span>
                @elseif($invoice->invoice_sended == 0)
                    <span class="badge badge-pill badge-info">Ожидает</span>
                @else
                    @if($invoice->invoice_payed)
                        <span class="badge badge-pill badge-success">Оплачен</span>
                    @else
                        <span class="badge badge-pill badge-danger">Не оплачен</span>
                    @endif
                @endif
            </td>
        </tr>
    @empty
        <h2>У клиента пока нет счетов</h2>
    @endforelse


