@extends('BaseData.Views.managerlayout')
@section('title', 'Просмотр счета')
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container mt-5">
        <a href="{{$invoice->invoice_file_url}}">{{$invoice->invoice_name}}</a>
    </div>
@endsection
