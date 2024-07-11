<?php

namespace App\Requests\Task;

use App\Models\Project;
use App\Rules\TaskDateRule;
use Illuminate\Foundation\Http\FormRequest;
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

    public function prepareForValidation()
    {
        $this->merge([
            'start_date' => date("Y-m-d H:i", strtotime($this->start_date)),
            'end_date' => date("Y-m-d H:i", strtotime($this->end_date)),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $projectId = $this->project_id;
        $rules = [
            'name' => ['required','string','max:400'],
            'project_id' => ['required',
                Rule::exists('projectts','id')
                    ->where('is_active',1)
            ],
            'priority' => ['nullable',Rule::in(Project::PRIORITY)],
            'status' => ['nullable',Rule::in(Project::STATUS)],
            'end_date' => ['required','date','date_format:Y-m-d H:i','after:start_date',new TaskDateRule($this->project_id)],
            'description' => ['required','string'],
            'assigned_member' => ['required','array','min:1'],
            'assigned_member.*' => ['required',
                Rule::exists('assigned_members','member_id')
                    ->where('assignable_id',$projectId)
                    ->where('assignable_type','project')
            ],

            'is_active' => ['nullable', 'boolean', Rule::in([1, 0])],
            'attachments' => ['sometimes','array','min:1'],
            'attachments.*.' => ['sometimes','file','mimes:pdf,jpeg,png,jpg,docx,doc,xls,txt,webp,zip','max:5048']
        ];

        if($this->isMethod('put')) {
            $rules['start_date'] = ['required','date','date_format:Y-m-d H:i',new TaskDateRule($this->project_id)];
        } else {
            $rules['start_date'] = ['required','date','date_format:Y-m-d H:i','after_or_equal:today',new TaskDateRule($this->project_id)];
        }

        return $rules;

    }

}
