@extends('BaseData.Views.managerlayout')
@isset($info)
    @section('title', 'Тип: '.$info->ct_name)
@else
    @section('title', 'Новый шаблон договора')
@endisset
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')

    <div class="container">
        <form class="mt-lg-5 h-100"
              @isset($info->ct_id)
                action="{{route('manager.contract_types.update', $info->ct_id)}}">
            @else
                action="{{route('manager.contract_types.store')}}">
            @endisset

            <fieldset class="mb-5">
                @isset($info->ct_id)
                    <legend>Редактирование - {{$info->ct_name}}</legend>
                @else
                    <legend>Создание нового шаблона договора</legend>
                @endisset
            </fieldset>

            <div class="form-group">
                <label for="ct_name" class="required">Название шаблона</label>
                <input type="text" name="ct_name" class="form-control" id="ct_name"
                       placeholder="Договор по контекстной рекламе" required
                       value="@isset($info->ct_name){{$info->ct_name}}@endisset">
            </div>
            <div class="form-group">
                <label for="ct_litera" class="required">Литера</label>
                <input type="text" name="ct_litera" class="form-control" id="ct_litera" maxlength="5"
                       placeholder="КР"
                       value="@isset($info->ct_litera){{$info->ct_litera}}@endisset">
            </div>
            <div class="form-group">
                <label for="ct_start" class="required">Начало отсчета</label>
                <input type="number" name="ct_start" class="form-control" id="ct_start"
                       placeholder="100"
                       value="@isset($info->ct_start){{$info->ct_start}}@endisset">
            </div>
            <div class="form-group">
                <label for="">Файл шаблона договора @isset($info->ct_template)
                        <a href="{{$info->ct_template_file_url}}">{{$info->ct_template_filename}}</a>
                    @endisset</label>
                <div class="custom-file">
                    <input type="file" name="ct_template" class="file-input" id="inputFile"
                           data-url="{{route('files.add_file')}}" placeholder="Выбрать">
                    <label class="custom-file-label" for="inputFile">@isset($info->ct_template) {{$info->ct_template_filename}} @else Выберите файл@endisset</label>
                </div>
                <input type="hidden" name="ct_template" @isset($info->ct_template) value="{{$info->ct_template}}" @endisset>
            </div>
            <div class="form-group">
                <select id="multiple_service" placeholder="Выберите услуги для типа" multiple
                        name="ct_services[]">
                    @foreach($services as $service)
                        <option value="{{$service->service_id}}" @if(isset($selected_services) and in_array($service->service_id,$selected_services)) selected @endif>{{$service->service_title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row mt-5">
                <div class="form-group col-md-12">
                    <h3>Дополнительные поля</h3>
                </div>
                <table class="table">
                    <thead>
                    <tr class="text-center">
                        <th ><label for="position">Ключ</label></th>
                        <th ><label for="name">Название</label></th>
                        <th ><label for="email">Подсказка</label></th>
                    </tr>
                    </thead>
                    <tbody class="emails_table">
                    @isset($info->ct_dop_keys)
                        @foreach($info->ct_dop_keys as $key=>$ct_dop_key)
                            <tr>
                                <td><input value="{{$ct_dop_key}}" class="form-control required" type="text" id='position'  name="ct_dop_keys[]" ></td>
                                <td><input @isset($info->ct_dop_names[$key]) value="{{$info->ct_dop_names[$key]}}" @endisset class="form-control required" type="text" id='name' name="ct_dop_names[]" ></td>
                                <td><input @isset($info->ct_dop_tooltips[$key]) value="{{$info->ct_dop_tooltips[$key]}}" @endisset class="form-control required" type="text" id='email' name="ct_dop_tooltips[]" ></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td><input value="" class="form-control required" type="text" id='position'  name="ct_dop_keys[]" ></td>
                            <td><input value="" class="form-control required" type="text" id='name' name="ct_dop_names[]" ></td>
                            <td><input value="" class="form-control required" type="text" id='email' name="ct_dop_tooltips[]" ></td>
                        </tr>
                    @endisset
                    </tbody>
                </table>
                <div class="text-center m-auto">
                    <button type="button" data-target="emails_table" class="btn btn-outline-dark tooltip_wrapper emails_add_row" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Добавить строку</button>
                </div>
            </div>





            @isset($info->ct_id)
                <button type="submit" class="btn btn-primary mt-2 mb-4">Сохранить</button>
            @else
                <div class="form-group mt-2 mb-4">
                    <div class="form-check">
                        <input class="form-check-input status" type="checkbox"
                               @if(isset($info->ct_status) and !$info->ct_status) @else checked
                               @endif  name="ct_status"
                               id="ct_status" value="1">
                        <label class="form-check-label" for="ct_status">
                            Тип договора активен
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
            @endisset
        </form>
    </div>
@endsection
