<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\NotificationRepository;
use App\Requests\Notification\NotificationRequest;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    private $view = 'admin.notification.';

    private NotificationRepository $notificationRepo;
    private CompanyRepository $companyRepo;


    public function __construct(NotificationRepository $notificationRepo, CompanyRepository $companyRepo)
    {
        $this->notificationRepo = $notificationRepo;
        $this->companyRepo = $companyRepo;
    }

    public function index(Request $request)
    {
        try {
            $filterParameters = [
                'type' =>$request->type ?? null,
            ];
            $select = ['*'];
            $with = [];
            $notifications = $this->notificationRepo->getAllCompanyNotifications($filterParameters,$select, $with);
            return view($this->view . 'index', compact('notifications','filterParameters'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create(): Factory|View|RedirectResponse|Application
    {
        try {
            $select = ['id', 'name'];
            $companyDetail = $this->companyRepo->getCompanyDetail($select);
            return view($this->view . 'create',
                compact('companyDetail')
            );
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(NotificationRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();
            $notification = $this->notificationRepo->store($validatedData);
            DB::commit();
            if ($notification) {
                $this->sendNotification(ucfirst($validatedData['title']), removeHtmlTags($notification['description']));
            }
            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notification created and sent Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('danger', $e->getMessage())->withInput();
        }
    }

    private function sendNotification($title, $description)
    {
        SMPushHelper::sendPush($title, $description);
    }

    public function show($id): JsonResponse
    {
        try {
            $select = ['*'];
            $with = ['notifiedUsers.userDetail:id,name'];
            $notifiedUser = [];
            $notification = $this->notificationRepo->findNotificationDetailById($id, $select, $with);
            $notification->description = removeHtmlTags($notification->description);
            if ($notification->notifiedUsers) {
                foreach ($notification->notifiedUsers as $key => $value) {
                    $notifiedUser[] = $value->userDetail->name;
                }
            }
            return response()->json([
                'data' => $notification,
                'user' => count($notifiedUser) > 0 ? implode(' , ', $notifiedUser) : 'All'
            ]);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function edit($id): Factory|View|RedirectResponse|Application
    {
        try {
            $notificationDetail = $this->notificationRepo->findNotificationDetailById($id);
            $notificationDetail->description = removeHtmlTags($notificationDetail->description);
            $select = ['id', 'name'];
            $companyDetail = $this->companyRepo->getCompanyDetail($select);
            return view($this->view . 'edit', compact('notificationDetail', 'companyDetail'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(NotificationRequest $request, $id): RedirectResponse
    {
        try {
            $validatedData = $request->validated();
            $notificationDetail = $this->notificationRepo->findNotificationDetailById($id);
            if (!$notificationDetail) {
                throw new Exception('Notification Not Found', 404);
            }
            DB::beginTransaction();
            $updateNotification = $this->notificationRepo->update($notificationDetail, $validatedData);
            DB::commit();
            if ($updateNotification) {
                $this->sendNotification(ucfirst($validatedData['title']), removeHtmlTags($validatedData['description']));
            }
            return redirect()
                ->route('admin.notifications.index')
                ->with('success', 'Notification Updated and Sent Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage())
                ->withInput();
        }
    }

    public function toggleStatus($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->notificationRepo->toggleStatus($id);
            DB::commit();
            return redirect()->back()->with('success', 'Notification Status changed  Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function delete($id): RedirectResponse
    {
        try {
            $notificationDetail = $this->notificationRepo->findNotificationDetailById($id);
            if (!$notificationDetail) {
                throw new Exception('Notification Detail Not Found', 404);
            }
            DB::beginTransaction();
            $this->notificationRepo->delete($notificationDetail);
            DB::commit();
            return redirect()->back()->with('success', 'Notification Deleted  Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function sendNotificationToAllCompanyUser($notificationId): RedirectResponse
    {
        try {
            $notificationDetail = $this->notificationRepo->findNotificationDetailById($notificationId);
            if (!$notificationDetail) {
                throw new Exception('Notification Detail Not found');
            }
            $this->sendNotification(ucfirst($notificationDetail->title), removeHtmlTags($notificationDetail->description));
            DB::beginTransaction();
            $validatedData['is_active'] = 1;
            $validatedData['notification_publish_date'] = Carbon::now()->format('Y-m-d H:i:s');
            $this->notificationRepo->update($notificationDetail, $validatedData);
            DB::commit();
            return redirect()->back()->with('success', 'Notification Sent Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getNotificationForNavBar(): JsonResponse|RedirectResponse
    {
        try {
            $select = ['notification_publish_date', 'title'];
            $notification = $this->notificationRepo->getNotificationForNavBar($select);
            $navNotifications = [];
            foreach ($notification as $key => $value) {
                $data = [];
                $data['publish_date'] = \Carbon\Carbon::parse($value->notification_publish_date)->diffForhumans();
                $data['title'] = ucfirst($value->title);
                $navNotifications[] = $data;
            }
            return response()->json([
                'data' => $navNotifications,
            ]);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
