<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LabelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      $id = $this->id ? : 'NULL';
      return [
        'name' => 'required|string|unique:labels,name,' . $id . ',id|max:255',
        'txt_color' => 'required|string|max:255',
        'bg_color' => 'required|string|max:255'
      ];
    }

    protected function failedValidation(Validator $validator)
    {
      $errors   = [];
      $messages = $validator->getMessageBag();

      foreach ($messages->keys() as $key) {
        $errors[$key] = $messages->get($key, $this->messageFormat)[0];
      }

      throw new HttpResponseException(response()->json($errors, 422));
    }
}
