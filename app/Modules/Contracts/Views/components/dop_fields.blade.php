
<div class="form-group col-md-12">
    <h3>Дополнительные поля</h3>
</div>


@isset($type_info)
    @if(strlen($type_info->ct_dop_keys) and strlen($type_info->ct_dop_names))
        @php
            $dop_keys = unserialize($type_info->ct_dop_keys);
            $dop_names = unserialize($type_info->ct_dop_names);
            $dop_tooltips = unserialize($type_info->ct_dop_tooltips);
        @endphp
    @endif
@endisset
@if(isset($info) and strlen($info->contract_dop))
    @php $contract_dops = unserialize($info->contract_dop)@endphp
@endif

@isset($dop_keys)
    @foreach($dop_keys as $index=>$dop_key)
        <div class="form-group col-md-12">
            <label for="dop_key">{{$dop_names[$index]}} <div class="tooltip_wrapper"><i class="fa fa-info-circle"></i>
                    <span class="tooltiptext">{{$dop_tooltips[$index]}}</span>
                </div>
            </label>
            <input type="text" required class="form-control" name="dop_val[]" @if(isset($contract_dops) and isset($contract_dops[trim($dop_key)])) value="{{$contract_dops[trim($dop_key)]}}" @endif id="dop_key">
            <input type="hidden" class="form-control" name="dop_key[]" value="{{trim($dop_key)}}" id="dop_key">
        </div>
    @endforeach
@endisset
