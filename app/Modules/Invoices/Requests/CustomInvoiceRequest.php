<?php

namespace App\Modules\Invoices\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CustomInvoiceRequest extends FormRequest
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
    public function rules() : array
    {
        return [
            'invoice_supplies' => ['required', 'numeric', 'max:100'],
            'invoice_contract' => ['required', 'string', 'max:200'],
            'client_name' => ['required', 'string', 'max:200'],
            'client_phone' => ['required', 'string', 'min:11','max:12'],
            'client_address' => ['required', 'string', 'max:250'],
            'service_names' => ['required','array'],
            'service_names.*' => ['required','string'],
            'service_prices' => ['required','array'],
            'service_prices.*' => ['required','integer'],
            'invoice_date' => ['required','date_format:d.m.Y'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages() : array
    {
        return [
           'invoice_supplies.required' => 'Укажите поставщика',
           'invoice_contract.required' => 'Укажите основание счета',
           'invoice_contract.max' => 'Длина текста в поле Основание счета должна содержать до 200 символов',
           'invoice_supplies.numeric' => 'Не верный формат данных поставщика',
           'invoice_supplies.max' => 'Не верный формат данных поставщика',
           'client_name.required' => 'Укажите название или ФИО покупателя',
           'client_name.string' =>'Не верный формат данных',
           'client_name.max'  => 'Длина названия или ФИО покупателя должна быть меньше 200 символов',
           'client_phone.required' => 'Укажите телефон покупателя',
           'client_phone.string' =>'Не верный формат данных для телефона',
           'client_phone.max' => 'Длина телефона должна быть до 12 символов',
           'client_phone.min' => 'Длина телефона должна быть от 11 символов',
           'client_address.required' =>'Укажите адрес покупателя',
           'client_address.string' =>'Не верный формат данных для адреса',
           'client_address.max' => 'Длина адреса должна быть меньше 250 символов',
           'service_names.required' => 'Вы не выбрали ни одной услуги',
           'service_names.array' =>'Не верный формат данных для услуг',
           'service_prices.*.integer'=>'Не верный формат данных стоимости услуг',
           'service_prices.required' => 'Вы не указали стоимость услуг',
           'service_prices.array'=>'Не верный формат данных для стоимости',
           'invoice_date.required'=>'Укажите дату',
           'invoice_date.date_format' =>'Не верный формат даты',
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
        $errors =  $validator->errors()->first();

        if ($this->expectsJson()) {

            return response()->json(['status'=>false, 'message'=>$errors]);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
