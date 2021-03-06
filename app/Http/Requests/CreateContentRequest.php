<?php

namespace App\Http\Requests;

use App\Models\Content;
use Illuminate\Foundation\Http\FormRequest;

class CreateContentRequest extends FormRequest
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

    return Content::$rules;
  }

  public function attributes()
  {
    return Content::attributes();
  }

  // public function messages()
  // {
  //   return Content::messages();
  // }
}
