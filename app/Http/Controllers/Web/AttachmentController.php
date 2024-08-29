<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Requests\Project\ProjectAttachmentRequest;
use App\Requests\Task\TaskAttachmentRequest;
use App\Services\Attachment\AttachmentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttachmentController extends Controller
{
    private $projectView = 'admin.project.';
    private $taskView = 'admin.task.';

    private AttachmentService $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function createProjectAttachment($projectId)
    {
        try {
            return view($this->projectView. 'document-upload', compact('projectId'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function createTaskAttachment($taskId)
    {
        try {
            return view($this->taskView. 'upload-attachment', compact('taskId'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function storeProjectAttachment(ProjectAttachmentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();
                $this->attachmentService->storeProjectAttachment($validatedData);
            DB::commit();
            return redirect()
                ->route('admin.projects.show',$validatedData['project_id'])
                ->with('success', 'Attachments Added Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }

    public function storeTaskAttachment(TaskAttachmentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();
                $this->attachmentService->storeTaskAttachment($validatedData);
            DB::commit();
            return redirect()
                ->route('admin.tasks.show',$validatedData['task_id'])
                ->with('success', 'Attachments Added Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }


    public function deleteAttachmentById($attachmentId): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->attachmentService->deleteProjectAttachment($attachmentId);
            DB::commit();
            return redirect()->back()->with('success', 'Attachment Deleted Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
