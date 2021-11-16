@extends('BaseData.Views.managerlayout')

@isset($info)
    @section('title', $info->service_title)
@else
    @section('title', 'Новая услуга')
@endisset
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container">
        <form class="mt-lg-5 h-100"
            @isset($info->service_id)
              action="{{route('manager.services.update', $info->service_id)}}"
            @else
                action="{{route('manager.services.store')}}"
            @endisset >

            <fieldset class="mb-5">
                @isset($info->service_id)
                    <legend>Услуга - {{$info->service_title}}</legend>
                @else
                    <legend>Создание новой услуги</legend>
                @endisset
            </fieldset>

            <div class="form-group">
                <label for="service_title" class="required">Название услуги</label>
                <input type="text" name="service_title" class="form-control" id="service_title"
                       placeholder="СЕО продвижение" required
                       value="@isset($info->service_title){{$info->service_title}}@endisset">
            </div>
            <div class="form-group">
                <label for="service_writen" class="required">Написание в документа:</label>
                <input type="text" name="service_writen" class="form-control" id="service_writen"
                       placeholder="За сео продвижение" required
                       value="@isset($info->service_writen){{$info->service_writen}}@endisset">
            </div>
            <div class="form-group">
                <label for="service_price" class="required">Цена услуги по умолчанию</label>
                <input type="text" name="service_price" class="form-control" id="service_price"
                       placeholder="1000"
                       value="@isset($info->service_price){{$info->service_price}}@endisset">
            </div>
            <div class="form-group">
                <label for="service_period">Периодичность</label>
                <select name="service_period" class="form-control" id="service_period">
                    <option value="monthly"
                            @if(isset($info->service_period) and $info->service_period == 'monthly') selected @endif>
                        Ежемесячно
                    </option>
                    <option value="weekly"
                            @if(isset($info->service_period) and $info->service_period == 'weekly') selected @endif>
                        Еженедельно
                    </option>
                    <option value="daily"
                            @if(isset($info->service_period) and $info->service_period == 'daily') selected @endif>
                        Ежедневно
                    </option>
                    <option value="once"
                            @if(isset($info->service_period) and $info->service_period == 'once') selected @endif>
                        Однократно
                    </option>
                </select>
            </div>
            @isset($info->service_id)
                <button type="submit" class="btn btn-primary">Сохранить</button>
            @else
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input status" type="checkbox"
                               @if(isset($info->service_status) and !$info->service_status) @else checked
                               @endif  name="service_status"
                               id="service_status" value="1">
                        <label class="form-check-label" for="service_status">
                            Услуга активна
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
            @endisset
        </form>
    </div>
@endsection
