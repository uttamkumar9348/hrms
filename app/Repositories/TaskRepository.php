<?php

namespace App\Repositories;

use App\Helpers\PMHelper;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskRepository
{
    public function getAllFilteredTasks($filterParameters,$select,$with): mixed
    {
        return Task::query()->select($select)->with($with)

            ->when(isset($filterParameters['task_name']), function ($query) use ($filterParameters) {
                $query->where('name', 'like', '%' . $filterParameters['task_name'] . '%');
            })
            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })
            ->when(isset($filterParameters['priority']), function ($query) use ($filterParameters) {
                $query->where('priority', $filterParameters['priority']);
            })
            ->when(isset($filterParameters['members']), function ($query) use ($filterParameters) {
                $query->whereHas('assignedMembers.user',function($subQuery) use ($filterParameters){
                    $subQuery->whereIn('id', $filterParameters['members']);
                });
            })
            ->when(isset($filterParameters['projectts']), function ($query) use ($filterParameters) {
                $query->whereHas('project',function($subQuery) use ($filterParameters){
                    $subQuery->where('id', $filterParameters['projectts']);
                });
            })
            ->latest()
            ->paginate(Task::RECORDS_PER_PAGE);
    }

    public function getAllTaskDetailForPieChart()
    {
        return Task::selectRaw('status, count(*) as count')
            ->where('is_active', 1)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function store($validatedData):mixed
    {
        return Task::create($validatedData)->fresh();
    }

    public function toggleStatus($taskDetail):mixed
    {
        return $taskDetail->update([
            'is_active' => !$taskDetail->is_active,
        ]);
    }

    public function getAllTasks($select)
    {
        return Task::select($select)->latest()->get();
    }

    public function findTaskDetailById($id,$with=[],$select=['*']):mixed
    {
        return Task::select($select)
            ->with($with)
            ->where('id',$id)
            ->first();
    }

    public function findAssignedMemberTaskDetailById($taskId,$with,$select):mixed
    {
        return Task::select($select)
            ->with($with)
            ->where(function($query) use ($taskId){
                $query->whereHas('assignedMembers',function($subQuery){
                    $subQuery->where('member_id', getAuthUserCode());
                })
                ->orWhereHas('project.projectLeaders', function ($query) {
                    $query->where('leader_id', getAuthUserCode());
                });
            })
            ->whereHas('project',function($projectQuery){
                $projectQuery->where('is_active',1);
            })
            ->where('id',$taskId)
            ->where('is_active',1)
            ->first();
    }

    public function update($taskDetail, $validatedData)
    {
        if(isset($validatedData['attachments'])){
            $taskDetail->taskAttachments()->delete();
        }
        $taskDetail->update($validatedData);
        return $taskDetail->assignedMembers()->delete();
    }

    public function getUserAssignedTasksLists($userId,$select,$with,$perPage)
    {
        return Task::query()->select($select)->with($with)
            ->where(function($query) use ($userId){
                $query->whereHas('assignedMembers.user',function($subQuery) use ($userId){
                    $subQuery->where('id', $userId);
                })
                ->orWhereHas('project.projectLeaders', function ($query) {
                    $query->where('leader_id', getAuthUserCode());
                });
            })
            ->whereHas('project',function($projectQuery){
                $projectQuery->where('is_active',1);
            })
            ->where('is_active',1)
            ->paginate($perPage);
    }

    public function delete(Task $taskDetail)
    {
        return $taskDetail->delete();
    }

    public function dropAssignedMembers($taskDetail)
    {
        $taskDetail->assignedMembers()->delete();
        return true;
    }

    public function assignMemberToTask(Task $taskDetail,$assignedMemberArray)
    {
        return $taskDetail->assignedMembers()->createMany($assignedMemberArray);
    }

    public function changeTaskStatus($taskDetail,$changedStatus)
    {
        return $taskDetail->update([
            'status' => $changedStatus
        ]);
    }

    public function toggleTaskByProjectId($projectId,$changedStatus)
    {
        return Task::where('project_id',$projectId)->update([
            'is_active' => $changedStatus
        ]);
    }

}
