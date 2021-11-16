<div class="modal fade show"  id="custom_invoice_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Создание индивидуального счета</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="custom_invoice_create_form" action="{{route('manager.invoices.create_custom_invoice')}}">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="invoice_supplies">Выберите поставщика услуг из списка:</label>
                            <select name="invoice_supplies" required class="form-control" id="invoice_supplies">
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->company_id}}">{{$supplier->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="invoice_contract">Основание счета (договор):</label>
                            <input type="text" class="form-control" data-default="" required name="invoice_contract" id="invoice_contract">
                        </div>
                    </div>
                    <h4 class="mt-md-4">Данные заказчика:</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="client_name">Название организации (или ФИО):</label>
                            <input class="form-control" data-default="" required type="text" name="client_name" id="client_name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="client_phone" >Телефон:</label>
                            <input class="form-control" data-default="" required type="text" name="client_phone" id="client_phone">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="client_address">Адрес:</label>
                            <input class="form-control" data-default="" required type="text" name="client_address" id="client_address">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="client_inn" >ИНН (или паспортные данные):</label>
                            <input class="form-control" data-default="" required type="text" name="client_inn" id="client_inn">
                        </div>
                    </div>
                    <hr>
                    <h4 class="mt-md-4">Услуги:</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="service_names">Название</label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="service_prices">Стоимость</label>
                        </div>

                    </div>
                    <div class="form-row services_data_wrapper">
                        <div class="form-group col-md-6">
                            <select name="service_names[]" required class="form-control service_names_selector" id="service_names">
                                @foreach($services as $service)
                                    <option value="{{$service->service_writen}}">{{$service->service_writen}}</option>
                                @endforeach
                                    <option value="services_select_to_input">ввести свою...</option>
                            </select>
                            <div style="display: none" class="service_names_selector_wrapper">
                                <input type="text" data-default="" required disabled class="form-control service_names_selector" id="service_names" name="service_names[]">
                                <span class="icon_down service_names_selector_icon" ><i class="fa fa-angle-down"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-md-5">
                            <input type="number" required data-default="" class="form-control service_prices" id="service_prices" name="service_prices[]">
                        </div>
                        <div class="form-group col-md-1">
                            <button type="button" class="btn btn-outline-danger button_delete_services" >X</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-outline-secondary clone_services_data_wrapper d-flex m-auto r-0 l-0" data-target="services_data_wrapper">Добавить строку</button>
                    </div>

                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="invoice_date">Дата</label>
                            <input class="form-control datepicker" data-default="{{date('d.m.Y', time())}}" required value="{{date('d.m.Y', time())}}" type="text" id="invoice_date" name="invoice_date" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="payment_type">Тип оплаты</label>
                            <select class="form-control" required name="payment_type" id="payment_type">
                                <option value="predoplata">Предоплата</option>
                                <option value="postoplata">Постоплата</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-doggle="modal" data-target="#create_custom_invoice_and_send" class="btn btn-outline-success create_custom_invoice_and_send">Отправить по email</button>
                <button type="submit" data-target=".custom_invoice_create_form" class="btn btn-outline-success custom_invoice_create_button">Получить счет</button>
                <button class="btn btn-outline-secondary custom_invoice_create_cancel close_modal">Отмена</button>
            </div>
        </div>
        <div class="modal fade" id="create_custom_invoice_and_send" tabindex="22" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Укажите данные</h5>
                        <button type="button" class="close close_modal"  aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="send_email">Email:</label>
                            <input type="email" name="send_email" id="send_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="send_time"></label>
                            <select name="send_time" data-default="" required class="form-control dont_check_empty service_names_selector" id="send_time">
                                <option value="now">Сейчас</option>
                                <option value="services_select_to_input">веберите время...</option>
                            </select>
                            <div style="display: none" class="service_names_selector_wrapper">
                                <input type="time"  required disabled class="form-control dont_check_empty service_names_selector" id="send_time" name="send_time">
                                <span class="icon_down service_names_selector_icon" ><i class="fa fa-angle-down"></i></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success create_custom_invoice_and_send_button" data-target=".custom_invoice_create_form">Отправить</button>
                        <button type="button" class="btn btn-outline-secondary close_modal">Отмена</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
