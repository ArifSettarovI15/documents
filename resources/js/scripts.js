(function ($) {
    "use strict";
    var path = window.location.href;
    $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function () {
        if (this.href === path) {
            $(this).addClass("active");
        }
    });

    // Toggle the side navigation
    $("#sidebarToggle").on("click", function (e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });
})(jQuery);

$(document).on('click', 'a.page-link', function (event){
    event.preventDefault()
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: JSON.stringify({'page':$(this).attr('data-page')}),
        success: function (response) {
            if (response.html) {
                $('.table-data').html(response.html)
            }
            if (response.paging) {
                $('.paging-data').html(response.paging)
            }
        }
    })
})

$(document).on('click', '.delete_email_row', function (event){
    event.preventDefault()
    $(this).closest('tr').remove()
})

$('.datepicker').datepicker({
    format: 'dd.mm.yyyy',
    autoclose: true,
    language: 'ru'
})

$(document).on('submit', 'form', function (event) {
    event.preventDefault();
})
$(document).on('input','input:text', function (){
    $(this).attr('value', $(this).val())
})
$(document).on('input','input[type=email]', function (){
    $(this).attr('value', $(this).val())
})
$(document).on('submit', 'form', function () {
    var url = $(this).attr('action');
    var send = true
    var modal = $(this).find('button[data-toggle="modal"]').attr('data-target')

    if ($(this).find('.emails_table').length)
    {
        var inputs = $(this).find('.emails_table').find('input.required[value=""]')
        if (inputs.length){

            send = false
            inputs.addClass('is-invalid')
            $(modal).modal({'show': true})
        }

        var emails = $(this).find('.emails_table')
        var tr = $(emails[0]).find('.email_invoices:checked').closest('tr')
        $('input[name=client_email]').val($(tr).find('#email').val())
    }
    if (send)
        $.ajax({
            type: "POST",
            url: url,
            data: $(this).serialize(),
            success: function (response) {
                if (response.redirect)
                {
                    window.location.replace(response.redirect)
                }
                if (response.status)
                {
                    $(document).find('*[data-default]').each(function (){
                        $(this).val($(this).attr('data-default'))
                        $(this).removeClass('is-valid is-invalid')})
                }
                if (response.reload)
                {
                    location.reload();
                }
                show_message(response)
            }
        });
})
$(document).on('change', '.js_status_change', function () {
    var options = {}
    options['field'] = $(this).attr('data-field')
    options['id'] = $(this).attr('data-id')
    options['value'] = parseInt($(this).attr('data-current'))
    var input = $(this)
    if (options['value'] === 1)
        options['value'] = 0
    else
        options['value'] = 1
    var url = $(this).attr('data-url')
    $.ajax({
        type: "POST",
        url: url,
        data: options,
        success: function (response) {
            $(input).attr('data-current',options['value'])
            show_message(response)

        }
    });
})

$(document).ready(function () {
    check_rows()
    if (typeof ($('.contract_number').html()) != 'undefined' && $('.contract_number').val() == '') {
        create_number()
    }
    var select = $('#multiple_service').html()
    if (typeof (select) != 'undefined') {
        var multipleCancelButton = new Choices('#multiple_service', {
            removeItemButton: true,
            searchResultLimit: 10
        });
    }

})

function check_rows() {
    var services = $('.contract_services').attr('data-services')
    var rows = $('.contract_services').find('.form-row').length
    if (rows >= services) {
        $('.add_contract_service_button_wrap').css({'display': 'none'})
    } else {
        $('.add_contract_service_button_wrap').css({'display': 'block'})
    }

}

$('.add_contract_service_button').on('click', function () {
    var rows = $('.contract_services').find('.form-row').last()

    var html = rows.html()
    html = "<div class='form-row'>" + html + "</div>"
    $('.contract_services').append(html)
    check_rows()
})


$(document).ready(function (){
    var link_active = $('#layoutSidenav').find("a.nav-link.active")
    var collapse =$(link_active).closest('div.collapse').attr('id')
    $(link_active).closest('div.collapse').addClass('show')
    $('a[data-target="#'+collapse+'"]').removeClass('collapsed')
})

$('.contract_services').on('click', 'a', function (event) {
    event.preventDefault()
    var parent = $(this).closest('.form-row')
    var data = {}
    var url = $(this).attr('href')
    data['cs_contract_id'] = $('.contract_services').attr('data-contract')
    data['cs_service_id'] = $(parent).find('.contract_service').val()
    data['cs_price'] = $(parent).find('.contract_service_price').val()
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function (response) {
            $.toast({
                text: response,
                allowToastClose: false,
                width: 500,
                height: 100,
                hideAfter: 5000,
                stack: 3,
                position: 'top-right',
                bgColor: '#32a852',
                textColor: '#fff'
            })
        }
    });
})
var item;
$(document).on('click', '.js_ajax_delete', function (event) {
    event.preventDefault();
    item = this
    $("#mi-modal").modal('show');
    $("#modal-btn-no").on("click", function(){
        $("#mi-modal").modal('hide');
    });
})

$(document).on("click", '#modal-btn-si', function(){
    $("#mi-modal").modal('hide');
    $.ajax({
        type: "POST",
        url: $(item).attr('href'),
        success: function (response) {
            if (response.message) {
                show_message(response.message)
            }
            else {
                show_message(response)
            }
            if (response.status === true || typeof(response.status) === 'undefined')
                $(item).closest('tr').remove()
            }
    });
});
$(document).on('change', '#client_bank', function () {
    var value = $(this).val();
    var input = '<input type="text" class="form-control" id="client_bank" name="client_bank">'
    if (value === "another") {
        var item = $(this).closest('div')
        $(this).css({'display': 'none'})
        $(item).append(input)
    }
})

$(document).on('change', '.contract_type', function () {
    create_number()
})

$(document).on('change', '.contract_type', function () {
    var data = new FormData();
    data.append('type_id', $(this).val())
    data.append('contract_id', $('.contract_id').val())
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
            $('.services_section').html(response.html)
            $('.dop_section').html(response.html2)
        }
    })
})

function create_number() {
    if (typeof ($('.contract_type').html()) != 'undefined') {
        var litera = $('.contract_type').find(":selected").attr('data-litera')
        var number = $('.contract_type').find(":selected").attr('data-number')
        $('.contract_number').val(litera + '-' + number)
    }
}

$(".file-input").on("change", function () {
    var data = new FormData();
    var fileName = $(this).val().split("\\").pop();
    var files = $(this)[0].files;
    var url = $(this).attr('data-url')
    data.append('file', files[0]);
    data.append('name', $(this).attr('name'));
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.file_id) {
                $('[name=' + response.name + ']').attr('value', response.file_id)
                show_message(response)
            } else {
                show_message(response)
            }

        }
    })

    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

function show_message(response) {
    if (response.status === true) {
        $.toast({
            text: response.message || 'Результат выполнен успешно',
            allowToastClose: false,
            width: 500,
            height: 100,
            hideAfter: 5000,
            stack: 3,
            position: 'top-right',
            bgColor: '#32a852',
            textColor: '#fff'
        })
    } else if (response.status === false) {
        $.toast({
            text: response.message || 'Ошибка выполнения запроса',
            allowToastClose: false,
            width: 500,
            height: 100,
            hideAfter: 5000,
            stack: 3,
            position: 'top-right',
            bgColor: '#fc5203',
            textColor: '#fff'
        })
    } else if (typeof (response.status) == 'undefined') {
        $.toast({
            text: response.message || response || 'Результат запроса неизвестен',
            allowToastClose: false,
            width: 500,
            height: 100,
            hideAfter: 5000,
            stack: 3,
            position: 'top-right',
            bgColor: '#d6a51d',
            textColor: '#fff'
        })
    }
}

function randString(id){
    var dataSet = $(id).attr('data-character-set').split(',');
    var possible = '';
    if($.inArray('a-z', dataSet) >= 0){
        possible += 'abcdefghijklmnopqrstuvwxyz';
    }
    if($.inArray('A-Z', dataSet) >= 0){
        possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    if($.inArray('0-9', dataSet) >= 0){
        possible += '0123456789';
    }
    if($.inArray('#', dataSet) >= 0){
        possible += '![]{}()%&*$#^<>~@|';
    }
    var text = '';
    for(var i=0; i < $(id).attr('data-size'); i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}


$('input[rel="gp"]').each(function(){
    if (!$(this).val().length) {
        $(this).val(randString($(this)));
        $(this).attr('type','text');
    }
});
$("input:required").each(function (){
    var label = $(this).closest('form').find('label[for="'+$(this).attr('id')+'"]')
    $(label).append("<span style='color:red; font-size: 16px; font-weight: 700'> *</span>")
})
// Create a new password
$(".getNewPass").click(function(){
    var field = $(this).closest('div').find('input[rel="gp"]');
    field.val(randString(field));
    field.attr('type','text');
});

$(document).on('click', '.emails_add_row', function ()
{
    var target = $(this).attr('data-target')
    var child = $('.'+target).children().last().clone()
    $(child).find('input').each(function (){
        $(this).val('')
        $(this).attr('value','')
    })
    $('.'+target).append(child)
})

$(document).ready(function ()
{
    if ($('div').hasClass('services_status_bars')) {
        $('.smtp_status_true').css({'display': 'none'})
        $('.smtp_status_false').css({'display': 'none'})
        $('.converter_status_true').css({'display': 'none'})
        $('.converter_status_warning').css({'display': 'none'})
        $('.converter_status_false').css({'display': 'none'})

        var smtp_check_url = $('.get_smtp_status').attr('data-url')
        var cloudconvert_check_url = $('.get_cloudconvert_status').attr('data-url')
        check_service(smtp_check_url)
        check_service(cloudconvert_check_url)
        setInterval(function () {
            check_service(smtp_check_url)
            check_service(cloudconvert_check_url)
        },5000);
    }
})

function check_service(url)
{
    $.ajax({
        url: url,
        type: 'post',
        contentType: false,
        processData: false,
        success: function (response) {
            var current = $('input[data-url="'+url+'"]').attr('data-current')

            if (current !== response.status.toString())
            {
                $('.'+response.target+current).css({'display': 'none'})

                $('.'+response.target+response.status.toString()).css({'display': 'block'})

                $('input[data-url="'+url+'"]').attr('data-current',response.status.toString())
            }
            if (typeof(response.credits)!=="undefined" && response.credits !== null)
            {
                $('.progress_wrapper').find('b').html(response.credits)

                var percents = parseInt(response.credits)*100/25

                if (percents > 50)
                {
                    $('.progress_wrapper').find('.progress-bar').removeClass('bg-warning')
                    $('.progress_wrapper').find('.progress-bar').removeClass('bg-danger')
                }
                else if (20 < percents && percents  < 50)
                {
                    $('.progress_wrapper').find('.progress-bar').addClass('bg-warning')
                    $('.progress_wrapper').find('.progress-bar').removeClass('bg-danger')
                }
                else if(percents < 20)
                {
                    $('.progress_wrapper').find('.progress-bar').removeClass('bg-warning')
                    $('.progress_wrapper').find('.progress-bar').addClass('bg-danger')

                }
                if (percents===0){percents=1.5}
                $('.progress_wrapper').find('.progress-bar').css({'width':percents+'%'})

            }
        }
    })
}
$('.contract_to_doc').on('click',function()
{
    var url = $(this).attr('data-url')
    $.ajax({
        url: url,
        type: 'post',
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('.ajax-loader').css({'visibility':'visible'});
            $('.ajax-loader').find('.message').html('Конвертируем файл! <br> Это может занять какое-то время!')
        },
        success: function (response) {
            var clone = $('a.contract_html_download_url').clone()
            clone.attr('href', response.url)
            clone.html(response.name)
            clone.removeClass('contract_html_download_url')
            clone.addClass('contract_doc_download_url')
            $('a.contract_html_download_url').after(clone)
            $('a.contract_html_download_url').after('<br>')
            $('.ajax-loader').css({'visibility':'hidden'});
            $('.ajax-loader').find('.message').html('')
        }
    })
});


$(document).on('click', '.send_contract_for_clients', function (){
    var url = $(this).attr('data-url')
    var arr = $('.client_email').val()
    if($('input.client_email').val())
    {
        arr.push($('input.client_email').val())
    }
    var data = {'email_ids':  arr}

    $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        success: function (response){
            show_message(response)
            $('#modalSendContractToClient').modal('hide')
        }})
})

function filter_main_func(elem)
{
    var data = {}
    var filters = $(document).find('.table-filter')

    filters.each(function (){
        if ($(this).val() !=='')
        {
            data[$(this).attr('name')] = {'value':$(this).val(), 'type':$(this).attr('data-type')}
        }
    })
    debouncer(data)
}

var debouncer = debounce((data)=> {send_filter(data)},100)

$('input.table-filter').on('input', function (){
    filter_main_func(this)
})
$('input.table-date_picker').on('change', function (){
    filter_main_func(this)
})
$('select.table-filter').on('change', function (){
    filter_main_func(this)
})

function debounce(func, timeout = 300){
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };

}
function send_filter(data)
{
    $.ajax({
        type: 'POST',
        data: JSON.stringify(data),
        success: function (response){
            $('.table-data').html(response.html)
            $('.paging-data').html(response.paging)
        }})
}


$(document).on('click', '.get_invoice_for_client', function(){
    var url = $(this).attr('data-url')
    var contract = $(this).attr('data-contract')
    if (typeof(contract) === 'undefined'){
        $('#get_invoice_for_client_modal').modal('show')
    }else{
        ajax_contract_invoice(url, contract)
        show_message({'status':true, 'message': 'Формируем файл счета, ожидайте'})
    }

})

$('.button_success_get_invoice').click(function (){

    button_success_get_invoice_cliecked()
})

function button_success_get_invoice_cliecked (){

    var contract = $('#get_invoice_for_client_modal').find('select').val()

    ajax_contract_invoice($('.get_invoice_for_client').attr('data-url'), contract)
    $('#get_invoice_for_client_modal').modal('hide')
    show_message({'status':true, 'message': 'Формируем файл счета, ожидайте'})
}
function ajax_contract_invoice(url, contract)
{
    $.ajax(
        url,
        {
            type: 'POST',
            data: {'contract':contract},
            success: function (response)
            {
                if (response.file)
                {
                    window.location.href = response.file;
                }
            }
        }
    )
}
$(document).on('change', '.service_names_selector', function ()
{
    var option = $(this).find('option:selected').val()
    if (option === 'services_select_to_input')
    {
        $(this).hide()
        var wrapper = $(this).closest('.form-group')

        $(wrapper).find('.service_names_selector_wrapper').show()
        $(wrapper).find('input.service_names_selector').attr('disabled', false).focus()
    }
})
$(document).on('click', '.service_names_selector_icon', function (){
    $(this).closest('div').hide()
    $(this).closest('div').find('input').attr('disabled',true)
    $(this).closest('.form-group').find('select.service_names_selector').show().find('option').first().attr('selected', true)

})

$(document).on('click', '.clone_services_data_wrapper', function ()
{
    var target = '.'+$(this).attr('data-target')
    var fields = $(document).find(target).last()
    var cloned = fields.clone()
    $(cloned).find('input').val('')
    $(cloned).find('input').removeClass('is-valid')
    $(cloned).find('select').find('option').first().attr('selected', true)
    fields.after(cloned)
    $('.button_delete_services').css({'display':'block'})
})

$(document).on('click', '.custom_invoice_create_button', function ()
{
    send_custom_invoice()

})
function send_custom_invoice()
{
    var send_ajax = true
    var fields = $($('.custom_invoice_create_button').attr('data-target')).find(':required')
    console.log(fields)
    fields.each(function ()
    {
        if($(this).val() === '' && $(this).prop('disabled') === false)
        {
            if (! $(this).hasClass('dont_check_empty')) {
                console.log(this)
                send_ajax = false
            }
            $(this).addClass('is-invalid')
            $(this).removeClass('is-valid')
        }
        else
        {
            $(this).removeClass('is-invalid')
            $(this).addClass('is-valid')
        }
    })
    console.log(send_ajax)
    if (send_ajax)
    {
        $($('.custom_invoice_create_button').attr('data-target')).submit()
    }
}

$(document).on('change', 'input,select', function ()
{

    if ($(this).hasClass('is-invalid'))
    {

        if ($(this).val() !== '')
        {

            $(this).removeClass('is-invalid')
            $(this).addClass('is-valid')
        }
    }
    if ($(this).hasClass('is-valid'))
    {

        if ($(this).val() === '')
        {

            $(this).addClass('is-invalid')
            $(this).removeClass('is-valid')
        }
    }
})
$(document).on('click', '.button_delete_services', function (){
    console.log(this)
    if ($(this).closest('form').find('.services_data_wrapper').length > 1)
        $(this).closest('.services_data_wrapper').remove()
        if ($(this).closest('form').find('.services_data_wrapper').length > 1){
            $('.button_delete_services').css({'display':'none'})
        }
    else
    {
        $(this).css({'display':'none'})
    }
})

$('.custom_invoice_create_cancel').on('click', function ()
{
    $('#custom_invoice_modal').modal('hide')
})

$(document).on('click', '.create_custom_invoice_and_send', function ()
{
    $($(this).attr('data-target')).modal({'show':true, 'backdrop':false})

})

$(document).on('click', '.close_modal', function (){
    $(this).closest('.modal').modal('hide')

})
$(document).on('click','.create_custom_invoice_and_send_button', function (){
    var sec_modal = $(this).closest('.modal');
    var target = $(this).attr('data-target')
    sec_modal.find('input,select').each(function (){
        if ($(this).val() && !$(this).disabled)
        {
            $(target).append($(this).clone().css({'display': 'none'}))

        }
    })
    send_custom_invoice()
})

$($('.create_custom_invoice_and_send_button').closest('.modal')).on('hidden.bs.modal', function (){
    $('body').addClass('modal-open')
    console.log($('body'))
})
$(document).on('click', '.redo_send_invoice', function (){
    $.ajax( $(this).attr('data-url'),
        {
            type: 'POST',
            success: function (response){
                show_message(response)
            }
        }
    )
})
