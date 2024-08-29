<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Models\Support;
use App\Repositories\SupportRepository;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupportController extends Controller
{
    private $view = 'admin.support.';

    public SupportRepository $supportRepo;
    private NotificationService $notificationService;

    public function __construct(SupportRepository $supportRepo, NotificationService $notificationService)
    {
        $this->supportRepo = $supportRepo;
        $this->notificationService = $notificationService;
    }

    public function getAllQueriesPaginated(Request $request)
    {
        if (\Auth::user()->can('show-support')) {
            try {
                $filterParameters = [
                    'is_seen' => ($request->is_seen) ?? null,
                    'status' => $request->status ?? null,
                    'query_from' => $request->query_from ?? null,
                    'query_to' => $request->query_to ?? null,
                ];
                $with = [
                    'createdBy:id,name,branch_id,department_id,phone',
                    'createdBy.branch:id,name',
                    'createdBy.department:id,dept_name',
                    'departmentQuery:id,dept_name'
                ];
                $select = ['*'];
                $supportQueries = $this->supportRepo->getAllQueryDetail($filterParameters, $select, $with);
                return view($this->view . 'index', compact('supportQueries', 'filterParameters'));
            } catch (\Exception $exception) {
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function changeIsSeenStatus($queryId)
    {
        if (\Auth::user()->can('edit-support')) {
            try {
                $queryDetail = $this->supportRepo->findDetailById($queryId);
                if (!$queryDetail) {
                    throw new \Exception('Query detail not found', 404);
                }
                $this->supportRepo->changeStatusToSeen($queryDetail);
                return AppHelper::sendSuccessResponse('success');
            } catch (\Exception $exception) {
                return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function changeQueryStatus(Request $request, $queryId)
    {
        if (\Auth::user()->can('edit-support')) {
            try {
                $validatedData = $request->validate([
                    'status' => ['required', Rule::in(Support::STATUS)]
                ]);
                $supportDetail = $this->supportRepo->findDetailById($queryId);
                $update = $this->supportRepo->changeQueryStatus($supportDetail, $validatedData['status']);
                if ($update) {
                    $notificationData['title'] = 'Support Notification';
                    $notificationData['type'] = 'support';
                    $notificationData['user_id'] = [$supportDetail->created_by];
                    $notificationData['description'] = 'Your Support Request for ' . $supportDetail->title . ' is viewed and is ' . ucwords(removeSpecialChar($validatedData['status']));
                    $notificationData['notification_for_id'] = $supportDetail->id;
                    $notification = $this->notificationService->store($notificationData);
                    if ($notification) {
                        $this->sendSupportNotification(
                            $notification->title,
                            $notification->description,
                            $notificationData['user_id'],
                            $supportDetail->id
                        );
                    }
                }
                return redirect()->back()->with('success', 'Query Status Changed Successfully');
            } catch (\Exception $exception) {
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function delete($queryId)
    {
        if (\Auth::user()->can('delete-support')) {
            try {
                $queryDetail = $this->supportRepo->findDetailById($queryId);
                $this->supportRepo->delete($queryDetail);
                return redirect()->back()->with('success', 'Query Deleted Successfully');
            } catch (\Exception $exception) {
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    private function sendSupportNotification($title, $message, $userIds, $queryId)
    {
        SMPushHelper::sendSupportNotification($title, $message, $userIds, $queryId);
    }
}
