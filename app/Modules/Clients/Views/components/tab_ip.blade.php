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
            <legend>Создание нового клиента - Индивидуальный предприниматель</legend>
        @endisset
    </fieldset>
    <div class="form-row">
        <div class="form-group col-md-4">
            <input type="hidden" name="client_type" value="ip">
            <label for="client_name" class="required">Название контрагента</label>
            <input type="text" name="client_name" class="form-control" id="client_name"
                   placeholder="ИП Иванов Иван Иванович" required
                   value="@isset($info->client_name){{$info->client_name}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_director" class="required">ФИО владельца</label>
            <input type="text" name="client_director" class="form-control" id="client_director"
                   placeholder="Иванов Иван Иванович" required
                   value="@isset($info->client_director){{$info->client_director}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_email">Электронная почта</label>
            <input type="hidden" id="client_email" name="client_email" required>
            <button type="button" class="form-control btn btn-info" data-toggle="modal" data-target="#exampleModalCenter">
                Открыть список
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document" style="min-width: 1000px">
                    <div class="modal-content" style="min-width: 1000px">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Список электронных адресов</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table ">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th ><label for="position">Должность</label></th>
                                        <th ><label for="name">ФИО</label></th>
                                        <th ><label for="email">Email</label></th>
                                        <th>
                                            <div class="tooltip_wrapper"><i class="fa fa-info-circle"></i>
                                                <span class="tooltiptext">Выберите email для отправки счетов</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="emails_table">
                                    @isset($emails)
                                        @forelse($emails as $email)
                                            <tr>
                                                <td style="width: 50px !important;"><a type="button" class="btn delete_email_row btn-outline-danger " >X</a></td>
                                                <td><input value="{{$email->email_position}}" class="form-control required"
                                                           type="text" id='position' name="email_position[]"></td>
                                                <td><input value="{{$email->email_name}}" class="form-control required"
                                                           type="text" id='name' name="email_name[]"></td>
                                                <td><input value="{{$email->email_email}}" class="form-control required"
                                                           type="email" id='email' name="email_email[]"></td>
                                                <td class="text-center"><input class="form-check-input mt-2 email_invoices"
                                                                               type="radio" name="exampleRadios"
                                                                               id="exampleRadios1"
                                                                               @if($email->email_email == $info->client_email)checked @endif>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td style="width: 50px !important;"><button type="button" class="btn delete_email_row btn-outline-danger " >X</button></td>
                                                <td><input value="" class="form-control required" type="text" id='position'
                                                           name="email_position[]"></td>
                                                <td><input value="" class="form-control required" type="text" id='name'
                                                           name="email_name[]"></td>
                                                <td><input value="" class="form-control required" type="email" id='email'
                                                           name="email_email[]"></td>
                                                <td class="text-center"><input class="form-check-input mt-2 email_invoices"
                                                                               type="radio" name="exampleRadios"
                                                                               id="exampleRadios1" checked></td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td style="width: 50px !important;"><a href="" type="button" class="btn delete_email_row btn-outline-danger " >X</a></td>
                                            <td><input value="" class="form-control required" type="text" id='position'  name="email_position[]" ></td>
                                            <td><input value="" class="form-control required" type="text" id='name' name="email_name[]" ></td>
                                            <td><input value="" class="form-control required" type="email" id='email' name="email_email[]" ></td>
                                            <td class="text-center"><input class="form-check-input mt-2 email_invoices" type="radio" name="exampleRadios" id="exampleRadios1" checked></td>
                                        </tr>
                                    @endisset
                                </tbody>
                            </table>
                            <div class="text-center">
                                <button type="button" data-target="emails_table" class="btn btn-outline-dark tooltip_wrapper emails_add_row" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Добавить строку</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="client_address">Адрес</label>
        <input type="text" name="client_address" class="form-control" id="client_address"
               placeholder="123456, Россия, Республика Крым г. Симферополь, ул. Пушкина, дом № 1, квартира 1"
               required value="@isset($info->client_address){{$info->client_address}}@endisset">
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="client_phone">Телефон</label>
            <input type="text" name="client_phone" class="form-control" id="client_phone"
                   placeholder="+79781234567"
                   value="@isset($info->client_phone){{$info->client_phone}}@endisset">
        </div>
        <div class="form-group col-md-6">
            <label for="client_site">Сайт</label>
            <input type="text" name="client_site" class="form-control" id="client_site"
                   placeholder="http://example.com"
                   value="@isset($info->client_site){{$info->client_site}}@endisset">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="client_inn">ИНН</label>
            <input type="text" name="client_inn" class="form-control" id="client_inn" placeholder="910111111111"
                   required value="@isset($info->client_inn){{$info->client_inn}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_ogrn">ОГРН</label>
            <input type="text" name="client_ogrn" class="form-control" id="client_ogrn" placeholder="308211111111111"
                   required value="@isset($info->client_ogrn){{$info->client_ogrn}}@endisset">
        </div>
        <div class="form-group col-md-4">
            <label for="client_okpo">ОКПО</label>
            <input type="text" name="client_okpo" class="form-control" id="client_okpo" placeholder="0101234567"
                   required value="@isset($info->client_okpo){{$info->client_okpo}}@endisset">
        </div>
        <div class="form-group col-md-5">
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

        <div class="form-group col-md-3">
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
    <div class="form-row">
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
        <div class="form-group col-md-4 invisible" >
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
