<?php


namespace App\Modules\Plans\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * @property Validator|mixed currentValidator
 * @property mixed dontFlash
 */
class PlansStoreRequest extends FormRequest
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
     *
     * Rule::unique('servers')->where(function ($query) use($ip,$hostname) {
        return $query->where('ip', $ip)
        ->where('hostname', $hostname);
     */
    public function rules(): array
    {
        return [
            'plan_contract' => ['required','integer',
                                Rule::unique('plans')->ignore($this->id, 'plan_id')
                                            ->where(function($query){
                                                        return $query
                                                                ->where('plan_contract', $this->plan_contract)
                                                                ->where('plan_status', 1);})],
            'plan_day' => 'required|integer',
            'plan_status' => 'integer',
        ];
    }

    /**
     * Get the exceptions messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'plan_contract.required' => 'Выберите договор',
            'plan_contract.integer' => 'Не верный формат идентификатора договора',
            'plan_contract.unique' => 'По данному договору уже существует план',
            'plan_day.required' => 'Выберите день отправки',
            'plan_day.integer' => 'Не верный формат дня отправки',
            'plan_status.integer' => 'Не верный формат статуса',
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
            return response()->json($errors);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
