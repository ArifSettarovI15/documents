@extends('BaseData.Views.managerlayout')
@isset($info)
    @section('title', 'План №'.$info->plan_id)
@else
    @section('title', 'Новый план')
@endisset
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container">
        <form class="mt-lg-5 h-100"
            @isset($info->plan_id)
              action="{{route('manager.plans.update', $info->plan_id)}}">
            @else
                action="{{route('manager.plans.store')}}">
            @endisset

            <fieldset class="mb-5">
                @isset($info)
                    <legend>План {{$info->plan_id}}, редактирование</legend>
                @else
                    <legend>Создание нового плана</legend>
                @endisset

            </fieldset>

            <div class="form-group col-md-12 ">
                <label for="plan_contract" class="required">Договор</label>
                <select name="plan_contract" id="plan_contract" class="form-control">
                    <option value="">Выберите</option>

                    @isset($contracts)
                        @foreach($contracts as $contract)
                            <option value="{{$contract->contract_id}}" @if(isset($info) and $info->plan_contract == $contract->contract_id) selected @endif>Договор {{$contract->contract_number}} от {{$contract->contract_date}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-5">
            <label for="plan_day" class="required">Число отправки счетов</label>
            <select name="plan_day" id="plan_day" class="form-control">
                @for($i=1; $i<=10; $i++)
                    <option value="{{$i}}" @if(isset($info) and $info->plan_day == $i) selected @endif>{{$i}}</option>
                @endfor
            </select>
            </div>
            @isset($info->plan_id)
                <button type="submit" class="btn btn-primary">Сохранить</button>
            @else
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input status" type="checkbox"
                               @if(isset($info->plan_status) and !$info->plan_status) @else checked
                               @endif  name="plan_status"
                               id="plan_status" value="1">
                        <label class="form-check-label" for="plan_status">
                            План активен
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
            @endisset
        </form>
    </div>
@endsection
