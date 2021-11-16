@forelse($contracts as $contract)
    <tr>
        <th scope="row" class="pl-5">{{$contract->contract_id}}</th>
        <td>
            <input type="checkbox" data-toggle="toggle" class="js_status_change"
                   data-id="{{$contract->contract_id}}" data-current="{{$contract->contract_status}}"
                   data-field="contract_status"
                   data-url = "{{route('manager.contracts.status')}}"
                   data-on="Вкл" data-off="Выкл"
                   data-size="sm" data-onstyle="info"
                   @if($contract->contract_status) checked @endif>
        </td>
        <td>{{$contract->contract_number}}</td>
        <td class="pl-1">{{$contract->contract_date}}</td>
        <td>
            {{$contract->contract_client_name}}
        </td>
        <td>
            <a href="{{route('manager.contracts.show', $contract->contract_id)}}">
                <i class="fas fa-pen"></i>
            </a>
            <a href="{{route('manager.contracts.destroy', $contract->contract_id)}}" class="ml-2 js_ajax_delete">
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
