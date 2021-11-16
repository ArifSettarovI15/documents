<form class="mt-lg-5 h-100"
      @isset($info->client_id)
      action="{{route('manager.clients.update', $info->client_id)}}">
    @else
        action="{{route('manager.clients.store')}}">
    @endisset

    <fieldset class="mb-5">
        @isset($info)
            <legend>{{$info->client_name}}</legend>
        @else
            <legend>Создание нового клиента - Физическое лицо</legend>
        @endisset
    </fieldset>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="client_name" class="required">ФИО лица</label>
            <input type="hidden" name="client_type" value="fiz">
            <input type="text" name="client_name" class="form-control" id="client_name"
                   placeholder="Иванов Иван Иванович" required
                   value="@isset($info->client_name){{$info->client_name}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_birthday" class="required">Дата рождения</label>
            <input type="text" name="client_birthday" class="form-control datepicker" id="client_birthday"
                   placeholder="01.01.2000" required
                   value="@isset($info->client_birthday){{$info->client_birthday}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_birthplace">Место рождения</label>
            <input type="number" name="client_birthplace" class="form-control" id="client_birthplace"
                   placeholder="Симферополь" required
                   value="@isset($info->client_birthplace){{$info->client_birthplace}}@endisset">
        </div>
        <div class="form-group col-md-3">
            <label for="client_passport">Паспорт (серия, номер)</label>
            <input type="number" name="client_passport" class="form-control" id="client_passport"
                   placeholder="0914556000" required
                   value="@isset($info->client_passport){{$info->client_passport}}@endisset">
        </div>
        <div class="form-group col-md-5">
            <label for="client_passport_issued">Кем выдан</label>
            <input type="email" name="client_passport_issued" class="form-control" id="client_passport_issued"
                   placeholder="МВД по Республике Крым в г. Симферополь" required
                   value="@isset($info->client_passport_issued){{$info->client_passport_issued}}@endisset">
        </div>
        <div class="form-group col-md-2">
            <label for="client_passport_date">Дата выдачи</label>
            <input type="email" name="client_passport_date" class="form-control datepicker" id="client_passport_date"
                   placeholder="01.01.2001" required
                   value="@isset($info->client_passport_date){{$info->client_passport_date}}@endisset">
        </div>
        <div class="form-group col-md-2">
            <label for="client_passport_code">Код подразделения</label>
            <input type="email" name="client_passport_code" class="form-control datepicker" id="client_passport_code"
                   placeholder="910-001" required
                   value="@isset($info->client_passport_code){{$info->client_passport_code}}@endisset">
        </div>

    </div>
    <div class="form-group">
        <label for="client_address">Адрес регистрации (прописка)</label>
        <input type="text" name="client_address" class="form-control" id="client_address"
               placeholder="123456, Россия, Республика Крым г. Симферополь, ул. Пушкина, дом № 1, квартира 1"
               required value="@isset($info->client_address){{$info->client_address}}@endisset">
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="client_phone">Телефон</label>
            <input type="text" name="client_phone" class="form-control" id="client_phone"
                   placeholder="+79781234567"
                   value="@isset($info->client_phone){{$info->client_phone}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_email">Email</label>
            <input type="text" name="client_email" class="form-control" id="client_email"
                   placeholder="ivanov@mail.ru"
                   value="@isset($info->client_email){{$info->client_email}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_site">Сайт</label>
            <input type="text" name="client_site" class="form-control" id="client_site"
                   placeholder="http://example.com"
                   value="@isset($info->client_site){{$info->client_site}}@endisset">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="client_inn">ИНН</label>
            <input type="text" name="client_inn" class="form-control" id="client_inn" placeholder="910111111111"
                   required value="@isset($info->client_inn){{$info->client_inn}}@endisset">
        </div>
        <div class="form-group col-md-3">
            @isset($info)
                @php $in_select = false @endphp
            @endisset
            <label for="client_bank">Банк:</label>
            <select name="client_bank" class="form-control" id="client_bank">
                <option value="ПАО «РНКБ»" @if(isset($info) and $info->client_bank == "ПАО «РНКБ»") selected {{ $in_select=true }} @endif>ПАО «РНКБ»</option>
                <option value="АО «Генбанк»" @if(isset($info) and $info->client_bank == "АО «Генбанк»") selected {{ $in_select=true }} @endif>АО «Генбанк»</option>
                <option value="АО «Севастопольский Морской банк»"
                        @if(isset($info) and $info->client_bank == "АО «Севастопольский Морской банк»") selected {{ $in_select=true }} @endif>
                    АО «Севастопольский Морской банк»</option>
                <option value="ОАО «Банк ЧБРР»" @if(isset($info) and $info->client_bank == "ОАО «Банк ЧБРР»") selected {{ $in_select=true }} @endif>ОАО «Банк ЧБРР»</option>
                <option value="АО «АБ «РОССИЯ»" @if(isset($info) and $info->client_bank == "АО «АБ «РОССИЯ»") selected {{ $in_select=true }} @endif>АО «АБ «РОССИЯ»</option>
                <option value="АО КБ «ИС Банк»" @if(isset($info) and $info->client_bank == "АО КБ «ИС Банк»") selected {{ $in_select=true }} @endif>АО КБ «ИС Банк»</option>
                @if(isset($info->client_bank) and !$in_select)
                    <option value="{{$info->client_bank}}" selected>{{$info->client_bank}}</option>
                @endif
                <option value="another">другой...</option>
            </select>
        </div>

        <div class="form-group col-md-2">
            <label for="client_bik">БИК</label>
            <input type="text" name="client_bik" class="form-control" id="client_bik" placeholder="043511111"
                   required value="@isset($info->client_bik){{$info->client_bik}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_bill">Номер счета</label>
            <input type="text" name="client_bill" class="form-control" id="client_bill"
                   placeholder="40801122334455667788" required
                   value="@isset($info->client_bill){{$info->client_bill}}@endisset">
        </div>
    </div>
    <div class="form-row ">

        <div class="form-group col-md-4">
            <label for="client_autosend">Метод отправки</label>
            <select name="client_autosend" class="form-control" id="client_autosend">
                <option value="1" @if(isset($info->client_autosend) and $info->client_autosend) selected @endif>
                    Автоматическая
                </option>
                <option value="0"
                        @if(isset($info->client_autosend) and !$info->client_autosend) selected @endif>Ручная
                </option>
            </select>
        </div>
        <div class="form-group col-md-4 invisible">
            <label for="client_send_date">Дата отправки</label>
            <select name="client_send_date" class="form-control" id="client_send_date">
                @for($i=1; $i<=30; $i++)
                    <option value="{{$i}}"
                            @if(isset($info->client_send_date) and $info->client_send_date == $i)
                            selected
                        @endif>{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="form-group col-md-4 invisible">
            <label for="client_send_period">Периодичность</label>
            <select name="client_send_period" class="form-control" id="client_send_period">
                <option value="monthly"
                        @if(isset($info->client_send_period) and $info->client_send_period == 'monthly') selected @endif>
                    Ежемесячно
                </option>
                <option value="weekly"
                        @if(isset($info->client_send_period) and $info->client_send_period == 'weekly') selected @endif>
                    Еженедельно
                </option>
                <option value="daily"
                        @if(isset($info->client_send_period) and $info->client_send_period == 'daily') selected @endif>
                    Ежедневно
                </option>
                <option value="once"
                        @if(isset($info->client_send_period) and $info->client_send_period == 'once') selected @endif>
                    Однократно
                </option>
            </select>
        </div>
    </div>

        <div class="form-row">
            @if(isset($info->client_contracts) and count($info->client_contracts) > 0)
                <div class="form-group mt-3 col-md-6">
                    <h3>Договора клиента: </h3>
                    <div class="ml-5">
                        @foreach($info->client_contracts as $contract)
                            <a href="{{route('manager.contracts.show', $contract->contract_id)}}">Договор № {{$contract->contract_number}} от {{$contract->contract_date}}</a>
                            <br>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(isset($info) and $info->client_has_invoices)
                <div class="form-group col-md-6">
                    <h3>Счета клиента:</h3>
                    <div class="ml-5">
                        <a href="{{route('manager.invoices.client', $info->client_id)}}">Список счетов {{$info->client_name}}</a>
                    </div>
                </div>
            @endif
        </div>

    @isset($info->client_id)
        <button type="submit" class="btn btn-primary">Сохранить</button>
    @else
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input status" type="checkbox"
                       @if(isset($info->client_status) and !$info->client_status) @else checked
                       @endif  name="client_status"
                       id="client_status" value="1">
                <label class="form-check-label" for="client_status">
                    Клиент активен
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
    @endisset
</form>
