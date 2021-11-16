<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>СчетаTiger - @yield('title')</title>
{{--    <link href="{{asset('public/css/app.css')}}" rel="stylesheet"/>--}}
    <link href="{{asset('css/app.css')}}" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"
            crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('manager.index')}}">Счета</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i>
    </button>
    <!-- Navbar Search-->
{{--    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">--}}

{{--    </form>--}}
    <!-- Navbar-->
    <div class="breadcrumbs_inline ml-5" style="min-width: 500px; height: 50px; color:white; display: flex; line-height: 50px" >
        @yield('breadcrumbs')
    </div>

    <ul class="d-none d-md-inline-block navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{route('user.logout')}}">Выйти</a>
            </div>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Данные</div>
                    <a class="nav-link" href="{{route('manager.index')}}"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                        Главная
                    </a>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayoutsSuppliers"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Поставщики
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayoutsSuppliers">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{route('manager.companies.create')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                                Новый поставщик
                            </a>
                            <a class="nav-link" href="{{route('manager.companies.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                Все поставщики
                            </a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-friends"></i></div>
                        Клиенты
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{route('manager.clients.create')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                                Новый клиент
                            </a>
                            <a class="nav-link" href="{{route('manager.clients.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-friends"></i></div>
                                Список клиентов
                            </a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayoutsServices"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-check-square"></i></div>
                        Услуги
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayoutsServices">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{route('manager.services.create')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-plus-square"></i></div>
                                Новая услуга
                            </a>
                            <a class="nav-link" href="{{route('manager.services.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-check-square"></i></div>
                                Список услуг
                            </a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayoutsContracts"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-contract"></i></div>
                        Договора
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayoutsContracts">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{route('manager.contract_types.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-pencil-ruler"></i></div>
                                Шаблоны
                            </a>
                            <a class="nav-link" href="{{route('manager.contracts.create')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-file-signature"></i></div>
                                Новый договор
                            </a>
                            <a class="nav-link" href="{{route('manager.contracts.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-file-contract"></i></div>
                                Список договоров
                            </a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayoutsPlans"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-expand-arrows-alt"></i></div>
                        Планы
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayoutsPlans">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{route('manager.plans.create')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-expand-alt"></i></div>
                                Новый план
                            </a>
                            <a class="nav-link" href="{{route('manager.plans.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-expand-arrows-alt"></i></div>
                                Список планов
                            </a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayoutsInvoices"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-invoice"></i></div>
                        Счета
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayoutsInvoices">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{route('manager.invoices.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                Список счетов
                            </a>
                            <a class="nav-link" href="{{route('manager.invoices.not_payed')}}">
                                <div class="sb-nav-link-icon"><i class="fa fa-money-bill-alt"></i></div>
                                Неоплаченные
                            </a>
                            <a class="nav-link" href="{{route('manager.invoices.history')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                                История
                            </a>
                        </nav>
                    </div>
                    <a class="nav-link" href="{{route('manager.users.index')}}"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Пользователи
                    </a>
                    <a class="nav-link" href="{{route('manager.log_actions')}}"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                        События
                    </a>
                </div>
            </div>

        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            @yield("logs")
            @yield("content")
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">TigerWeb &copy; 2021</div>
                </div>
            </div>
        </footer>
    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 360px">
            <div class="modal-header" style="padding: 15px">
                <h4 class="modal-title float-left" id="myModalLabel">Подтвердите удаление</h4>
                <button type="button" class="close left" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <span>
                    Запись будет удалена без возможности восстановления!
                </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="modal-btn-si">Подтвердить</button>
                <button type="button" class="btn btn-outline-dark" id="modal-btn-no">Отмена</button>
            </div>
        </div>
    </div>
</div>
<div class="ajax-loader" style="visibility: hidden">
    <div class="content">
        <div class="lds-spinner">
            <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>

        </div>
        <div class="message">

        </div>
    </div>

</div>
{{--<script src="{{asset('public/js/app.js')}}"></script>--}}
<script src="{{asset('js/app.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<link rel="stylesheet" href="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006288/BBBootstrap/choices.min.css?version=7.0.0">
<script src="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006273/BBBootstrap/choices.min.js?version=7.0.0"></script>


</body>
</html>
