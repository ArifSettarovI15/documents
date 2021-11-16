<?php


namespace App\Modules\Services\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceInvoiceModel
 * @package App\Modules\Services\Models
 * @mixin Builder
 */
class ServiceInvoiceModel extends Model
{
    protected $table = 'service_invoice';
    protected $primaryKey = 'si_id';
    public $timestamps = false;

    protected $fillable=[
        'si_id',
        'si_service_id',
        'si_invoice_id',
        'si_client_id',
    ];
}
