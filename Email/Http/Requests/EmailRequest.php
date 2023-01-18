<?php

namespace Modules\Email\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmailRequest extends FormRequest
{

    use ApiResponser;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to_mail' => 'nullable|email|max:50',
            'to_name' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:50',
            'text'    => 'nullable|string'
        ];
    }


    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->respondUnprocessableEntity($validator->errors()->all()));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

}
