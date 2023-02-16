<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ListingUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
        'title' => 'required',
        'company' => ['required'],
        'location' => 'required',
        'website' => 'required',
        'email' => ['required', 'email'],
        'tags' => 'required',
        'description' => 'required'
      ];
    }

    public function messages()
    {
      return[
        'title.required' => __('title required'),
        'company.required' => __('company required'),
        'location.required' => __('location required'),
        'website.required' => __('website required'),
        'email.required' => __('email required'),
        'email.email' => __('email format wrong'),
        'tags.required' => __('tags required'),
        'description.required' => __('description required')
      ];
    }
}
