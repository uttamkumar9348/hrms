<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Requests\Task\TaskCommentRequest;
use App\Resources\Comment\CommentWithReplyResource;
use App\Resources\Comment\ReplyResource;
use App\Services\Notification\NotificationService;
use App\Services\Task\TaskCommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class TaskCommentApiController extends Controller
{
    public TaskCommentService $commentService;
    public NotificationService $notificationService;


    public function __construct(TaskCommentService $commentService,NotificationService $notificationService)
    {
        $this->commentService = $commentService;
        $this->notificationService = $notificationService;
    }


    public function saveComment(TaskCommentRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $mentionedMember = $validatedData['mentioned'] ?? [];
            DB::beginTransaction();
            if (is_null($validatedData['comment_id'])) {
                $data = $this->commentService->storeTaskCommentDetail($validatedData);
                $commentType = 'comment';
                $taskName = $data?->task?->name;
                if($data?->task?->created_by != getAuthUserCode()){
                    $mentionedMember[] =  $data?->task?->created_by;
                }
                $detail = new CommentWithReplyResource($data);
            } else {
                $data = $this->commentService->storeCommentReply($validatedData);
                $comment = $data->comment;
                $commentType = 'comment reply';
                $taskName = $comment->task?->name;
                if($comment->created_by != getAuthUserCode()){
                    $mentionedMember[] = $comment->created_by;
                }
                if($comment->task?->created_by != getAuthUserCode()){
                    $mentionedMember[] = $comment->task?->created_by;
                }
                $detail = new CommentWithReplyResource($comment);
            }
            DB::commit();
            if (!empty($mentionedMember)) {
                $mentionedMember = array_unique($mentionedMember);
                $notificationData = [
                    'title' => 'Comment Notification',
                    'type' => 'comment',
                    'user_id' => $mentionedMember,
                    'description' => 'You are mentioned in task ' . $taskName . ' ' . $commentType,
                    'notification_for_id' => $validatedData['task_id'],
                ];
                $notification = $this->notificationService->store($notificationData);
                if($notification){
                    $this->sendNotificationToMentionedMemberInComment(
                        $notificationData['title'],
                        $notificationData['description'],
                        $notificationData['user_id'],
                        $notificationData['notification_for_id']
                    );
                }
            }
            return AppHelper::sendSuccessResponse('Comment Added Successfully', $detail);
        } catch (Exception $exception) {
            DB::rollBack();
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    private function sendNotificationToMentionedMemberInComment($title,$message, $userIds, $taskId)
    {
        SMPushHelper::sendProjectManagementNotification($title, $message, $userIds, $taskId);
    }

    public function deleteComment($commentId): JsonResponse
    {
        try {
            DB::beginTransaction();
                $this->commentService->deleteTaskComment($commentId);
            DB::commit();
            return AppHelper::sendSuccessResponse('Comment Deleted Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteReply($replyId): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->commentService->deleteReply($replyId);
            DB::commit();
            return AppHelper::sendSuccessResponse('Comment Reply Deleted Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
