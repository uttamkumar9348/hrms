<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Services\Task\TaskChecklistService;
use Illuminate\Http\JsonResponse;
use Exception;

class TaskChecklistApiController extends Controller
{
    private TaskChecklistService $taskChecklistService;

    public function __construct(TaskChecklistService $taskChecklistService)
    {
        $this->taskChecklistService = $taskChecklistService;
    }

    public function toggleCheckListIsCompletedStatus($checklistId): JsonResponse
    {
        try {
            $checkList = $this->taskChecklistService->toggleIsCompletedStatusByAssignedUserOnly($checklistId);
            return AppHelper::sendSuccessResponse('Status updated successfully',$checkList);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
