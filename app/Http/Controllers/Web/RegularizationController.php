<?php

namespace App\Http\Controllers\Web;

use App\Exports\AttendanceDayWiseExport;
use App\Exports\AttendanceExport;
use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use App\Helpers\NepaliDate;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Repositories\BranchRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\RouterRepository;
use App\Repositories\UserRepository;
use App\Requests\Attendance\AttendanceTimeAddRequest;
use App\Requests\Attendance\AttendanceTimeEditRequest;
use App\Services\Attendance\AttendanceService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;

class RegularizationController extends Controller
{

    private $view = 'admin.attendance.';

    private CompanyRepository $companyRepo;
    private AttendanceService $attendanceService;
    private RouterRepository $routerRepo;
    private UserRepository $userRepository;
    private BranchRepository $branchRepo;
    private AttendanceController $attendanceController;


    public function __construct(CompanyRepository $companyRepo,
                                AttendanceService $attendanceService,
                                RouterRepository  $routerRepo,
                                UserRepository $userRepository,
                                BranchRepository $branchRepo,
                                AttendanceController $attendanceController,
    )
    {
        $this->attendanceService = $attendanceService;
        $this->companyRepo = $companyRepo;
        $this->routerRepo = $routerRepo;
        $this->userRepository =  $userRepository;
        $this->branchRepo =  $branchRepo;
        $this->attendanceController = $attendanceController;
    }   


    public function checkAttendance(Request $request){
        $date = $request->date;
        $user_id = auth()->user()->id;
        // dd($date);
        $attendance_data = Attendance::where('user_id', $user_id)->where('attendance_date', $date)->first();

        if ($attendance_data && $attendance_data->check_in_at != null  && $attendance_data->check_out_at != null) {
            return response()->json([
                'check_in' => $attendance_data ? $attendance_data->check_in_at : null,
                'check_out' => $attendance_data ? $attendance_data->check_out_at : null,
                'message' => "Already Checkdin"
            ]);
        } elseif ($attendance_data && $attendance_data->check_in_at != null && $attendance_data->check_out_at == null) {
            Log::info($attendance_data ? $attendance_data->check_in_at : null);

            return response()->json([
                'check_in' => $attendance_data ? $attendance_data->check_in_at : null,
                'check_out' =>  null,
                'message' => "Enter Checkout",
            ]);
        } else {
            Log::info($attendance_data);

            return response()->json([
                'check_in' => null,
                'message' => "You were absent",
            ]);
        }
    }

    public function createRegularization(Request $request){
        $this->authorize('attendance_create');
        $date = $request->date;
        $checkin_at = $request->checkin;
        $checkout_at = $request->checkout ? $request->checkout : null ;
        $user_id = auth()->user()->id;
        $companyId = auth()->user()->company_id ;

        try {
            $this->regularization($user_id, $companyId,$date,$checkin_at,$checkout_at);
            // return redirect()->back()->with('success', 'Employee Check In Successful');
            
            
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function regularization($userId,$companyId,$date,$checkin_at,$checkout_at,$dashboardAttendance=false,$locationData=[]){
        try{
            $select = ['name'];
            $permissionKeyForNotification = 'employee_check_in';
            $userDetail = $this->userRepository->findUserDetailById($userId);

            if(!$userDetail){
                throw new Exception('Employee Detail Not Found',404);
            }

            $validatedData = $this->attendanceController->prepareDataForAttendance($companyId, $userId,'checkIn');
            if($dashboardAttendance){
                $validatedData['check_in_latitude'] = $locationData['lat'];
                $validatedData['check_in_longitude'] = $locationData['long'];
            }
            DB::beginTransaction();

            $regularization_data =  $this->attendanceService->newRgularization($validatedData,$date,$checkin_at,$checkout_at);
            $this->userRepository->updateUserOnlineStatus($userDetail,1);
            DB::commit();

            return $regularization_data;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
