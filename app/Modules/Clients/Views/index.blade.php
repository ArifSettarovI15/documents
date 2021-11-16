@extends('BaseData.Views.managerlayout')
@section('title', 'Список клиентов')

@include('components.bread-crumbs', with($breadcrumbs))

@section('content')
    <div class="container-fluid" >

        <a href="{{route('manager.clients.create')}}" class="btn btn-success float-right m-5" >Добавить клиента</a>

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr>
                <th scope="col">ID клиента</th>
                <th scope="col">Статус</th>
                <th scope="col">Название</th>
                <th scope="col">Вид отправки</th>
                <th scope="col"></th>
            </tr>
            <tr>
                <th scope="col"><input type="text" class="table-filter" data-type="search" name="client_id"></th>

                <th scope="col"><select class="table-filter" data-type="filter" name="client_status">
                        <option value="">Все</option>
                        <option value="0">Отключенные</option>
                        <option value="1">Активные</option>
                    </select>
                </th>
                <th scope="col"><input type="text" class="table-filter" data-type="search" name="client_name"></th>

                <th scope="col"></th>
            </tr>
            </thead>
            <tbody class="table-data">
                @include('Clients.Views.components.clients_table')
            </tbody>
        </table>
        <div class="paging-data mt-5">
            {{$clients->links('paging')}}
        </div>
    </div>

@endsection
