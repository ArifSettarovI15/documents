@extends('BaseData.Views.managerlayout')
@isset($info)
    @section('title', 'Договор № '.$info->contract_number)
@else
    @section('title', 'Новый договор')
@endisset
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container">
        <form class="mt-lg-5 mb-5 h-100"
              @isset($info->contract_id)
              action="{{route('manager.contracts.update', $info->contract_id)}}">
            @else
                action="{{route('manager.contracts.store')}}">
            @endisset

            <fieldset class="mb-5">
                @isset($info)
                    <legend>Редактирование договора {{$info->contract_number}}</legend>
                    <div class="row">
                        <div class="col-md-5 contracts_links_wrap">
                            @if($info->contract_file_id)
                                <a class='contract_html_download_url' href="{{$info->contract_file_url}}" target="_blank">{{$info->contract_file_name}}</a> <br>
                            @endif
                            @if($info->contract_doc_file_id)
                                <a class='contract_doc_download_url' href="{{$info->contract_doc_file_url}}">{{$info->contract_doc_file_name}}</a>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if($info->contract_file_id)
                                <button type="button" class="ml-3 btn btn-outline-info contract_to_doc" data-url="{{route('manager.contracts.convert_to_doc',$info->contract_id)}}">Выгрузить договор в .docx файл</button>
                            @endif
                        </div>
                        @if($plan)
                        <div class="col-md-3">
                                <a href="{{route('manager.plans.show', $plan->plan_id)}}">План текущего договора</a>
                        </div>
                        @endif
                    </div>
                @else
                    <legend>Создание нового договора</legend>
                @endisset
            </fieldset>
            <div class="form-row">
                <div class="form-group col-md-4">
                    @isset($contract_types)
                        <label for="contract_type">Шаблон договора</label>
                        <select name="contract_type" id="contract_type" class="form-control contract_type" required
                                @isset($info) disabled @endisset
                                data-url="{{route('manager.contract_types.services')}}">
                            @foreach($contract_types as $contract_type)
                                <option value="{{$contract_type->ct_id}}"
                                        data-litera="{{$contract_type->ct_litera}}"
                                        data-number="{{$contract_type->data_number}}"
                                        @if(isset($info->contract_type) and $info->contract_type == $contract_type->ct_id)
                                            selected
                                        @endif>{{$contract_type->ct_name}}</option>
                            @endforeach
                        </select>
                    @else
                        <br>
                        <a href="{{route('manager.contract_types.create')}}">Нет активных типов договора</a>
                    @endisset
                </div>
                <div class="form-group col-md-2">
                    <label for="contract_number" class="required">№ договора</label>
                    <input type="text" name="contract_number" class="form-control contract_number" id="contract_number" readonly
                           value="@isset($info->contract_number){{$info->contract_number}}@endisset">
                    </div>
                <div class="form-group col-md-6">
                    <label for="contract_date" class="required">Дата договора</label>
                    <input type="text" name="contract_date" class="form-control datepicker" id="contract_date" required
                           placeholder="01.01.2001"
                           value="@isset($info->contract_date){{$info->contract_date}} @else {{date('d.m.Y')}}@endisset">
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="contract_supplier">Поставщик</label>
                    @isset($info) <input type="hidden" name="contract_supplier" value="{{$info->contract_supplier}}">@endisset
                    <select name="contract_supplier" @isset($info) disabled @endisset class="form-control" id="contract_supplier" required>
                        @foreach($suppliers as $supplier)
                            <option value="{{$supplier->company_id}}"
                                    @if(isset($info) and $info->contract_supplier == $supplier->company_id) selected @endif>{{$supplier->company_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="contract_client">Контрагент</label>
                    @isset($info) <input type="hidden" name="contract_client" value="{{$info->contract_client}}">@endisset
                    <select name="contract_client" @isset($info) disabled @endisset
                            @if(count($clients) == 1) readonly @endif
                            class="form-control" id="contract_client" required>
                        @if(count($clients)>1)
                            <option value="">Выберите</option>
                        @endif
                        @foreach($clients as $client)
                            <option value="{{$client->client_id}}"
                                    @if(isset($info) and $info->contract_client == $client->client_id) selected @endif>{{$client->client_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row services_section">
                @include('Contracts.Views.service_contracts')
            </div>
            <div class="form-row mt-5 dop_section">
                @include("Contracts/Views/components/dop_fields")
            </div>
            @if(!isset($info))
                <div class="form-row mt-5">
                    <div class="form-group col-md-6">
                        <label for="plan_date">План отправки счетов:</label>
                        <select name="plan_date" class="form-control">
                            <option value="">Не создавать план</option>
                            @for($i =1; $i < 15; $i++)
                                <option value="{{$i}}">{{$i}} число месяца</option>
                            @endfor
                        </select>
                    </div>
                </div>
            @endif


            @isset($info->contract_id)
                    <input type="hidden" disabled class="contract_id" value="{{$info->contract_id}}">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                @isset($info->contract_doc_file_id)
                    <button type="button" class="btn float-right btn-outline-success" onclick="$('#modalSendContractToClient').modal('show')">Отправить договор клиенту</button>
                    <div class="modal fade" id="modalSendContractToClient" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Отправка договора клиенту</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="contract_download_url">Договор</label>
                                        <a class='contract_doc_download_url form-control-file' id="contract_download_url" data-fileId="{{$info->contract_doc_file_id}}" href="{{$info->contract_doc_file_id}}">{{$info->contract_doc_file_name}}</a>
                                    </div>
                                    <div class="">
                                        <label for="multiple_service required">Email</label>
                                        <select class="client_email required" id="multiple_service" multiple
                                                name="client_email">
                                            @foreach($info->contract_client_emails as $email)
                                                <option value="{{$email->email_email}}">{{$email->email_email}}</option>
                                            @endforeach
                                        </select>
                                        <span>и/или введите</span>
                                        <input type="email" name="client_email" class="form-control client_email">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success send_contract_for_clients" data-url="{{route('manager.contracts.send_to_client',$info->contract_id)}}" >Отправить</button>
                                    <button type="button" onclick="$('#modalSendContractToClient').modal('hide')" class="btn btn-outline-secondary">Отмена</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset
            @else
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input status" type="checkbox"
                               @if(isset($info->contract_status) and !$info->contract_status) @else checked
                               @endif  name="contract_status"
                               id="contract_status" value="1">
                        <label class="form-check-label" for="contract_status">
                            Договор активен
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>

            @endisset
        </form>
    </div>
@endsection
