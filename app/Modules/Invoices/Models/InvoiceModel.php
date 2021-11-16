<?php

namespace App\Modules\Invoices\Models;

use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Contracts\Models\ContractsModel;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static orderBy(string $string, string $way)
 * @method static where(string $string, string $operator, int $int)
 * @method find(int $id)
 *
 * @property mixed invoice_client_id
 * @property mixed invoice_contract_id
 */
class InvoiceModel extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    public $timestamps = false;
    protected $fillable = [
        'invoice_number',
        'invoice_name',
        'invoice_client_id',
        'invoice_contract_id',
        'invoice_email',
        'invoice_file',
        'invoice_date',
        'invoice_sended',
        'invoice_send_active',
        'invoice_custom_client',
        'invoice_custom_contract',
    ];

    protected $fields_names = [
        'invoice_number' => 'Номер счета',
        'invoice_name' => 'Название счета',
        'invoice_client_id' => 'Клиент счета',
        'invoice_contract_id' => 'Договор счета',
        'invoice_email' => 'Email счета',
        'invoice_file' => 'Файл счета',
        'invoice_date' => 'Дата отправки счета',
        'invoice_sended' => 'Статус отправки счета',
        'invoice_send_active' => 'Статус оплаты счета',
        'invoice_payed'=> 'Статус оплаты счета',
    ];

    public function getFieldName($key): string
    {
        return $this->fields_names[$key];
    }


        public function getInvoiceContractNameAttribute() :string
    {
        $contract = ContractsModel::where('contract_id','=', $this->invoice_contract_id)->select('contract_number', 'contract_date')->first()->toArray();

        return "Договор № ".$contract['contract_number']." от ".$contract['contract_date'];
    }
    public function getInvoiceClientNameAttribute(): string
    {
        return ClientsModel::where('client_id', $this->invoice_client_id)->pluck('client_name')->first();
    }
}
