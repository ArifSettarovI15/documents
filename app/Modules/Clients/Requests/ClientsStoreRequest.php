<?php

namespace App\Modules\Clients\Requests;

use App\Modules\Clients\Models\ClientsModel;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * @property ClientsModel id
 * @property Validator|mixed currentValidator
 * @property mixed dontFlash
 */
class ClientsStoreRequest extends FormRequest
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
        $array = ['client_name' => 'required|string|max:255',
                  'client_email' => 'required|email|max:100',
                  'client_address' => 'required|string|max:255',
                  'client_phone' => 'max:12',
                  'client_site' => 'max:255',
                  'client_bank' => 'required|string|max:100',
                  'client_bik' => 'required|numeric|min:100000000|max:999999999',
                  'client_bill' => 'required|numeric|min:10000000000000000000|max:99999999999999999999',
                  'client_send_date' => 'required|numeric|max:30',
                  'client_send_period' => 'required|string|max:150',
                  'client_autosend' => 'required|numeric|max:1'];

        if (in_array($this->client_type,['ip', 'ooo']))
        {
            $dop_fields = [
                'client_director' => 'required|string|max:150',
                'client_inn' => 'required|unique:clients,client_inn,'.$this->id.',client_id|numeric|min:1000000000|max:9999999999',
                'client_ogrn' => 'required|unique:clients,client_ogrn,'.$this->id.',client_id|numeric|min:1000000000000|max:999999999999999',
                'client_okpo' => 'required|unique:clients,client_okpo,'.$this->id.',client_id|numeric|min:10000000|max:9999999999',
                ];
            if ($this->client_type == 'ooo')
                $dop_fields['client_kpp']= 'required|unique:clients,client_kpp,'.$this->id.',client_id|numeric|max:999999999|min:1000000000';
        }
        if ($this->client_type == 'fiz')
        {
            $dop_fields = [
                'client_birthday'=>'required|date_format:d.m.Y',
                'client_birthplace'=>'required',
                'client_passport'=>'required|unique:clients,client_passport,'.$this->id.',client_id|numeric|min:1000000000|max:9999999999',
                'client_passport_issued'=>'required',
                'client_passport_date'=>'required|date_format:d.m.Y',
                'client_passport_code'=>'required|numeric|min:6|max:6',
            ];
        }


        return array_merge($array, $dop_fields);
    }

    /**
     * Get the exceptions messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'client_passport.unique' =>  'Такие паспортные данные уже используются',
            'client_passport_code.required' =>  'Заполните код подразделения',
            'client_passport_code.max' =>  'Код подразделения должен содержать 6 цифр',
            'client_passport_code.numeric' =>  'Код подразделения должен содержать только цифры',
            'client_passport.required' =>  'Заполните паспортные данные',
            'client_passport.max' =>  'Поле серия и номер паспорта должно содержать 10 цифр',
            'client_passport.min' =>  'Поле серия и номер паспорта должно содержать 10 цифр',
            'client_passport.numeric' =>  'Серия и номер паспорта должены содержать только цифры',
            'client_passport_date.required'=>'Введите дату выдачи паспорта клиента',
            'client_passport_issued.required'=>'Поле Кем выдан обязательно для заполнения',
            'client_passport_date.date_format'=>'Не верный формат даты в поле Дата выдачи',
            'client_birthplace.required'=>'Введите место рождения клиента',
            'client_birthday.required'=>'Введите дату рождения клиента',
            'client_birthday.date_format'=>'Не верный формат даты рождения клиента',

            'client_kpp.required'=>'КПП предприятия обязательно для заполнения',
            'client_ogrn.required'=>'ОГРН предприятия обязательно для заполнения',
            'client_okpo.required'=>'ОКПО предприятия обязательно для заполнения',

            'client_kpp.unique'=>'Предприятие с таким КПП уже зарегистрировано в системе',
            'client_ogrn.unique'=>'Предприятие с таким ОГРН уже зарегистрировано в системе',
            'client_okpo.unique'=>'Предприятие с таким ОКПО уже зарегистрировано в системе',

            'client_kpp.numeric'=>'Неверный формат КПП',
            'client_ogrn.numeric'=>'Неверный формат ОГРН',
            'client_okpo.numeric'=>'Неверный формат ОКПО',

            'client_kpp.max'=>'КПП должен содержать 9 символов',
            'client_kpp.min'=>'КПП должен содержать 9 символов',
            'client_ogrn.max'=>'Длина номера ОГРН от 13 до 15 символов',
            'client_ogrn.min'=>'Длина номера ОГРН от 13 до 15 символов',
            'client_okpo.max'=>'ОКПО должен содержать 10 символов',
            'client_okpo.min'=>'ОКПО должен содержать 10 символов',



            'client_name.required' =>'Название контрагента обязательно для заполнения',
            'client_name.max' =>'Название контрагента должно быть менее 255 символов',
            'client_email.max' =>'Название контрагента должно быть менее 100 символов',
            'client_email.required' =>'Email контрагента обязательно для заполнения',
            'client_email.unique' =>'Такая электронная почта уже используется',
            'client_address.required' =>'Адрес обязателен для заполнения',
            'client_address.max' =>'Адрес должен быть менее 255 символов',
            'client_phone.max' =>'Телефон должен быть не длиннее 12 символов',
            'client_phone.min' =>'Телефон должен быть не короче 11 символов',
            'client_site.max' =>'Адрес сайта должен быть не длиннее 255 символов',
            'client_inn.required' =>'ИНН контрагента обязателен для заполнения',
            'client_inn.unique' =>'Уже есть контрагент с таким ИНН',
            'client_inn.max' =>'ИНН контрагента должен содержать 10 символов',
            'client_inn.min' =>'ИНН контрагента должен содержать 10 символов',
            'client_inn.numeric' =>'ИНН контрагента должен состоять только из цифр',
            'client_bank.required' =>'Выберите банк клиента',
            'client_bank.string' =>'Банк должен быть строкой',
            'client_bank.max' =>'Название банка должно быть меньше 100 символов',
            'client_bik.required' =>'БИК контрагента обязателен для заполнения',
            'client_bik.numeric' =>'БИК контрагента должен состоять только из цифр',
            'client_bik.max' =>'БИК контрагента должен содержать 9 символов',
            'client_bik.min' =>'БИК контрагента должен содержать 9 символов',
            'client_bill.required' =>'Номер счета контрагента обязателен для заполнения',
            'client_bill.numeric' =>'Номер счета контрагента должен состоять только из цифр',
            'client_bill.max' =>'Номер счета контрагента должен содержать 20 символов',
            'client_bill.min' =>'Номер счета контрагента должен содержать 20 символов',
            'client_send_date.required' =>'Число отправки обязательно для заполнения',
            'client_send_period.required' =>'Период отправки обязателен для заполнения',
            'client_autosend.required' =>'Метод отправки обязателен для заполнения',
            'client_status.required' =>'Статус клиента обязателен для заполнения',
            'client_status.boolean' =>'Статус клиента должен содержать булево',
            'client_date.numeric' =>'Число отправки должно состоять только из цифр',
            'client_date.max' =>'Число отправки должно быть не позднее 30 числа',

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
        $errors =  $validator->errors()->all();

        if ($this->expectsJson()) {

            return response()->json(['status'=>false, 'message'=>$errors]);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
