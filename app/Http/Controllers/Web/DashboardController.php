<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use App\Services\Client\ClientService;
use App\Services\Project\ProjectService;
use App\Services\Task\TaskService;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardRepository $dashboardRepo;
    private ClientService $clientService;
    private TaskService $taskService;
    private ProjectService $projectService;

    public function __construct(DashboardRepository $dashboardRepo,
                                ClientService       $clientService,
                                TaskService         $taskService,
                                ProjectService      $projectService
    )
    {
        $this->dashboardRepo = $dashboardRepo;
        $this->clientService = $clientService;
        $this->projectService = $projectService;
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        try {
            $appTimeSetting = AppHelper::check24HoursTimeAppSetting();

            $projectSelect = ['id','name','start_date','deadline','status','priority'];
            $withProject = [
                'projectLeaders.user:id,name,avatar',
                'tasks:id,project_id',
                'completedTask:id,project_id'
            ];
            $companyId = AppHelper::getAuthUserCompanyId();
            
            if (!$companyId) {
                throw new Exception('Company Detail Not Found');
            }
            $date = AppHelper::yearDetailToFilterData();
            $dashboardDetail = $this->dashboardRepo->getCompanyDashboardDetail($companyId, $date);
            $topClients = $this->clientService->getTopClientsOfCompany();
            $taskPieChartData = $this->taskService->getTaskDataForPieChart();
            $projectCardDetail = $this->projectService->getProjectCardData();
            $recentProjects = $this->projectService->getRecentProjectListsForDashboard($projectSelect,$withProject);
            return view('admin.dashboard', compact(
                'dashboardDetail',
                'topClients',
                'taskPieChartData',
                'projectCardDetail',
                'recentProjects',
                'appTimeSetting'
                )
            );
        } catch (Exception $exception) {
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage());
        }
    }


}
