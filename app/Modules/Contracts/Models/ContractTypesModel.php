<?php


namespace App\Modules\Contracts\Models;


use App\Modules\Files\Repositories\FilesRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed ct_template
 * @property mixed ct_id
 * @property mixed ct_litera
 * @method static where(string $string, int $int)
 */
class ContractTypesModel extends Model
{

    protected $table = 'contract_types';
    protected $primaryKey  = 'ct_id';
    public $timestamps = false;
    protected $fillable = [
        'ct_name',
        'ct_dop_keys',
        'ct_dop_names',
        'ct_dop_tooltips',
        'ct_litera',
        'ct_start',
        'ct_template',
        'ct_status'
    ];
    protected $fields_names = [
        'ct_name' => 'Название типа договора',
        'ct_dop_keys' => 'Ключи для типа договора',
        'ct_dop_names'=> 'Названия доп. полей типа договора',
        'ct_dop_tooltips'=> 'Подсказки для типа договора',
        'ct_litera'=> 'Литера типа договора',
        'ct_start'=> 'Начало нумерации типа договора',
        'ct_template'=> 'Шаблон типа договора',
        'ct_status'=> 'Статус типа договора',
    ];

    public function getFieldName($key)
    {
        return $this->fields_names[$key];
    }

    /** @noinspection PhpUnusedFunctionInspection|PhpUnusedElementInspection */
    public function getCtTemplateFilenameAttribute(): string
    {
        return FilesRepository::getFilename($this->ct_template);
    }
    public function getCtTemplateFileUrlAttribute(): string
    {
        return FilesRepository::getFile($this->ct_template);
    }
    public function getDataNumberAttribute(): int
    {
        $contract = ContractsModel::where('contract_number', 'LIKE', $this->ct_litera.'%')
                                    ->orderBy('contract_number', 'DESC')
                                    ->pluck('contract_number')->first();
        $contract = str_replace($this->ct_litera, '', $contract);
        $contract = str_replace('-', '', $contract);
        $contract = (int) $contract;
        return $contract + 1;
    }

}
