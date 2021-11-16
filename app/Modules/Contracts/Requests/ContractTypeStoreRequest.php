<?php


namespace App\Modules\Contracts\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed ct_litera
 * @property mixed ct_status
 * @property mixed id
 * @property mixed ct_name
 * @property Validator|mixed currentValidator
 * @property mixed dontFlash
 * @property mixed ct_dop_keys
 * @property mixed ct_dop_names
 */
class ContractTypeStoreRequest extends FormRequest
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
            'ct_name'     => [
                'required',
                'string',
                'max:150',
                Rule::unique('contract_types')
                    ->ignore($this->id, 'ct_id')
                    ->where(
                        function ($query) {
                            return $query
                                ->where('ct_name', $this->ct_name)
                                ->where('ct_status', $this->ct_status);
                        }
                    ),
            ],
            'ct_litera'   => [
                'bail',
                'required',
                'string',
                'max:5',
                Rule::unique('contract_types')->ignore($this->id, 'ct_id')
                    ->where(
                        function ($query) {
                            return $query
                                ->where('ct_litera', $this->ct_litera)
                                ->where('ct_status', $this->ct_status);
                        }
                    ),
            ],
            'ct_start'    => ['bail', 'required', 'numeric', 'max:10000'],
            'ct_template' => ['bail', 'required', 'integer'],
            'ct_services' => ['bail', 'required', 'array'],
        ];
    }

    /**
     * Get the exceptions messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'ct_name.required'     => 'Заполните название типа договора',
            'ct_litera.required'   => 'Заполните литеру типа договора',
            'ct_name.unique'       => 'Такое название типа договора уже используется',
            'ct_litera.unique'     => 'Значение литеры уже используется',
            'ct_start.required'    => 'Заполните старт нумерации договоров',
            'ct_template.required' => 'Выберите документ с шаблоном договора',
            'ct_services.required' => 'Выберите хотя-бы одну услугу',
            'ct_name.string'       => 'Название типа договора должно быть строкой',
            'ct_litera.string'     => 'Литера договора должна быть строкой',
            'ct_start.numeric'     => 'Не верный формат начала нумерации договора',
            'ct_template.integer'  => 'Не верный формат идентификатора файла',
            'ct_services.array'    => 'Не верный формат списка услуг',
            'ct_name.max'          => 'Название типа договора должно содержать до 150 символов',
            'ct_litera.max'        => 'Литера типа договора должна содержать до 5 символов',
            'ct_start.max'         => 'Cтарт нумерации договоров должен быть меньше 10.000',
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
