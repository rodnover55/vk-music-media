<?php

namespace VkMusic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int viewer_id
 */
class TokenRequest extends FormRequest
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
        return [
            'viewer_id' => 'integer|required',
            'viewer_type' => 'integer|required',
            'access_token' => 'string|required',
            'group_id' => 'integer|required',
            'api_result' => 'string|required'
        ];
    }
}
