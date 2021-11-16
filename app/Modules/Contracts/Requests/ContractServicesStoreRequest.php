<?php


namespace App\Modules\Contracts\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * @property Validator|mixed currentValidator
 * @property mixed dontFlash
 */
class ContractServicesStoreRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'cs_contract_id' => 'required|integer',
            'cs_service_id' => 'required|integer',
            'cs_price' => 'required|integer',
        ];
    }

    /**
     * Get the exceptions messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'cs_contract_id.required' => 'Выберите договор',
            'cs_contract_id.integer' => 'Не верный формат идентификатора договора',
            'cs_service_id.required' => 'Выберите услугу',
            'cs_service_id.integer' => 'Не верный формат идентификатора услуги',
            'cs_price.required' => 'Укажите стоимость',
            'cs_price.integer' => 'Не церный формат стоимости',
        ];
    }

    /***
     * @param Validator $validator
     * @throw \Illuminate\Validation\ValidationException
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator):void
    {
        $this->currentValidator = $validator;
        throw new ValidationException($validator, $this->response($validator));

    }

    public function response(Validator $validator)
    {
        $errors = $validator->getMessageBag()->toArray();

        if ($this->expectsJson()) {
            $errors = array_values($errors);
            return response()->json($errors[0]);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
