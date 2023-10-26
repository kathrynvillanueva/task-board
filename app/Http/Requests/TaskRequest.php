<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
        'name' => 'required|string|unique:tasks,name,' . $id . ',id|max:255',
        'version' => 'nullable|string|max:255',
        'priority_id' => 'nullable|integer|min:1',
        'label_id' => 'nullable|integer|min:1',
        'start_date' => 'required|date',
        'due_date' => 'required|date',
        'progress' => 'nullable|integer',
        'points' => 'required|between:0,99.99',
        'initial_condition' => 'nullable|max:2000',
        'procedure' => 'nullable|max:2000',
        'expected_result' => 'nullable|max:2000'
      ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
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
