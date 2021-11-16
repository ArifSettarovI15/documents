<?php

namespace App\Modules\Clients\Models;


use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Invoices\Models\InvoiceModel;
use Illuminate\Database\Eloquent\Model;


/**
 * @property integer client_id
 * @method static where(string $string, int $int)
 * @method static find($id)
 * @method paginate(int $int)
 * @method create(array $all)
 */
class ClientsModel extends Model
{
    /**
     * @method create(array $request)
     * @method paginate(int $int)
     * @method where(string $string, $id)
     * @property ClientsModel client_id
     */

    protected $table = 'clients';

    protected $primaryKey = 'client_id';

    public $timestamps = false;

    protected $fillable = [
        'client_type',
        'client_name',
        'client_director',
        'client_email',
        'client_status',
        'client_send_date',
        'client_send_period',
        'client_autosend',
        'client_site',
        'client_inn',
        'client_kpp',
        'client_ogrn',
        'client_okpo',
        'client_birthday',
        'client_birthplace',
        'client_passport',
        'client_passport_issued',
        'client_passport_date',
        'client_passport_code',
        'client_bank',
        'client_bik',
        'client_bill',
        'client_phone',
        'client_address',
    ];

    protected $fields_names = [
        'client_type' => 'Тип организации',
        'client_name' => 'Название организации',
        'client_director' => 'ФИО владельца',
        'client_email' => 'Email клиента',
        'client_status' => 'Статус клиента',
        'client_send_date' => 'Дата отправки счетов',
        'client_send_period' => 'Период отправки счетов',
        'client_autosend' => 'Метод отправки счетов',
        'client_site' => 'Сайт клиента',
        'client_inn' => 'ИНН клиента',
        'client_kpp' => 'КПП клиента',
        'client_ogrn' => 'ОГРН клиента',
        'client_okpo' => 'ОКПО клиента',
        'client_birthday' => 'Дата рождения клиента',
        'client_birthplace' => 'Место рождения клиента',
        'client_passport' => 'Паспортные данные клиента',
        'client_passport_issued' => 'Кем выдан паспорт',
        'client_passport_date' => 'Дата выдачи паспорта',
        'client_passport_code' => 'Код паспорта',
        'client_bank' => 'Банк клиента',
        'client_bik' => 'БИК банка клиента',
        'client_bill' => 'Номер счета клиента',
        'client_phone' => 'Телефон клиента',
        'client_address' => 'Адрес клиента',
    ];

    public function getFieldName($key)
    {
        return $this->fields_names[$key];
    }

    public function getClientContractsAttribute(){
        return ContractsModel::where('contract_client','=',$this->client_id)->get();
    }
    public function getClientHasInvoicesAttribute()
    {
        return InvoiceModel::where('invoice_client_id', '=', $this->client_id)->count();
    }

}
