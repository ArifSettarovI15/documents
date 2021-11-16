<?php


namespace App\Modules\Services\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * @property Validator|mixed currentValidator
 * @property mixed dontFlash
 * @method expectsJson()
 * @method getRedirectUrl()
 * @method except(mixed $dontFlash)
 */
class ServicesStoreRequest extends FormRequest
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
            'service_title' => 'required|string|max:100',
            'service_writen' => 'required|string|max:500',
            'service_price' => 'numeric|max:999999',
            'service_period' => 'required|string|max:25',
            'service_status' => 'integer|max:1',
        ];
    }

    /**
     * Get the exceptions messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'service_title.required' =>'Название услуги обязательно для заполнения',
            'service_title.max' =>'Название услуги должно быть менее 100 символов',
            'service_price.numeric' =>'Цена должно состоять из цифр',
            'service_writen.required' =>'Написание услуги обязательно для заполнения',
            'service_writen.max' =>'Написание услуги должно быть менее 500 символов',
            'service_period.required' =>'Периодичность отправки обязательна для заполнения',
            'service_period.max' =>'Периодичность отправки должна быть не более 25 символов',
            'service_status.required' =>'Статус услуги обязателен для заполнения',
            'service_status.integer' =>'Статус услуги должен быть 0 или 1',
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

    public function response(Validator $validator){
        $errors = $validator->getMessageBag()->toArray();

        if ($this->expectsJson()) {
            $errors = array_values($errors);
            return response()->json($errors);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
