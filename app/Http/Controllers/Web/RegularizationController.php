<?php

namespace App\Http\Controllers\Web;


use Exception;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Repositories\BranchRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\RouterRepository;
use App\Repositories\UserRepository;
use App\Services\Attendance\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                                AttendanceController $attendanceController
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
        $companyId = auth()->user()->company_id;
        // dd($companyId);


        try {
            $result = $this->regularization($user_id, $companyId,$date,$checkin_at,$checkout_at);
            if($result){
                return response()->json([
                    'message' => "Regularization Successfull"
                ]);
            }else{
                return response()->json([
                    'message' => null
                ]);
            }
            
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
            
            // dd($userDetail);
            $validatedData = $this->attendanceController->prepareDataForRegularization($companyId, $userId,'checkIn');
            // dd($validatedData);
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
