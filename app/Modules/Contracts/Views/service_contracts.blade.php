
<div class="form-group col-md-12 mt-5">
    <h2>Услуги к договору</h2>
</div>

@isset($services)
    <div class="form-group col-md-5">
        <label for="service">Услуга</label>
    </div>
    <div class="form-group col-md-3">
        <label for="price">Цена</label>
    </div>
    <div class="form-group col-md-4">
        <label for="period">Периодичность</label>
    </div>
    @foreach($services as $service)
        <div class="form-group col-md-5">
            <input type="text" class="form-control" id="service" value="{{$service->service_title}}" disabled>
            <input type="hidden" class="form-control" name="cs_service_id[]" value="{{$service->service_id}}">
        </div>
        <div class="form-group col-md-3">
            <input type="number" class="form-control" id="price" name="cs_price[]" @isset($services_prices[$service->service_id]) value="{{$services_prices[$service->service_id]}}" @endisset required>
        </div>
        <div class="form-group col-md-4">

            <input type="hidden" class="form-control" name="cs_service_period[]" value="{{$service->service_period}}">
            <input type="text" class="form-control" disabled id="period"
               @isset($service)
                   @if ($service->service_period == 'monthly')
                        value="Ежемесячно"
                   @elseif($service->service_period == 'once')
                        value="Однократно"
                   @endif
               @endisset>
        </div>
    @endforeach
@endisset
