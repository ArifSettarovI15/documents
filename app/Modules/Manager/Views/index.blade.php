@extends('BaseData.Views.managerlayout')
@section('title', 'Панель управления')

@section('content')
    <div class="container-fluid ">
        <div class="row mt-5 services_status_bars">
            <input type="hidden" data-url="{{route('manager.get_smtp_status')}}" class="get_smtp_status" data-current="wait">
            <input type="hidden" data-url="{{route('manager.get_cloudconvert_status')}}" class="get_cloudconvert_status" data-current="wait">
            <div style="display: none" class="card smtp_status_true card-status bg-success text-white col-md-3 ml-1 mr-1" >
                <div class="card-title pt-4 pl-4 pb-1">
                    <h5>Почтовый сервер</h5>
                </div>
                <div class="card-body align-middle text-center">
                    <i class="far fa-check-circle mb-3" style="font-size: 96px"></i>
                    <h1 class="mt-3 mb-1">
                        РАБОТАЕТ
                    </h1>
                </div>
                <div class="card-footer mt-5 d-flex align-items-center justify-content-between">
                    <span class="mt-4">smtp.tigerweb.ru</span>
                </div>
            </div>

            <div style="display: none" class="card smtp_status_false card-status bg-danger text-white col-md-3 ml-1 mr-1" >
                <div class="card-title pt-4 pl-4 pb-1">
                    <h5>Почтовый сервер</h5>
                </div>
                <div class="card-body align-middle text-center">
                    <i class="far fa-times-circle mb-3" style="font-size: 96px"></i>
                    <h2 class="mt-3">
                        НЕ ДОСТУПЕН
                    </h2>
                    <div class="text text-left ml-2 p-1" style="background: rgba(116,26,26,0.3); border-radius: 5px">
                        <span class="">Сервер перестал отвечать на запросы. Повторная попытка соединения.</span>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>smtp.tigerweb.ru</span>
                </div>
            </div>

            <div style="display: none" class="card converter_status_true card-status bg-success text-white col-md-3 ml-1 mr-1"  >
                <div class="card-title pt-4 pl-4 pb-1">
                    <h5>Сервер конвертации</h5>
                </div>
                <div class="card-body align-middle text-center">
                    <i class="far fa-check-circle mb-3" style="font-size: 96px"></i>
                    <h1 class="mt-3">
                        ДОСТУПЕН
                    </h1>


                    <div class="progress_wrapper mt-4">
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%;"></div>
                        </div>
                        <span style="font-size: 18px">Лимит на сегодня: <b>25</b></span>
                    </div>

                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="https://cloudconvert.com"  style=" color: #fcfcfc; text-decoration: underline">cloudconvert.com</a>
                </div>
            </div>

            <div style="display: none" class=" card converter_status_warning card-status bg-warning-darken text-white col-md-3 ml-1 mr-1"  >
                <div class="card-title pt-4 pl-4 pb-1">
                    <h5>Сервер конвертации</h5>
                </div>
                <div class="card-body align-middle text-center">
                    <i class="fas fa-exclamation-triangle mb-3" style="font-size: 96px"></i>
                    <h2 class="mt-3">
                        ЛИМИТ ИСЧЕРПАН
                    </h2>


                    <div class="progress_wrapper mt-3">
                        <div class="progress mb-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width:1.5%;"></div>
                        </div>
                        <span class="">Лимит на сегодня: <b>0</b></span>
                    </div>

                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="https://cloudconvert.com"  style=" color: #fcfcfc; text-decoration: underline">cloudconvert.com</a>
                </div>
            </div>

            <div style="display: none" class="card converter_status_false card-status bg-danger text-white col-md-3 ml-1 mr-1" >
                <div class="card-title pt-4 pl-4 pb-1">
                    <h5>Cервер конвертации</h5>
                </div>
                <div class="card-body align-middle text-center">
                    <i class="far fa-times-circle mb-3" style="font-size: 96px"></i>
                    <h2 class="mt-1">
                        НЕ ДОСТУПЕН
                    </h2>
                    <div class="text text-left ml-2 p-1" style="background: rgba(116,26,26,0.3); border-radius: 5px">
                        <span class="">Сервер перестал отвечать на запросы. Повторная попытка соединения.</span>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>smtp.tigerweb.ru</span>
                </div>
            </div>

            <div class="card smtp_status_wait card-status bg-secondary text-white col-md-3 ml-1 mr-1" >
                <div class="card-title pt-4 pl-4 pb-1">
                    <h5>Почтовый сервер</h5>
                </div>
                <div class="card-body align-middle text-center">
                    <div style="">
                        <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
                    <h2 class="mt-4">
                        Получение информации
                    </h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>smtp.tigerweb.ru</span>
                </div>
            </div>

            <div class="card converter_status_wait card-status bg-secondary text-white col-md-3 ml-1 mr-1" >
                <div class="card-title pt-4 pl-4 pb-1">
                    <h5>Сервер конвертации</h5>
                </div>
                <div class="card-body align-middle text-center">
                    <div style="">
                        <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
                    <h2 class="mt-4">
                        Получение информации
                    </h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="https://cloudconvert.com"  style=" color: #dee2e6;font-size: 18px">cloudconvert.com</a>
                </div>
            </div>
            <div class="col-md-3">

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-outline-success mt-2"
                                data-toggle="modal" data-target="#custom_invoice_modal"
                                style="width: 250px">Создать индивидуальный счет</button>
                        @include('Manager.Views.components.create_custom_invoice_modal')
                    </div>
                </div>
            </div>

        </div>
        <div class="log-table mt-5">
            <div class="table-data">
                @include('Manager.Views.logs_table')
            </div>
            <div class="row mt-2">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="paging-data float-right mr-5" >
                        {{$actions->links('paging')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
