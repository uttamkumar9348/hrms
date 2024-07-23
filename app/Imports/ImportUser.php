<?php

namespace App\Imports;

use App\Helpers\AppHelper;
use App\Models\Branch;
use App\Models\Department;
use App\Models\OfficeTime;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportUser implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        // $eventDate = date('Y-m-d', strt$row['4']));
        Log::info($row['4']);
        return new User([
            "name" => $row['0'],
            "email" => $row['2'],
            "username" => $row['9'],
            "password" => $row['10'],
            "address" => $row['1'],
            "avatar" => $row['7'],
            "dob" =>date('Y-m-d',strtotime($row['4'])),
            "gender" => $row['5'],
            "phone" => $row['0'],
            "role_id" => Role::where('name',$row['11'])->value('id'),
            "leave_allocated" => $row['20'],
            "employment_type" => $row['16'],
            "joining_date" => date('Y-m-d',strtotime($row['18'])),
            "workspace_type" => $row['19'],
            "company_id" => AppHelper::getAuthUserCompanyId(),
            "branch_id" => Branch::where('name',$row['12'])->value('id'),
            "department_id" => Department::where('dept_name',$row['13'])->value('id'),
            "post_id" => Post::where('post_name',$row['14'])->value('id'),
            "supervisor_id" =>  User::where('name',$row['15'])->value('id'),
            "office_time_id" =>  OfficeTime::where('opening_time',$row['17'])->value('id'),
            "marital_status" => $row['6'],
           
        ]);

        // dd($result);
    }
}
