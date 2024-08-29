<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Models\TeamMeeting;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\Requests\TeamMeeting\TeamMeetingRequest;
use App\Services\TeamMeeting\TeamMeetingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamMeetingController extends Controller
{
    private $view = 'admin.teamMeeting.';

    private CompanyRepository $companyRepo;
    private UserRepository $userRepo;
    private TeamMeetingService $teamMeetingService;


    public function __construct(
        CompanyRepository  $companyRepo,
        UserRepository     $userRepo,
        TeamMeetingService $teamMeetingService
    ) {
        $this->companyRepo = $companyRepo;
        $this->userRepo = $userRepo;
        $this->teamMeetingService = $teamMeetingService;
    }

    public function index(Request $request)
    {
        if (\Auth::user()->can('manage-team_meeting')) {
            try {
                $filterParameters = [
                    'company_id' => AppHelper::getAuthUserCompanyId(),
                    'participator' => $request->participator ?? null,
                    'meeting_from' => $request->meeting_from ?? null,
                    'meeting_to' => $request->meeting_to ?? null,
                ];
                $select = ['*'];
                $with = ['teamMeetingParticipator'];
                $teamMeetings = $this->teamMeetingService->getAllCompanyTeamMeetings($filterParameters, $select, $with);
                return view($this->view . 'index', compact('teamMeetings', 'filterParameters'));
            } catch (Exception $exception) {
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create-team_meeting')) {
            try {
                $selectCompany = ['id', 'name'];
                $selectUser = ['id', 'name'];
                $companyDetail = $this->companyRepo->getCompanyDetail($selectCompany);
                $userDetail = $this->userRepo->getAllVerifiedEmployeeOfCompany($selectUser);
                return view(
                    $this->view . 'create',
                    compact('companyDetail', 'userDetail')
                );
            } catch (Exception $exception) {
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(TeamMeetingRequest $request)
    {
        if (\Auth::user()->can('create-team_meeting')) {
            try {
                $validatedData = $request->validated();
                DB::beginTransaction();
                $teamMeeting = $this->teamMeetingService->store($validatedData);
                DB::commit();
                if ($teamMeeting) {
                    $userIds = $this->getUserIdsForTeamMeetingNotification($validatedData['participator']);
                    $this->sendTeamMeetingNotification(
                        ucfirst($validatedData['title']),
                        'You are invited for team meeting at ' . ($validatedData['venue']) . ' on ' .
                            (date('M d Y', strtotime($validatedData['meeting_date'])) . ' at ' . AttendanceHelper::changeTimeFormatForAttendanceView($validatedData['meeting_start_time'])),
                        $userIds,
                        $teamMeeting->id
                    );
                }
                return redirect()
                    ->back()
                    ->with('success', 'Team Meeting created and sent Successfully');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('danger', $e->getMessage())->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show($id)
    {
        if (\Auth::user()->can('show-team_meeting')) {
            try {
                $select = ['*'];
                $teamMeeting = $this->teamMeetingService->findOrFailTeamMeetingDetailById($id, $select);

                $teamMeeting->title = ucfirst($teamMeeting->title);
                $teamMeeting->venue = ucfirst($teamMeeting->venue);
                $teamMeeting->meeting_date = AppHelper::formatDateForView($teamMeeting->meeting_date);
                $teamMeeting->image = $teamMeeting->image ? asset(TeamMeeting::UPLOAD_PATH . $teamMeeting->image) : '';
                $teamMeeting->time = AttendanceHelper::changeTimeFormatForAttendanceView($teamMeeting->meeting_start_time);
                $teamMeeting->description = removeHtmlTags($teamMeeting->description);
                $teamMeeting->meeting_published_at = convertDateTimeFormat($teamMeeting->meeting_published_at);
                $teamMeeting->creator = $teamMeeting->createdBy->name;

                return response()->json([
                    'data' => $teamMeeting,
                ]);
            } catch (Exception $exception) {
                return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-team_meeting')) {
            try {
                $with = ['teamMeetingParticipator'];
                $selectMeeting = ['*'];
                $selectCompany = ['id', 'name'];
                $selectUser = ['id', 'name'];
                $teamMeetingDetail = $this->teamMeetingService->findOrFailTeamMeetingDetailById($id, $selectMeeting, $with);
                if ((AppHelper::ifDateInBsEnabled())) {
                    $teamMeetingDetail->meeting_date =  AppHelper::dateInYmdFormatEngToNep($teamMeetingDetail->meeting_date);
                }
                $participatorIds = [];
                foreach ($teamMeetingDetail->teamMeetingParticipator as $key => $value) {
                    $participatorIds[] = $value->meeting_participator_id;
                }
                $companyDetail = $this->companyRepo->getCompanyDetail($selectCompany);
                $userDetail = $this->userRepo->getAllVerifiedEmployeeOfCompany($selectUser);
                return view($this->view . 'edit', compact('teamMeetingDetail', 'companyDetail', 'userDetail', 'participatorIds'));
            } catch (Exception $exception) {
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(TeamMeetingRequest $request, $id)
    {
        if (\Auth::user()->can('edit-team_meeting')) {
            try {
                $validatedData = $request->validated();
                $teamMeetingDetail = $this->teamMeetingService->findOrFailTeamMeetingDetailById($id);
                DB::beginTransaction();
                $updateTeamMeeting = $this->teamMeetingService->update($teamMeetingDetail, $validatedData);
                DB::commit();
                if ($updateTeamMeeting) {
                    $userIds = $this->getUserIdsForTeamMeetingNotification($validatedData['participator']);
                    $this->sendTeamMeetingNotification(
                        ucfirst($teamMeetingDetail->title),
                        'You are invited for team meeting at ' . ($teamMeetingDetail->venue) . ' on ' .
                            (date('M d Y', strtotime($teamMeetingDetail->meeting_date)) . ' at ' . AttendanceHelper::changeTimeFormatForAttendanceView($teamMeetingDetail->meeting_start_time)),
                        $userIds,
                        $updateTeamMeeting->id
                    );
                }
                return redirect()->back()->with('success', 'Team Meeting Updated and Sent Successfully');
            } catch (Exception $exception) {
                DB::rollBack();
                return redirect()->back()->with('danger', $exception->getMessage())
                    ->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function delete($id)
    {
        if (\Auth::user()->can('delete-team_meeting')) {
            try {
                DB::beginTransaction();
                $this->teamMeetingService->deleteTeamMeeting($id);
                DB::commit();
                return redirect()->back()->with('success', 'Team Meeting Detail Deleted  Successfully');
            } catch (Exception $exception) {
                DB::rollBack();
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function removeImage($id)
    {
        if (\Auth::user()->can('delete-team_meeting')) {
            try {
                DB::beginTransaction();
                $this->teamMeetingService->removeMeetingImage($id);
                DB::commit();
                return redirect()->back()->with('success', 'Image Deleted  Successfully');
            } catch (Exception $exception) {
                DB::rollBack();
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    private function getUserIdsForTeamMeetingNotification($validatedData): array
    {
        $userIds = [];
        foreach ($validatedData as $key => $value) {

            $userIds[] = $value['meeting_participator_id'];
        }
        return $userIds;
    }

    private function sendTeamMeetingNotification($title, $message, $userIds, $teamMeetingId)
    {
        SMPushHelper::sendNoticeNotification($title, $message, $userIds, true, $teamMeetingId);
    }
}
