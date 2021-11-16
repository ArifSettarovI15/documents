@forelse($clients as $client)
    <tr>
        <th scope="row" class="pl-5">{{$client->client_id}}</th>
        <td>
            <input type="checkbox" data-toggle="toggle" class="js_status_change"
                   data-id="{{$client->client_id}}" data-current="{{$client->client_status}}"
                   data-field="client_status"
                   data-url = "{{route('manager.clients.status')}}"
                   data-on="Вкл" data-off="Выкл"
                   data-size="sm" data-onstyle="info"
                   @if($client->client_status) checked @endif>
        </td>
        <td><a href="{{route('manager.clients.info', $client->client_id)}}">{{$client->client_name}}</a></td>
        <td class="pl-5">
            @switch($client->client_autosend)
                @case("1")Автоматическая @break
                @case("0")Ручная @break
            @endswitch
        </td>
        <td>
            <a href="{{route('manager.clients.show', $client->client_id)}}">
                <i class="fas fa-pen"></i>
            </a>
            <a href="{{route('manager.clients.destroy', $client->client_id)}}" class="ml-2 js_ajax_delete">
                <i class="fas fa-trash-alt icon_danger"></i>
            </a>
        </td>

    </tr>
@empty
    <tr>
        <td colspan="7" style="text-align: center; padding: 100px 0;">
            <h2>Нет данных для вывода</h2>
        </td>
    </tr>
@endforelse
