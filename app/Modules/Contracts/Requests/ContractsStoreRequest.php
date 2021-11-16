<?php

namespace App\Modules\Contracts\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * @property Validator|mixed currentValidator
 * @property mixed dontFlash
 * @property int contract_id
 * @property mixed cs_service_id
 * @property mixed cs_price
 * @property mixed cs_service_period
 */
class ContractsStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            //'required|string|unique:contracts,contract_number,'.$this->id.',contract_id|max:12'
            'contract_number'=>[
                'required', 'string', 'max:12',
                Rule::unique('contracts')
                    ->ignore($this->id, 'contract_id')
                    ->where('contract_number', $this->contract_number)
            ],

            'contract_date'=>'required|date_format:d.m.Y',
            'contract_supplier'=>'required|integer',
            'contract_client'=>'required|integer',
        ];
    }

    /**
     * Get the exceptions messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'contract_number.required' =>'Номер договора обязателен для заполнения',
            'contract_number.unique' =>'Номер договора должен быть уникален',
            'contract_date.required' =>'Дата договора обязательна для заполнения',
            'contract_supplier.required' =>'Выберите поставщика услуг',
            'contract_client.required' =>'Выберите клиента',
            'contract_number.max' =>'Номер договора должен быть менее 12 символов',
            'contract_date.date_format' =>'Неверный формат даты',
            'contract_client.integer' =>'Неверный формат идентификатора контрагента',

        ];
    }

    /***
     * @param Validator $validator
     * @throw \Illuminate\Validation\ValidationException
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        $this->currentValidator = $validator;
        throw new ValidationException($validator, $this->response($validator));

    }

    public function response(Validator $validator){
        $errors =  $validator->getMessageBag()->toArray();

        if ($this->expectsJson()) {
            $errors = array_values($errors);
            return response()->json($errors[0]);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
