<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class SaveTaskRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'data.attributes.title' => [
                'required', 'min:4',
                Rule::unique('tasks', 'title')->ignore($this->route('task')),
            ],
            'data.attributes.description' => ['required'],
            'data.relationships.manager.data.id' => [
                Rule::requiredIf(! $this->route('task')),
                Rule::exists('users', 'id')
            ]
            /*'data.relationships.user.data.id' => [
                Rule::requiredIf(! $this->route('task')),
                Rule::exists('users', 'id')
            ],*/
        ];
    }

    public function validate()
    {
        $data = parent::validated()['data'];
        $attributes = $data['attributes'];

        if (isset($data['relationships'])) {
            $relationships = $data['relationships'];

            foreach ($relationships as $key => $relationship) {
                //dd($key);
                $attributes = array_merge($attributes, $this->{$key}($relationship));
            }
        }

        return $attributes;
    }

    public function manager($relationship):array
    {
        $userUuid = $relationship['data']['id'];
        return ['user_id' => $userUuid];
    }

}
