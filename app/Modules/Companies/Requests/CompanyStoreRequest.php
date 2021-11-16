<?php

namespace App\Modules\Companies\Requests;

use App\Modules\Companies\Models\CompaniesModel;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * @property CompaniesModel id
 * @property Validator|mixed currentValidator
 * @property mixed dontFlash
 * @property mixed company_status
 * @property mixed company_phone
 */
class CompanyStoreRequest extends FormRequest
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
            'company_type' => 'required|string|max:10',
            'company_name' => 'required|unique:companies,company_name,'.$this->id.',company_id|string|max:255',
            'company_director' => 'required|string|max:255',
            'company_email' => 'required|email|max:100',
            'company_address' => 'required|unique:companies,company_address,'.$this->id.',company_id|string|max:255',
            'company_phone' => 'string|max:12',
            'company_site' => 'string|max:255',
            'company_inn' => 'required|unique:companies,company_inn,'.$this->id.',company_id|numeric|min:10000000|max:99999999999999999999',
            'company_bank' => 'required|string|max:100|min:1',
            'company_bik' => 'required|unique:companies,company_bik,'.$this->id.',company_id|numeric|max:99999999999|min:10000000',
            'company_bill' => 'required|unique:companies,company_bill,'.$this->id.',company_id|numeric|max:99999999999999999999|min:100000000000000000',
        ];
    }

    /**
     * Get the exceptions messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'company_name.required' =>'Название поставщика обязательно для заполнения',
            'company_name.unique' =>'Поставщик с таким названием уже зарегистрирован в системе',
            'company_director.required' =>'Укажи ФИО владельца компании',
            'company_director.max' =>'ФИО владельца компании должно быть менее 255 символов',
            'company_type.max' =>'Тип организации должен быть менее 10 символов',
            'company_type.required' =>'Название поставщика обязательно для заполнения',
            'company_name.max' =>'Название поставщика должно быть менее 255 символов',
            'company_email.max' =>'Название поставщика должно быть менее 100 символов',
            'company_email.required' =>'Email поставщика обязательно для заполнения',
            'company_email.unique' =>'Такая электронная почта уже используется',
            'company_address.required' =>'Адрес обязателен для заполнения',
            'company_address.unique' =>'Поставщик с таким адресом уже зарегистрирован в системе',
            'company_address.max' =>'Адрес должен быть менее 255 символов',
            'company_phone.max' =>'Телефон должен быть не длиннее 12 символов',
            'company_site.max' =>'Адрес сайта должен быть не длиннее 255 символов',
            'company_inn.required' =>'ИНН поставщика обязателен для заполнения',
            'company_inn.unique' =>'Уже есть поставщик с таким ИНН',
            'company_inn.max' =>'ИНН поставщика должен быть не длиннее 20 символов',
            'company_inn.min' =>'ИНН поставщика должен быть не менее 8 символов',
            'company_inn.numeric' =>'ИНН поставщика должен состоять только из цифр',
            'company_bank.required' =>'Выберите банк клиента',
            'company_bank.string' =>'Банк должен быть строкой',
            'company_bank.max' =>'Название банка должно быть меньше 100 символов',
            'company_bik.required' =>'БИК поставщика обязателен для заполнения',
            'company_bik.unique' =>'Уже есть поставщик с таким БИК',
            'company_bik.numeric' =>'БИК поставщика должен состоять только из цифр',
            'company_bik.max' =>'БИК поставщика должен быть не длиннее 11 символов',
            'company_bik.min' =>'БИК поставщика должен быть не короче 8 символов',
            'company_bill.required' =>'Номер счета поставщика обязателен для заполнения',
            'company_bill.unique' =>'Уже есть поставщик с таким номером счета',
            'company_bill.numeric' =>'Номер счета поставщика должен состоять только из цифр',
            'company_bill.max' =>'Номер счета поставщика должен быть не длиннее 20 символов',
            'company_bill.min' =>'Номер счета поставщика должен быть не короче 18 символов',
            'company_send_date.required' =>'Число отправки обязательно для заполнения',
            'company_send_period.required' =>'Период отправки обязателен для заполнения',
            'company_autosend.required' =>'Метод отправки обязателен для заполнения',
            'company_status.required' =>'Статус клиента обязателен для заполнения',
            'company_status.boolean' =>'Статус клиента должен содержать булево',
            'company_date.numeric' =>'Число отправки должно состоять только из цифр',
            'company_date.max' =>'Число отправки должно быть не позднее 30 числа',

        ];
    }

    /***
     * @param Validator $validator
     * @throw \Illuminate\Validation\ValidationException
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $this->currentValidator = $validator;
        throw new ValidationException($validator, $this->response($validator));

    }

    public function response(Validator $validator){
        $errors =  $validator->getMessageBag()->toArray();

        if ($this->expectsJson()) {
            $errors = array_values($errors);
            return response()->json($errors);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
