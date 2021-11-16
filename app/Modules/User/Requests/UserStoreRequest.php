<?php

namespace App\Modules\User\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class UserStoreRequest extends FormRequest
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
            'login'=>['required', Rule::unique('users')->ignore($this->id, 'id')->where('login', $this->login),'max:50'],
            'password'=>['required', 'min:8', 'max:100'],
            'role_id'=>['required', 'numeric', "max:10"],
            'email'=>['required',Rule::unique('users')->ignore($this->id, 'id')->where('email', $this->email),'max:100'],
            'profile_name'=>['max:75'],
            'profile_lastname'=>['max:100'],
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'required'=>'Поле :attribute обязательно для заполнения',
            'min'=>'Количество символов в поле :attribute должно быть больше :min',
            'max'=>'Количество символов в поле :attribute должно быть меньше :max',
            'unique'=>'Такой :attribute уже используется',
            'string'=>'Не верный формат введённых данных для поля :attribute',
            'numeric'=>'Поле :attribute должно содержать только цифры',
        ];
    }
    public function attributes(): array
    {
        return [
            'login'=>'Логин',
            'password'=>'Пароль',
            'role_id'=>'Роль',
            'email'=>'Email',
            'profile_name'=>'Имя',
            'profile_lastname'=>'Фамилия',
        ];
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $this->currentValidator = $validator;
        throw new ValidationException($validator, $this->response($validator));
    }
    public function response(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        if ($this->expectsJson()) {
            $errors = array_values($errors)[0];
            return response()->json(['status'=>false, 'message'=>$errors[0]]);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);

    }
}
