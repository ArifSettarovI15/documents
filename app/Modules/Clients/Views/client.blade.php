@extends('BaseData.Views.managerlayout')
@isset($info)
    @section('title', $info->client_name)
@else
    @section('title', 'Новый клиент')
@endisset
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container  mt-5">
        <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#custom_invoice_modal"
                data-url="{{route('manager.invoices.get_by_contract')}}">Создать счет</button>
        @include('Clients.Views.components.create_custom_invoice_modal')


        <a  href="{{route('manager.contracts.create', ['client_id'=>$info->client_id])}}" class="btn btn-outline-success ml-5">Создать договор</a>
        @if(count($client_contracts) > 0)
        <button type="button" class="btn ml-5 btn-outline-success get_invoice_for_client" style="width: 250px"
                    @if(count($client_contracts) == 1) data-contract="{{$client_contracts[0]->contract_id}}" @endif
                    data-url="{{route('manager.invoices.get_by_contract')}}">Создать счет по договору</button>
        @endif
        @if(count($client_contracts) > 1)
            <div class="modal fade" id="get_invoice_for_client_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Договор</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body form-group">
                            <label for="contract_id">Выберите договор из списка:</label>
                            <select name="contract_id" class="form-control" id="contract_id">
                                @foreach($client_contracts as $contract)
                                    <option value="{{$contract->contract_id}}">Договор № {{$contract->contract_number}} от {{$contract->contract_date}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary button_success_get_invoice">Подтвердить</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <ul class="nav nav-tabs nav-justified mt-3" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#client_info" role="tab" aria-controls="home" aria-selected="true">Информация клиента</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#contracts" role="tab" aria-controls="profile" aria-selected="false">Договора</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#invoices" role="tab" aria-controls="contact" aria-selected="false">Счета</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active " id="client_info" role="tabpanel" aria-labelledby="home-tab">
                    <table class="table">
                        @if($info->client_type != 'fiz')
                            <tr>
                                <td><b>Название организации</b></td>
                                <td>{{$info->client_name}}</td>
                            </tr>
                            <tr>
                                <td><b>Владелец</b></td>
                                <td>{{$info->client_director}}</td>
                            </tr>
                        @else
                            <tr>
                                <td><b>ФИО клиента</b></td>
                                <td>{{$info->client_name}}</td>
                            </tr>
                            <tr>
                                <td><b>Дата рождения</b></td>
                                <td>{{$info->client_birthday}}</td>
                            </tr>
                            <tr>
                                <td><b>Место рождения</b></td>
                                <td>{{$info->client_birthplace}}</td>
                            </tr>
                            <tr>
                                <td><b>Паспорт (серия, номер)</b></td>
                                <td>{{$info->client_passport}}</td>
                            </tr>
                            <tr>
                                <td><b>Кем и когда выдан</b></td>
                                <td>{{$info->client_passport_issued}} {{$info->client_passport_date}}</td>
                            </tr>
                            <tr>
                                <td><b>Код подразделения</b></td>
                                <td>{{$info->client_passport_code}}</td>
                            </tr>

                        @endif
                        <tr>
                            <td><b>Email для отправки счетов</b></td>
                            <td>{{$info->client_email}}</td>
                        </tr>
                        @if($info->client_type != 'fiz')
                            @isset($emails)
                                <tr>
                                    <td><b>Список Email</b></td>
                                    <td>
                                        <table>
                                            @foreach($emails as $email)
                                                <tr>
                                                    <td>{{$email->email_position}}</td>
                                                    <td>{{$email->email_name}}</td>
                                                    <td>{{$email->email_email}}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endisset
                        @endif
                        <tr>
                            <td><b>Адрес</b></td>
                            <td>{{$info->client_address}}</td>
                        </tr>
                        <tr>
                            <td><b>Телефон</b></td>
                            <td>{{$info->client_phone}}</td>
                        </tr>
                        <tr>
                            <td><b>Сайт</b></td>
                            <td>{{$info->client_site}}</td>
                        </tr>
                        <tr>
                            <td><b>ИНН клиента</b></td>
                            <td>{{$info->client_inn}}</td>
                        </tr>
                        @if($info->client_type != 'fiz')
                            <tr>
                                <td><b>ИНН клиента</b></td>
                                <td>{{$info->client_inn}}</td>
                            </tr>
                            <tr>
                                <td><b>ОГРН клиента</b></td>
                                <td>{{$info->client_ogrn}}</td>
                            </tr>
                            @isset($info->client_kpp)
                                <tr>
                                    <td><b>КПП клиента</b></td>
                                    <td>{{$info->client_kpp}}</td>
                                </tr>
                            @endisset
                            <tr>
                                <td><b>ОКПО клиента</b></td>
                                <td>{{$info->client_okpo}}</td>
                            </tr>
                        @else
                            <tr>
                                <td><b>ОКПО клиента</b></td>
                                <td>{{$info->client_okpo}}</td>
                            </tr>
                        @endif
                            <tr>
                                <td><b>Банк клиента</b></td>
                                <td>{{$info->client_bank}}</td>
                            </tr>
                            <tr>
                                <td><b>БИК банка</b></td>
                                <td>{{$info->client_bik}}</td>
                            </tr>
                            <tr>
                                <td><b>Номер счета клиента</b></td>
                                <td>{{$info->client_bill}}</td>
                            </tr>

                    </table>



                </div>
                <div class="tab-pane fade " id="contracts" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="mt-5">
                        @forelse($info->client_contracts as $contract)
                            <a href="{{route('manager.contracts.show', $contract->contract_id)}}">Договор № {{$contract->contract_number}} от {{$contract->contract_date}}</a>
                            <br>
                        @empty
                            <h2>У клиента пока нет договоров</h2>
                        @endforelse
                    </div>
                </div>
                <div class="tab-pane fade " id="invoices" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="mt-5">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Счет</th>
                                    <th>Услуга</th>
                                    <th>Статус отправки</th>
                                    <th>Статус оплаты</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>
                                        <select type="text" class="table-filter" name="si_service_id" data-type="filter">
                                            <option value="">Все</option>
                                            @foreach($services as $service)
                                                <option value="{{$service->service_id}}">{{$service->service_title}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="table-data">
                                @include('Clients.Views.components.client_invoices_table')
                            </tbody>
                        </table>
                        <div class="paging-data mt-5">
                            {{$invoices->links('paging')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
