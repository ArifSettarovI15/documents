@extends('BaseData.Views.managerlayout')
@isset($info)
    @section('title', $info->client_name)
@else
    @section('title', 'Новый клиент')
@endisset
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')

    <div class="container mt-5">
        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if(isset($info) and $info->client_type != 'ip') disabled @else active @endif" id="home-tab" data-toggle="tab" href="#ip" role="tab" aria-controls="home" aria-selected="true">Индивидуальный предприниматель</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(isset($info) and $info->client_type != 'ooo') disabled @else @isset($info) active @endisset @endif" id="profile-tab" data-toggle="tab" href="#ooo" role="tab" aria-controls="profile" aria-selected="false">Общество с огран. ответ-тью</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(isset($info) and $info->client_type != 'fiz') disabled @else @isset($info) active @endisset @endif" id="contact-tab" data-toggle="tab" href="#fiz" role="tab" aria-controls="contact" aria-selected="false">Физическое лицо</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade @if(isset($info) and $info->client_type == 'ip') show active @else @isset($info)@else show active @endisset @endif" id="ip" role="tabpanel" aria-labelledby="home-tab">
                @include('Clients.Views.components.tab_ip')
            </div>
            <div class="tab-pane fade @if(isset($info) and $info->client_type == 'ooo') show active @endif" id="ooo" role="tabpanel" aria-labelledby="nav-profile-tab">
                @include('Clients.Views.components.tab_ooo')
            </div>
            <div class="tab-pane fade @if(isset($info) and $info->client_type == 'fiz') show active @endif" id="fiz" role="tabpanel" aria-labelledby="nav-contact-tab">
                @include('Clients.Views.components.tab_fiz')
            </div>
        </div>
    </div>
@endsection
