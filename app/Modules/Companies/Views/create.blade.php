@extends('BaseData.Views.managerlayout')
@isset($info)
    @section('title', $info->company_name)
@else
    @section('title', 'Новый постащик')
@endisset
@include('components.bread-crumbs', with($breadcrumbs))

@section('content')
    <div class="container">
        <form class="mt-lg-5 h-100"
              @isset($info->company_id)
              action="{{route('manager.companies.update', $info->company_id)}}">
            @else
                action="{{route('manager.companies.store')}}">
            @endisset

            <fieldset class="mb-5">
                <legend>Создание нового поставщика</legend>
            </fieldset>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="company_type" class="required">Тип организации</label>
                    <select name="company_type" class="form-control" id="company_type">
                        <option value="ip">ИП</option>
                        <option value="ooo">OOO</option>
                    </select>
                </div>
                <div class="form-group col-md-5">
                    <label for="company_name" class="required">Название контрагента</label>
                    <input type="text" name="company_name" class="form-control" id="company_name"
                           placeholder="ИП Иванов Иван Иванович" required
                           value="@isset($info->company_name){{$info->company_name}}@endisset">
                </div>
                <div class="form-group col-md-5">
                    <label for="company_director" class="required">Владелец</label>
                    <input type="text" name="company_director" class="form-control" id="company_director"
                           placeholder="Иванов Иван Иванович" required
                           value="@isset($info->company_director){{$info->company_director}}@endisset">
                </div>

            </div>
            <div class="form-group">
                <label for="company_address">Адрес</label>
                <input type="text" name="company_address" class="form-control" id="company_address"
                       placeholder="123456, Россия, Республика Крым г. Симферополь, ул. Пушкина, дом № 1, квартира 1"
                       required value="@isset($info->company_address){{$info->company_address}}@endisset">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="company_phone">Телефон</label>
                    <input type="text" name="company_phone" class="form-control" id="company_phone"
                           placeholder="+79781234567"
                           value="@isset($info->company_phone){{$info->company_phone}}@endisset">
                </div>
                <div class="form-group col-md-6">
                    <label for="company_email">Электронная почта</label>
                    <input type="email" name="company_email" class="form-control" id="company_email"
                           placeholder="ivanov@mail.ru" required
                           value="@isset($info->company_email){{$info->company_email}}@endisset">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="company_inn">ИНН</label>
                    <input type="text" name="company_inn" class="form-control" id="company_inn" placeholder="910111111111"
                           required value="@isset($info->company_inn){{$info->company_inn}}@endisset">
                </div>
                <div class="form-group col-md-3">
                    @isset($info)
                        @php $in_select = false @endphp
                    @endisset
                    <label for="company_bank">Банк:</label>
                    <select name="company_bank" class="form-control" id="company_bank">
                        <option value="ПАО «РНКБ»" @if(isset($info) and $info->company_bank == "ПАО «РНКБ»") selected {{ $in_select=true }} @endif>ПАО «РНКБ»</option>
                        <option value="АО «Генбанк»" @if(isset($info) and $info->company_bank == "АО «Генбанк»") selected {{ $in_select=true }} @endif>АО «Генбанк»</option>
                        <option value="АО «Севастопольский Морской банк»"
                                @if(isset($info) and $info->company_bank == "АО «Севастопольский Морской банк»") selected {{ $in_select=true }} @endif>
                                    АО «Севастопольский Морской банк»</option>
                        <option value="ОАО «Банк ЧБРР»" @if(isset($info) and $info->company_bank == "ОАО «Банк ЧБРР»") selected {{ $in_select=true }} @endif>ОАО «Банк ЧБРР»</option>
                        <option value="АО «АБ «РОССИЯ»" @if(isset($info) and $info->company_bank == "АО «АБ «РОССИЯ»") selected {{ $in_select=true }} @endif>АО «АБ «РОССИЯ»</option>
                        <option value="АО КБ «ИС Банк»" @if(isset($info) and $info->company_bank == "АО КБ «ИС Банк»") selected {{ $in_select=true }} @endif>АО КБ «ИС Банк»</option>
                        @if(isset($info->company_bank) and !$in_select)
                            <option value="{{$info->company_bank}}" selected>{{$info->company_bank}}</option>
                        @endif
                        <option value="another">другой...</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label for="company_bik">БИК</label>
                    <input type="text" name="company_bik" class="form-control" id="company_bik" placeholder="043511111"
                           required value="@isset($info->company_bik){{$info->company_bik}}@endisset">
                </div>
                <div class="form-group col-md-4">
                    <label for="company_bill">Номер счета</label>
                    <input type="text" name="company_bill" class="form-control" id="company_bill"
                           placeholder="40801122334455667788" required
                           value="@isset($info->company_bill){{$info->company_bill}}@endisset">
                </div>
            </div>
            @isset($info->company_id)
                <button type="submit" class="btn btn-primary">Сохранить</button>
            @else
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input status" type="checkbox"
                               @if(isset($info->company_status) and !$info->company_status) @else checked
                               @endif  name="company_status"
                               id="company_status" value="1">
                        <label class="form-check-label" for="company_status">
                            Поставщик активен
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
            @endisset
        </form>
    </div>
@endsection
