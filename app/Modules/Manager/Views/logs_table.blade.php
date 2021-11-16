
@foreach($actions as $action)
    <div class="row tw-table-row">
        <div class="col-md-1 text-center"><strong>{{$action->log_id}}</strong></div>
        <div class="col-md-5">{!! $action->log_message!!}</div>
        <div class="col-md-2 text-center">
            @if($action->log_user)
                {{$action->log_user_name}}
            @else
                СИСТЕМА
            @endif</div>
        <div class="col-md-2 text-center">{{date('d.m.Y H:i:s',$action->log_time)}}</div>
        <div class="col-md-1 text-center">{{$action->log_type}}</div>
        @if($action->log_fields_keys and $action->log_before)
            <div class="col-md-1"><a data-toggle="collapse" href="#action_{{$action->log_id}}">Подробнее <i class="fas fa-angle-down"></i></a></div>
        @endif
    </div>
    @if($action->log_fields_keys and $action->log_before)
        @php
            $action->log_fields_keys = unserialize($action->log_fields_keys);
            $action->log_fields_names = unserialize($action->log_fields_names);
            $action->log_before = unserialize($action->log_before);
            $action->log_after = unserialize($action->log_after);
        @endphp
        <div class="row">
            <div class="col-md-12 collapse multi-collapse" id="action_{{$action->log_id}}">
                <div class="tw-log-more text-center">
                    <table class="table m-auto col-md-6">
                        <tbody>
                        @foreach($action->log_before as $key=>$before)
                            <tr class="tw-log-table-row">
                                <td class="before" style="max-width: 500px">
                                    <div style=" width:100%; overflow: hidden">
                                        {{$action->log_fields_names[$key]}} - {{$before}}
                                    </div>
                                </td>
                                <td style="width:20px">=></td>

                                <td class="after" style="max-width: 500px">
                                    <div style=" width:100%; overflow: hidden">
                                        {{$action->log_fields_names[$key]}} - {{$action->log_after[$key]}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endforeach
