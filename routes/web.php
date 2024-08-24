<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Farming\CuttingOrderController;
use App\Http\Controllers\Farming\FarmerLoanController;
use App\Http\Controllers\Farming\FarmingController;
use App\Http\Controllers\Farming\FarmingDetailController;
use App\Http\Controllers\Farming\FarmingPaymentController;
use App\Http\Controllers\Farming\GuarantorController;
use App\Http\Controllers\Farming\SeedCategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseTransferController;
use App\Http\Controllers\ProductServiceCategoryController;
use App\Http\Controllers\ProductServiceController;
use App\Http\Controllers\ProductServiceUnitController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\Web\AppSettingController;
use App\Http\Controllers\Web\AssetController;
use App\Http\Controllers\Web\AssetTypeController;
use App\Http\Controllers\Web\AttachmentController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\BranchController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DataExportController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\EmployeeLogOutRequestController;
use App\Http\Controllers\Web\EmployeeSalaryController;
use App\Http\Controllers\Web\FeatureController;
use App\Http\Controllers\Web\GeneralSettingController;
use App\Http\Controllers\Web\HolidayController;
use App\Http\Controllers\Web\LeaveController;
use App\Http\Controllers\Web\LeaveTypeController;
use App\Http\Controllers\Web\NFCController;
use App\Http\Controllers\Web\NoticeController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\OfficeTimeController;
use App\Http\Controllers\Web\OverTimeSettingController;
use App\Http\Controllers\Web\PaymentCurrencyController;
use App\Http\Controllers\Web\PaymentMethodController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\PrivacyPolicyController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\QrCodeController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\RouterController;
use App\Http\Controllers\Web\AdvanceSalaryController;
use App\Http\Controllers\Web\AssetAssignmentController;
use App\Http\Controllers\Web\RegularizationController;
use App\Http\Controllers\Web\SalaryComponentController;
use App\Http\Controllers\Web\SalaryGroupController;
use App\Http\Controllers\Web\SalaryHeadController;
use App\Http\Controllers\Web\SalaryHistoryController;
use App\Http\Controllers\Web\SalaryTDSController;
use App\Http\Controllers\Web\StaticPageContentController;
use App\Http\Controllers\Web\SupportController;
use App\Http\Controllers\Web\TadaAttachmentController;
use App\Http\Controllers\Web\TadaController;
use App\Http\Controllers\Web\TaskChecklistController;
use App\Http\Controllers\Web\TaskCommentController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\TeamMeetingController;
use App\Http\Controllers\Web\TimeLeaveController;
use App\Http\Controllers\Web\UnderTimeSettingController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false,
    'login' => false,
    'logout' => false
]);

Route::get('/', function () {
    return redirect()->route('admin.login');
});

/** app privacy policy route */
Route::get('privacy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy');

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['web']
], function () {
    Route::get('login', [AdminAuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.process');

    Route::group(['middleware' => ['admin.auth', 'permission']], function () {

        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /** User route */
        Route::resource('users', UserController::class);
        Route::get('users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users/export/forms', [UserController::class, 'exportForm'])->name('users.exportForm');

        Route::get('employees/import-csv', [UserController::class, 'employeeImport'])->name('employees.import-csv.show');
        Route::post('employees/import-csv', [UserController::class, 'importEmployee'])->name('employees.import-excel.store');

        Route::get('users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
        Route::get('users/change-workspace/{id}', [UserController::class, 'changeWorkSpace'])->name('users.change-workspace');
        Route::get('users/get-company-employee/{branchId}', [UserController::class, 'getAllCompanyEmployeeDetail'])->name('users.getAllCompanyUsers');
        Route::post('users/change-password/{userId}', [UserController::class, 'changePassword'])->name('users.change-password');
        Route::get('users/force-logout/{userId}', [UserController::class, 'forceLogOutEmployee'])->name('users.force-logout');

        /** company route */
        Route::resource('company', CompanyController::class);

        /** branch route */
        Route::resource('branch', BranchController::class);
        Route::get('branch/toggle-status/{id}', [BranchController::class, 'toggleStatus'])->name('branch.toggle-status');
        Route::get('branch/delete/{id}', [BranchController::class, 'delete'])->name('branch.delete');


        /** Department route */
        Route::resource('departments', DepartmentController::class);
        Route::get('departments/toggle-status/{id}', [DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');
        Route::get('departments/delete/{id}', [DepartmentController::class, 'delete'])->name('departments.delete');
        Route::get('departments/get-All-Departments/{branchId}', [DepartmentController::class, 'getAllDepartmentsByBranchId'])->name('departments.getAllDepartmentsByBranchId');


        /** post route */
        Route::resource('posts', PostController::class);
        Route::get('posts/toggle-status/{id}', [PostController::class, 'toggleStatus'])->name('posts.toggle-status');
        Route::get('posts/delete/{id}', [PostController::class, 'delete'])->name('posts.delete');
        Route::get('posts/get-All-posts/{deptId}', [PostController::class, 'getAllPostsByBranchId'])->name('posts.getAllPostsByBranchId');

        /** roles & permissions route */
        Route::resource('roles', RoleController::class);
        Route::get('roles/toggle-status/{id}', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
        Route::get('roles/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
        Route::get('roles/permissions/{roleId}', [RoleController::class, 'createPermission'])->name('roles.permission');
        Route::put('roles/assign-permissions/{roleId}', [RoleController::class, 'assignPermissionToRole'])->name('role.assign-permissions');

        /** office_time route */
        Route::resource('office-times', OfficeTimeController::class);
        Route::get('office-times/toggle-status/{id}', [OfficeTimeController::class, 'toggleStatus'])->name('office-times.toggle-status');
        Route::get('office-times/delete/{id}', [OfficeTimeController::class, 'delete'])->name('office-times.delete');

        /** branch_router route */
        Route::resource('routers', RouterController::class);
        Route::get('routers/toggle-status/{id}', [RouterController::class, 'toggleStatus'])->name('routers.toggle-status');
        Route::get('routers/delete/{id}', [RouterController::class, 'delete'])->name('routers.delete');

        /** holiday route */
        Route::get('holidays/import-csv', [HolidayController::class, 'holidayImport'])->name('holidays.import-csv.show');
        Route::post('holidays/import-csv', [HolidayController::class, 'importHolidays'])->name('holidays.import-csv.store');
        Route::resource('holidays', HolidayController::class);
        Route::get('holidays/toggle-status/{id}', [HolidayController::class, 'toggleStatus'])->name('holidays.toggle-status');
        Route::get('holidays/delete/{id}', [HolidayController::class, 'delete'])->name('holidays.delete');

        /** app settings */
        Route::get('app-settings/index', [AppSettingController::class, 'index'])->name('app-settings.index');
        Route::get('app-settings/toggle-status/{id}', [AppSettingController::class, 'toggleStatus'])->name('app-settings.toggle-status');
        Route::get('app-settings/changeTheme', [AppSettingController::class, 'changeTheme'])->name('app-settings.change-theme');

        /** General settings */
        Route::resource('general-settings', GeneralSettingController::class);
        Route::get('general-settings/delete/{id}', [GeneralSettingController::class, 'delete'])->name('general-settings.delete');

        /** Leave route */
        Route::resource('leaves', LeaveTypeController::class);
        Route::get('leaves/toggle-status/{id}', [LeaveTypeController::class, 'toggleStatus'])->name('leaves.toggle-status');
        Route::get('leaves/toggle-early-exit/{id}', [LeaveTypeController::class, 'toggleEarlyExit'])->name('leaves.toggle-early-exit');
        Route::get('leaves/delete/{id}', [LeaveTypeController::class, 'delete'])->name('leaves.delete');
        Route::get('leaves/get-leave-types/{earlyExitStatus}', [LeaveTypeController::class, 'getLeaveTypesBasedOnEarlyExitStatus']);

        /** Company Content Management route */
        Route::resource('static-page-contents', StaticPageContentController::class);
        Route::get('static-page-contents/toggle-status/{id}', [StaticPageContentController::class, 'toggleStatus'])->name('static-page-contents.toggle-status');
        Route::get('static-page-contents/delete/{id}', [StaticPageContentController::class, 'delete'])->name('static-page-contents.delete');

        /** Notification route */
        Route::get('notifications/get-nav-notification', [NotificationController::class, 'getNotificationForNavBar'])->name('nav-notifications');
        Route::resource('notifications', NotificationController::class);
        Route::get('notifications/toggle-status/{id}', [NotificationController::class, 'toggleStatus'])->name('notifications.toggle-status');
        Route::get('notifications/delete/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
        Route::get('notifications/send-notification/{id}', [NotificationController::class, 'sendNotificationToAllCompanyUser'])->name('notifications.send-notification');

        /** Attendance route */
        Route::resource('attendances', AttendanceController::class);
        Route::get('employees/attendance/check-in/{companyId}/{userId}', [AttendanceController::class, 'checkInEmployee'])->name('employees.check-in');
        Route::get('employees/attendance/check-out/{companyId}/{userId}', [AttendanceController::class, 'checkOutEmployee'])->name('employees.check-out');
        Route::get('employees/attendance/delete/{id}', [AttendanceController::class, 'delete'])->name('attendance.delete');
        Route::get('employees/attendance/change-status/{id}', [AttendanceController::class, 'changeAttendanceStatus'])->name('attendances.change-status');
        Route::get('employees/attendance/{type}', [AttendanceController::class, 'dashboardAttendance'])->name('dashboard.takeAttendance');

        //Attendance Regularization
        Route::resource('regularization', RegularizationController::class);
        Route::get('regularization/approve/{id}', [RegularizationController::class, 'approveRegularization'])->name('regularization.approveRegularization');
        Route::get('regularization/reject/{id}', [RegularizationController::class, 'rejectRegularization'])->name('regularization.rejectRegularization');
        Route::post('ajax-regularization', [RegularizationController::class, 'checkAttendance'])->name('ajaxRegularizationModal');
        Route::post('create-regularization', [RegularizationController::class, 'createRegularization'])->name('createAjaxRegularization');

        /** Leave route */
        Route::get('employees/leave-request', [LeaveController::class, 'index'])->name('leave-request.index');
        Route::post('leave-request/store', [LeaveController::class, 'storeLeaveRequest'])->name('employee-leave-request.store');
        Route::get('employees/leave-request/show/{leaveId}', [LeaveController::class, 'show'])->name('leave-request.show');
        Route::put('employees/leave-request/status-update/{leaveRequestId}', [LeaveController::class, 'updateLeaveRequestStatus'])->name('leave-request.update-status');
        Route::get('leave-request/create', [LeaveController::class, 'createLeaveRequest'])->name('leave-request.create');
        Route::get('employees/leave-request/add', [LeaveController::class, 'addLeaveRequest'])->name('leave-request.add');
        Route::post('employees/leave-request/add', [LeaveController::class, 'saveLeaveRequest'])->name('leave-request.save');

        /** Time Leave Route */
        Route::get('employees/time-leave-request', [TimeLeaveController::class, 'index'])->name('time-leave-request.index');
        Route::put('employees/time-leave-request/status-update/{leaveRequestId}', [TimeLeaveController::class, 'updateLeaveRequestStatus'])->name('time-leave-request.update-status');
        Route::get('employees/time-leave-request/show/{leaveId}', [TimeLeaveController::class, 'show'])->name('time-leave-request.show');
        Route::get('employees/time-leave-request/create', [TimeLeaveController::class, 'createLeaveRequest'])->name('time-leave-request.create');
        Route::post('employees/time-leave-request/store', [TimeLeaveController::class, 'storeLeaveRequest'])->name('time-leave-request.store');


        /**logout request Routes */
        Route::get('employee/logout-requests', [EmployeeLogOutRequestController::class, 'getAllCompanyEmployeeLogOutRequest'])->name('logout-requests.index');
        Route::get('employee/logout-requests/toggle-status/{employeeId}', [EmployeeLogOutRequestController::class, 'acceptLogoutRequest'])->name('logout-requests.accept');

        /** Notice route */
        Route::resource('notices', NoticeController::class);
        Route::get('notices/toggle-status/{id}', [NoticeController::class, 'toggleStatus'])->name('notices.toggle-status');
        Route::get('notices/delete/{id}', [NoticeController::class, 'delete'])->name('notices.delete');
        Route::get('notices/send-notice/{id}', [NoticeController::class, 'sendNotice'])->name('notices.send-notice');

        /** Team Meeting route */
        Route::resource('team-meetings', TeamMeetingController::class);
        Route::get('team-meetings/delete/{id}', [TeamMeetingController::class, 'delete'])->name('team-meetings.delete');
        Route::get('team-meetings/remove-image/{id}', [TeamMeetingController::class, 'removeImage'])->name('team-meetings.remove-image');

        /** Clients route */
        Route::post('clients/ajax/store', [ClientController::class, 'ajaxClientStore'])->name('clients.ajax-store');
        Route::resource('clients', ClientController::class);
        Route::get('clients/delete/{id}', [ClientController::class, 'delete'])->name('clients.delete');
        Route::get('clients/toggle-status/{id}', [ClientController::class, 'toggleIsActiveStatus'])->name('clients.toggle-status');

        /** Project Management route */
        Route::resource('projects', ProjectController::class);
        Route::get('projects/delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');
        Route::get('projects/toggle-status/{id}', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
        Route::get('projects/get-assigned-members/{projectId}', [ProjectController::class, 'getProjectAssignedMembersByProjectId'])->name('projects.get-assigned-members');
        Route::get('projects/get-employees-to-add/{addEmployeeType}/{projectId}', [ProjectController::class, 'getEmployeesToAddTpProject'])->name('projects.add-employee');
        Route::post('projects/update-leaders', [ProjectController::class, 'updateLeaderToProject'])->name('projects.update-leader-data');
        Route::post('projects/update-members', [ProjectController::class, 'updateMemberToProject'])->name('projects.update-member-data');

        /** Project & Task Attachment route */
        Route::get('projects/attachment/create/{projectId}', [AttachmentController::class, 'createProjectAttachment'])->name('project-attachment.create');
        Route::post('projects/attachment/store', [AttachmentController::class, 'storeProjectAttachment'])->name('project-attachment.store');
        Route::get('tasks/attachment/create/{taskId}', [AttachmentController::class, 'createTaskAttachment'])->name('task-attachment.create');
        Route::post('tasks/attachment/store', [AttachmentController::class, 'storeTaskAttachment'])->name('task-attachment.store');
        Route::get('attachment/delete/{id}', [AttachmentController::class, 'deleteAttachmentById'])->name('attachment.delete');


        /** Task Management route */
        Route::resource('tasks', TaskController::class);
        Route::get('projects/task/create/{projectId}', [TaskController::class, 'createTaskFromProjectPage'])->name('project-task.create');
        Route::get('tasks/delete/{id}', [TaskController::class, 'delete'])->name('tasks.delete');
        Route::get('tasks/toggle-status/{id}', [TaskController::class, 'toggleStatus'])->name('tasks.toggle-status');

        /** Task Checklist route */
        Route::post('task-checklists/save', [TaskChecklistController::class, 'store'])->name('task-checklists.store');
        Route::get('task-checklists/edit/{id}', [TaskChecklistController::class, 'edit'])->name('task-checklists.edit');
        Route::put('task-checklists/update/{id}', [TaskChecklistController::class, 'update'])->name('task-checklists.update');
        Route::get('task-checklists/delete/{id}', [TaskChecklistController::class, 'delete'])->name('task-checklists.delete');
        Route::get('task-checklists/toggle-status/{id}', [TaskChecklistController::class, 'toggleIsCompletedStatus'])->name('task-checklists.toggle-status');

        /** Task Comments  route */
        Route::post('task-comment/store', [TaskCommentController::class, 'saveCommentDetail'])->name('task-comment.store');
        Route::get('task-comment/delete/{commentId}', [TaskCommentController::class, 'deleteComment'])->name('comment.delete');
        Route::get('task-comment/reply/delete/{replyId}', [TaskCommentController::class, 'deleteReply'])->name('reply.delete');

        /** Support route */
        Route::get('supports/get-all-query', [SupportController::class, 'getAllQueriesPaginated'])->name('supports.index');
        Route::get('supports/change-seen-status/{queryId}', [SupportController::class, 'changeIsSeenStatus'])->name('supports.changeSeenStatus');
        Route::put('supports/update-status/{id}', [SupportController::class, 'changeQueryStatus'])->name('supports.updateStatus');
        Route::get('supports/delete/{id}', [SupportController::class, 'delete'])->name('supports.delete');

        /** Tada route */
        Route::put('tadas/update-status/{id}', [TadaController::class, 'changeTadaStatus'])->name('tadas.update-status');
        Route::resource('tadas', TadaController::class);
        Route::get('tadas/delete/{id}', [TadaController::class, 'delete'])->name('tadas.delete');
        Route::get('tadas/toggle-active-status/{id}', [TadaController::class, 'toggleTadaIsActive'])->name('tadas.toggle-status');

        /** Tada Attachment route */
        Route::get('tadas/attachment/create/{tadaId}', [TadaAttachmentController::class, 'create'])->name('tadas.attachment.create');
        Route::post('tadas/attachment/store', [TadaAttachmentController::class, 'store'])->name('tadas.attachment.store');
        Route::get('tadas/attachment/delete/{id}', [TadaAttachmentController::class, 'delete'])->name('tadas.attachment-delete');

        /** Export data route */
        Route::get('leave-types-export', [DataExportController::class, 'exportLeaveType'])->name('leave-type-export');
        Route::get('leave-requests-export', [DataExportController::class, 'exportEmployeeLeaveRequestLists'])->name('leave-request-export');
        Route::get('employee-detail-export', [DataExportController::class, 'exportEmployeeDetail'])->name('employee-lists-export');
        Route::get('attendance-detail-export', [DataExportController::class, 'exportAttendanceDetail'])->name('attendance-lists-export');

        /** Asset Management route */
        Route::resource('asset-types', AssetTypeController::class, [
            'except' => ['destroy']
        ]);
        Route::get('asset-types/delete/{id}', [AssetTypeController::class, 'delete'])->name('asset-types.delete');
        Route::get('asset-types/toggle-status/{id}', [AssetTypeController::class, 'toggleIsActiveStatus'])->name('asset-types.toggle-status');

        Route::resource('assets', AssetController::class, [
            'except' => ['destroy']
        ]);
        Route::resource('asset_assignment', AssetAssignmentController::class, [
            'except' => ['destroy']
        ]);
        Route::get('assets/delete/{id}', [AssetController::class, 'delete'])->name('assets.delete');
        Route::get('assets/toggle-status/{id}', [AssetController::class, 'changeAvailabilityStatus'])->name('assets.change-Availability-status');

        Route::get('asset-assignments/get-All-Assets/{assetType_id}', [AssetAssignmentController::class, 'getAllAssetsByAssetTypeId'])->name('assets.getAllAssetsByAssetTypeId');

        /** Salary Component route */
        Route::resource('salary-components', SalaryComponentController::class, [
            'except' => ['destroy', 'show']
        ]);
        Route::get('salary-components/delete/{id}', [SalaryComponentController::class, 'delete'])->name('salary-components.delete');
        Route::get('salary-components/change-status/{id}', [SalaryComponentController::class, 'toggleSalaryComponentStatus'])->name('salary-components.toggle-status');

        /** Payment Methods route */
        Route::resource('payment-methods', PaymentMethodController::class, [
            'except' => ['destroy', 'show', 'edit']
        ]);
        Route::get('payment-methods/delete/{id}', [PaymentMethodController::class, 'deletePaymentMethod'])->name('payment-methods.delete');
        Route::get('payment-methods/change-status/{id}', [PaymentMethodController::class, 'togglePaymentMethodStatus'])->name('payment-methods.toggle-status');

        /** Payment Currency route */
        Route::get('payment-currency', [PaymentCurrencyController::class, 'index'])->name('payment-currency.index');
        Route::post('payment-currency', [PaymentCurrencyController::class, 'updateOrSetPaymentCurrency'])->name('payment-currency.save');

        /** Salary TDS route */
        Route::resource('salary-tds', SalaryTDSController::class, [
            'except' => ['destroy', 'show']
        ]);
        Route::get('salary-tds/delete/{id}', [SalaryTDSController::class, 'deleteSalaryTDS'])->name('salary-tds.delete');
        Route::get('salary-tds/change-status/{id}', [SalaryTDSController::class, 'toggleSalaryTDSStatus'])->name('salary-tds.toggle-status');

        /** Salary Group route */
        Route::resource('salary-groups', SalaryGroupController::class, [
            'except' => ['destroy', 'show']
        ]);
        Route::get('salary-groups/delete/{id}', [SalaryGroupController::class, 'deleteSalaryGroup'])->name('salary-groups.delete');
        Route::get('salary-groups/change-status/{id}', [SalaryGroupController::class, 'toggleSalaryGroupStatus'])->name('salary-groups.toggle-status');

        /** Employee Salary route */
        Route::resource('employee-salaries', EmployeeSalaryController::class, [
            'except' => ['destroy', 'create', 'edit', 'update', 'store', 'show']
        ]);
        Route::get('employee-salaries/update-cycle/{employeeId}/{cycle}', [EmployeeSalaryController::class, 'changeSalaryCycle'])->name('employee-salaries.update-salary-cycle');
        Route::post('employee-salaries/payroll-create', [EmployeeSalaryController::class, 'payrollCreate'])->name('employee-salaries.payroll-create');
        Route::get('employee-salaries/payroll', [EmployeeSalaryController::class, 'payroll'])->name('employee-salary.payroll');
        Route::get('employee-salaries/payroll/{payslipId}', [EmployeeSalaryController::class, 'viewPayroll'])->name('employee-salary.payroll-detail');
        Route::get('employee-salaries/payroll/{payslipId}/print', [EmployeeSalaryController::class, 'printPayslip'])->name('employee-salary.payroll-print');
        Route::get('employee-salaries/payroll/{payslipId}/edit', [EmployeeSalaryController::class, 'editPayroll'])->name('employee-salary.payroll-edit');
        Route::put('employee-salaries/payroll/{payslipId}/update', [EmployeeSalaryController::class, 'updatePayroll'])->name('employee-salary.payroll-update');
        Route::delete('employee-salaries/payroll/{payslipId}/delete', [EmployeeSalaryController::class, 'deletePayroll'])->name('employee-salary.payroll-delete');

        Route::get('employee-salaries/salary/create/{employeeId}', [EmployeeSalaryController::class, 'createSalary'])->name('employee-salaries.add');
        Route::post('employee-salaries/salary/{employeeId}', [EmployeeSalaryController::class, 'saveSalary'])->name('employee-salaries.store-salary');
        Route::get('employee-salaries/salary/edit/{employeeId}', [EmployeeSalaryController::class, 'editSalary'])->name('employee-salaries.edit-salary');
        Route::put('employee-salaries/salary/{employeeId}', [EmployeeSalaryController::class, 'updateSalary'])->name('employee-salaries.update-salary');
        Route::get('employee-salaries/salary/{employeeId}', [EmployeeSalaryController::class, 'deleteSalary'])->name('employee-salaries.delete-salary');

        Route::put('employee-salaries/{payslipId}/make-payment', [EmployeeSalaryController::class, 'makePayment'])->name('employee-salaries.make_payment');

        /** get weeks list */
        Route::get('employee-salaries/getWeeks/{year}', [EmployeeSalaryController::class, 'getWeeks'])->name('employee-salaries.get-weeks');


        /** Employee Salary History route */
        Route::get('employee-salaries/salary-update/{accountId}', [SalaryHistoryController::class, 'create'])->name('employee-salaries.increase-salary');
        Route::post('employee-salaries/salary-history/store', [SalaryHistoryController::class, 'store'])->name('employee-salaries.updated-salary-store');
        Route::get('employee-salaries/salary-increment-history/{employeeId}', [SalaryHistoryController::class, 'getEmployeeAllSalaryHistory'])->name('employee-salaries.salary-revise-history.show');

        Route::get('advance-salaries/setting/', [AdvanceSalaryController::class, 'setting'])->name('advance-salaries.setting');

        /** Advance Salary route */
        Route::resource('advance-salaries', AdvanceSalaryController::class, [
            'except' => ['destroy', 'store', 'edit']
        ]);
        Route::get('advance-salaries/delete/{id}', [AdvanceSalaryController::class, 'delete'])->name('advance-salaries.delete');



        /** Payroll OverTime Setting route */

        Route::resource('overtime', OverTimeSettingController::class, [
            'except' => ['destroy']
        ]);
        Route::get('overtime/delete/{id}', [OverTimeSettingController::class, 'delete'])->name('overtime.delete');
        Route::get('overtime/change-status/{id}', [OverTimeSettingController::class, 'toggleOTStatus'])->name('overtime.toggle-status');


        /** Payroll UnderTime Setting route */
        Route::resource('under-time', UnderTimeSettingController::class, [
            'except' => ['destroy']
        ]);
        Route::get('under-time/delete/{id}', [UnderTimeSettingController::class, 'delete'])->name('under-time.delete');
        Route::get('under-time/change-status/{id}', [UnderTimeSettingController::class, 'toggleUTStatus'])->name('under-time.toggle-status');


        Route::resource('qr', QrCodeController::class, [
            'except' => ['destroy', 'show']
        ]);
        Route::get('qr/delete/{id}', [QrCodeController::class, 'delete'])->name('qr.destroy');
        Route::get('qr/print/{id}', [QrCodeController::class, 'print'])->name('qr.print');

        Route::get('/nfc', [NFCController::class, 'index'])->name('nfc.index');
        Route::get('/nfc/delete/{id}', [NFCController::class, 'delete'])->name('nfc.destroy');

        /** app settings */
        Route::get('feature/index', [FeatureController::class, 'index'])->name('feature.index');
        Route::get('feature/toggle-status/{id}', [FeatureController::class, 'toggleStatus'])->name('feature.toggle-status');

        /** delete employee leave type */
        Route::get('employees/leave_type/delete/{id}', [UserController::class, 'deleteEmployeeLeaveType'])->name('employee_leave_type.delete');


        /* Farmer management*/
        Route::group(
            [
                'prefix' => 'farmer',
                'as' => 'farmer.',
            ],
            function () {
                Route::post('location/get_states', [FarmingController::class, 'getStates'])->name('location.get_states');
                Route::post('location/get_districts', [FarmingController::class, 'getDistricts'])->name('location.get_districts');
                Route::post('location/get_blocks', [FarmingController::class, 'getBlocks'])->name('location.get_blocks');
                Route::post('location/get_gram_panchyats', [FarmingController::class, 'getGramPanchyats'])->name('location.get_gram_panchyats');
                Route::post('location/get_villages', [FarmingController::class, 'getVillages'])->name('location.get_villages');
                Route::post('location/get_centers', [FarmingController::class, 'getCenters'])->name('location.get_centers');
                Route::post('location/get_country_state', [FarmingController::class, 'get_country_state'])->name('location.get_country_state');

                Route::get('farming_registration/validate/{id}', [FarmingController::class, 'validateProfile'])->name('farming_registration.validate');
                Route::resource('farming_registration', FarmingController::class);
                Route::post('search_filter', [FarmingController::class, 'search'])->name('farming_registration.search_filter');
                Route::get('farming_registration/{id}/destroy', [FarmingController::class, 'destroy'])->name('farming_registration.destroy');
                Route::post('registration_id', [FarmingController::class, 'registration_id'])->name('registration_id');

                Route::resource('guarantor', GuarantorController::class);
                Route::get('guarantor/{id}/destroy', [GuarantorController::class, 'destroy'])->name('guarantor.destroy');

                Route::get('bank_guarantee', [FarmingPaymentController::class, 'bankGuarantee'])->name('bank_guarantee.index');
                Route::get('bank_guarantee/{id}', [FarmingPaymentController::class, 'editBankGuarantee'])->name('bank_guarantee.edit');
                Route::get('bank_guarantee/pdf/{id}', [FarmingPaymentController::class, 'pdfBankGuarantee'])->name('bank_guarantee.pdf');
                Route::resource('payment', FarmingPaymentController::class);
                Route::get('payment/{id}/destroy', [FarmingPaymentController::class, 'destroy'])->name('payment.destroy');
                Route::post('g_code', [FarmingPaymentController::class, 'g_code'])->name('g_code');

                Route::view('allotment', 'admin.farmer.allotment.index')->name('allotment.index');

                Route::post('get_product_service_by_category', [FarmerLoanController::class, 'getProductServiceByCategory'])->name('loan.get_product_service_by_category');
                Route::post('get_product_service_detail', [FarmerLoanController::class, 'getProductServiceDetail'])->name('loan.get_product_service_detail');
                Route::post('get_farming_detail', [FarmerLoanController::class, 'getFarmingDetail'])->name('loan.get_farming_detail');
                Route::resource('loan', FarmerLoanController::class);
                Route::get('loan/{id}/invoice', [FarmerLoanController::class, 'invoice_generate'])->name('loan.invoice_generate');
                Route::get('loan/{id}/destroy', [FarmerLoanController::class, 'destroy'])->name('loan.destroy');

                Route::get('reimbursement/create', [FarmingPaymentController::class, 'reimbursementCreate'])->name('reimbursement.create');
                Route::get('reimbursement', [FarmingPaymentController::class, 'reimbursement'])->name('reimbursement.index');
                Route::get('reimbursement/{id}/delete', [FarmingPaymentController::class, 'reimbursement_delete'])->name('reimbursement.delete');

                Route::resource('seed_category', SeedCategoryController::class);
                Route::get('seed_category/{id}/destroy', [SeedCategoryController::class, 'destroy'])->name('seed_category.destroy');

                Route::post('get_detail', [FarmingDetailController::class, 'getFarmingDetail'])->name('farming.get_detail');
                Route::resource('farming_detail', FarmingDetailController::class);
                Route::get('farming_detail/{id}/destroy', [FarmingDetailController::class, 'destroy'])->name('farming_detail.destroy');

                Route::post('update_cutting_order', [CuttingOrderController::class, 'updateCuttingOrder'])->name('farming.update_cutting_order');
                Route::resource('cutting_order', CuttingOrderController::class);
            }
        );

        //warehouse
        Route::resource('warehouse', WarehouseController::class);
        Route::post('warehouse/{id}/destroy', [WarehouseController::class, 'destroy'])->name('warehouse.destroy');

        //purchase
        Route::resource('purchase', PurchaseController::class);
        Route::post('purchase/items', [PurchaseController::class, 'items'])->name('purchase.items');
        Route::get('/bill/{id}/', 'PurchaseController@purchaseLink')->name('purchase.link.copy');
        Route::get('purchase/{id}/payment', [PurchaseController::class, 'payment'])->name('purchase.payment');
        Route::post('purchase/{id}/payment', [PurchaseController::class, 'createPayment'])->name('purchase.payment');
        Route::post('purchase/{id}/payment/{pid}/destroy', [PurchaseController::class, 'paymentDestroy'])->name('purchase.payment.destroy');
        Route::post('purchase/product/destroy', [PurchaseController::class, 'productDestroy'])->name('purchase.product.destroy');
        Route::post('purchase/vender', [PurchaseController::class, 'vender'])->name('purchase.vender');
        Route::post('purchase/product', [PurchaseController::class, 'product'])->name('purchase.product');
        Route::get('purchase/create/{cid}', [PurchaseController::class, 'create'])->name('purchase.create');
        Route::get('purchase/{id}/sent', [PurchaseController::class, 'sent'])->name('purchase.sent');
        Route::get('purchase/{id}/resent', [PurchaseController::class, 'resent'])->name('purchase.resent');

        //warehouse-transfer
        Route::resource('warehouse-transfer', WarehouseTransferController::class);
        Route::post('warehouse-transfer/getproduct', [WarehouseTransferController::class, 'getproduct'])->name('warehouse-transfer.getproduct');
        Route::post('warehouse-transfer/getquantity', [WarehouseTransferController::class, 'getquantity'])->name('warehouse-transfer.getquantity');

        //pos barcode
        Route::get('barcode/pos', [PosController::class, 'barcode'])->name('pos.barcode');
        Route::get('setting/pos', [PosController::class, 'setting'])->name('pos.setting');
        Route::post('barcode/settings', [PosController::class, 'BarcodesettingStore'])->name('barcode.setting');
        Route::get('print/pos', [PosController::class, 'printBarcode'])->name('pos.print');
        Route::post('pos/getproduct', [PosController::class, 'getproduct'])->name('pos.getproduct');
        Route::any('pos-receipt', [PosController::class, 'receipt'])->name('pos.receipt');
        Route::post('/cartdiscount', [PosController::class, 'cartdiscount'])->name('cartdiscount');

        Route::get('pos-print-setting', [SystemController::class, 'posPrintIndex'])->name('pos.print.setting');
        Route::get('purchase/preview/{template}/{color}', [PurchaseController::class, 'previewPurchase'])->name('purchase.preview');
        Route::get('pos/preview/{template}/{color}', [PosController::class, 'previewPos'])->name('pos.preview');

        Route::post('/purchase/template/setting', [PurchaseController::class, 'savePurchaseTemplateSettings'])->name('purchase.template.setting');
        Route::post('/pos/template/setting', [PosController::class, 'savePosTemplateSettings'])->name('pos.template.setting');

        Route::get('purchase/pdf/{id}', [PurchaseController::class, 'purchase'])->name('purchase.pdf');
        Route::get('pos/pdf/{id}', [PosController::class, 'pos'])->name('pos.pdf');
        Route::get('pos/data/store', [PosController::class, 'store'])->name('pos.data.store');
        //for pos print
        Route::get('printview/pos', [PosController::class, 'printView'])->name('pos.printview');

        Route::resource('pos', PosController::class);

        //bill
        Route::get('bill/{id}/duplicate', [BillController::class, 'duplicate'])->name('bill.duplicate');
        Route::get('bill/{id}/shipping/print', [BillController::class, 'shippingDisplay'])->name('bill.shipping.print');
        Route::get('bill/index', [BillController::class, 'index'])->name('bill.index');
        Route::post('bill/product/destroy', [BillController::class, 'productDestroy'])->name('bill.product.destroy');
        Route::post('bill/product', [BillController::class, 'product'])->name('bill.product');
        Route::post('bill/vender', [BillController::class, 'vender'])->name('bill.vender');
        Route::get('bill/{id}/sent', [BillController::class, 'sent'])->name('bill.sent');
        Route::get('bill/{id}/resent', [BillController::class, 'resent'])->name('bill.resent');
        Route::get('bill/{id}/payment', [BillController::class, 'payment'])->name('bill.payment');
        Route::post('bill/{id}/payment', [BillController::class, 'createPayment'])->name('bill.payment');
        Route::post('bill/{id}/payment/{pid}/destroy', [BillController::class, 'paymentDestroy'])->name('bill.payment.destroy');
        Route::get('bill/items', [BillController::class, 'items'])->name('bill.items');
        Route::resource('bill', BillController::class);
        Route::get('bill/create/{cid}', [BillController::class, 'create'])->name('bill.create');

        //Product Service
        Route::get('productservice/index', [ProductServiceController::class, 'index'])->name('productservice.index');
        Route::get('productservice/{id}/detail', [ProductServiceController::class, 'warehouseDetail'])->name('productservice.detail');
        Route::post('empty-cart', [ProductServiceController::class, 'emptyCart']);
        Route::post('warehouse-empty-cart', [ProductServiceController::class, 'warehouseemptyCart'])->name('warehouse-empty-cart');
        Route::resource('productservice', ProductServiceController::class);
        Route::get('productservice/{id}/destroy', [ProductServiceController::class, 'destroy'])->name('productservice.destroy');
        Route::resource('product-category', ProductServiceCategoryController::class);
        Route::post('product-category/getaccount', [ProductServiceCategoryController::class, 'getAccount'])->name('productServiceCategory.getaccount');
        Route::resource('product-unit', ProductServiceUnitController::class);
        Route::get('export/productservice', [ProductServiceController::class, 'export'])->name('productservice.export');
        Route::get('import/productservice/file', [ProductServiceController::class, 'importFile'])->name('productservice.file.import');
        Route::post('import/productservice', [ProductServiceController::class, 'import'])->name('productservice.import');
        Route::get('product-categories', [ProductServiceCategoryController::class, 'getProductCategories'])->name('product.categories');
        Route::get('add-to-cart/{id}/{session}', [ProductServiceController::class, 'addToCart']);
        Route::patch('update-cart', [ProductServiceController::class, 'updateCart']);
        Route::delete('remove-from-cart', [ProductServiceController::class, 'removeFromCart']);
        Route::get('name-search-products', [ProductServiceCategoryController::class, 'searchProductsByName'])->name('name.search.products');
        Route::get('search-products', [ProductServiceController::class, 'searchProducts'])->name('search.products');

        //Product Stock
        Route::resource('productstock', ProductStockController::class);
        Route::get('sample/download', [ProductStockController::class, 'sample_download'])->name('sample.download');
    });
});

Route::fallback(function () {
    return view('errors.404');
});
