<?php

namespace App\Modules\Contracts\Models;


use App\Modules\Clients\Models\ClientsEmailsModel;
use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Files\Repositories\FilesRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer client_id
 * @property mixed contract_id
 * @property mixed contract_client
 * @property mixed contract_file_id
 * @method static find(string $string, mixed $needle)
 * @method static where(string$column, string$operator, string$value)
 * @method static whereIn(string $string, false|string[] $explode)
 */
class ContractsModel extends Model
{
    /**
     * @method create(array $request)
     * @method paginate(int $int)
     * @method where(string $string, $id)
     * @property int contract_id
     */

    protected $table = 'contracts';
    protected $primaryKey = 'contract_id';
    public $timestamps = false;

    protected $fillable = [
        'contract_type',
        'contract_number',
        'contract_supplier',
        'contract_client',
        'contract_date',
        'contract_status',
        'contract_dop',
        'contract_file_id',
        'contract_doc_file_id',
    ];
    protected $fields_names = [
        'contract_type' => 'Тип договора',
        'contract_number' => 'Номер договора',
        'contract_supplier' => 'Исполнитель договора',
        'contract_client'=> 'Заказчик договора',
        'contract_date'=> 'Дата договора',
        'contract_status'=> 'Статус договора',
        'contract_dop'=> 'Данные приложения договора',
        'contract_file_id'=> 'Шаблон договора',
        'contract_doc_file_id'=> 'Файл договора',
    ];

    public function getFieldName($key)
    {
        return $this->fields_names[$key];
    }

    /**
     * @return string
     */
    public function getContractClientNameAttribute(): string
    {
        return ClientsModel::where('client_id',$this->contract_client)->first()->client_name;
    }

    /**
     * @return mixed
     */
    public function getContractServicesAttribute(){
        return ContractsServicesModel::where('cs_contract_id', $this->contract_id)->get();
    }

    /**
     * @return string
     */
    public function getContractFileNameAttribute(): string
    {
        return FilesRepository::getFilename($this->contract_file_id);
    }

    /**
     * @return string
     */
    public function getContractFileUrlAttribute(): string
    {
        return FilesRepository::getFile($this->contract_file_id);
    }

    /**
     * @return string
     */
    public function getContractFilePathAttribute(): string
    {
        return FilesRepository::getFilePath($this->contract_file_id);
    }

    /**
     * @return string
     */
    public function getContractDocFileNameAttribute(): string
    {
        return FilesRepository::getFilename($this->contract_doc_file_id);
    }

    /**
     * @return string
     */
    public function getContractDocFileUrlAttribute(): string
    {
        return FilesRepository::getFile($this->contract_doc_file_id);
    }

    /**
     * @return string
     */
    public function getContractDocFilePathAttribute(): string
    {
        return FilesRepository::getFilePath($this->contract_doc_file_id);
    }

    /**
     * @return ClientsEmailsModel[]|Collection
     */
    public function getContractClientEmailsAttribute()
    {
        return (new ClientsEmailsModel)->where('email_client_id', $this->contract_client)->get();
    }
}
