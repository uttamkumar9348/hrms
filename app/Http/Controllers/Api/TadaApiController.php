<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Requests\Tada\TadaRequest;
use App\Resources\Tada\TadaCollection;
use App\Resources\Tada\TadaDetailResource;
use App\Services\Tada\TadaAttachmentService;
use App\Services\Tada\TadaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TadaApiController extends Controller
{
    public TadaService $tadaService;
    public TadaAttachmentService $attachmentService;

    public function __construct(TadaService $tadaService,TadaAttachmentService $attachmentService)
    {
        $this->tadaService = $tadaService;
        $this->attachmentService = $attachmentService;
    }

    public function getEmployeesTadaLists(Request $request)
    {
        try {
            $select = ['id','title','employee_id','created_at','status','remark','total_expense'];
            $with = ['employeeDetail:id,name'];
            $tadaLists = $this->tadaService->getAllTadaDetailOfEmployee(getAuthUserCode(),$select,$with);
            $data = new TadaCollection($tadaLists);
            return AppHelper::sendSuccessResponse('Data Found',$data);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getEmployeesTadaDetail($tadaId)
    {
        try {
            $select= ['*'];
            $with = ['employeeDetail:id,name','attachments','verifiedBy:id,name'];
            $tadaDetail = $this->tadaService->findEmployeeTadaDetailByTadaId($tadaId,$with,$select);
            $detail = new TadaDetailResource($tadaDetail);
            return AppHelper::sendSuccessResponse('Data Found',$detail);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());

        }
    }

    public function storeTadaDetail(TadaRequest $request)
    {
        try {
            $permissionKeyForNotification = 'tada_alert';

            $validatedData = $request->validated();
            DB::beginTransaction();
                $tada = $this->tadaService->store($validatedData);
            DB::commit();
            AppHelper::sendNotificationToAuthorizedUser(
                'Tada Alert',
                auth()->user()->name.' has submitted a new TADA for '.$tada->title,
                $permissionKeyForNotification
            );
            return AppHelper::sendSuccessResponse('Data created successfully');
        }catch(Exception $e) {
            DB::rollBack();
            return AppHelper::sendErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function updateTadaDetail(TadaRequest $request)
    {
        try {

            $permissionKeyForNotification = 'tada_alert';

            $tadaId = $request->tada_id;
            $with = ['attachments'];
            $detail = $this->tadaService->findEmployeeTadaDetailByTadaId($tadaId,$with);
            if($detail['status'] != 'pending'){
                throw new Exception('You cannot update the detail once verified,Please contact administrator',403);
            }
            $validatedData = $request->validated();
            DB::beginTransaction();
                $this->tadaService->update($detail,$validatedData);
            DB::commit();
            AppHelper::sendNotificationToAuthorizedUser(
                'Tada Alert',
                auth()->user()->name.' has updated '.$detail->title. ' submitted TADA detail',
                $permissionKeyForNotification
            );
            return AppHelper::sendSuccessResponse('Data updated successfully');
        }catch (Exception $e) {
            DB::rollBack();
            return AppHelper::sendErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function deleteTadaAttachment($attachmentId)
    {
        try{
            $select = ['*'];
            $with = ['tada:id,status,employee_id'];
            $attachmentDetail = $this->attachmentService->findEmployeeTadaAttachmentDetail($attachmentId,$select,$with);
            if(!$attachmentDetail){
                throw new Exception('Attachment Detail Not Found',404);
            }
            if($attachmentDetail?->tada?->status !== 'pending'){
                throw new Exception('Cannot Delete Attachment Detail Once Verified',403);
            }
            if($attachmentDetail->total_attachments < 2 ){
                throw new Exception('Please upload another attachment before deleting.',400);
            }
            DB::beginTransaction();
            $this->attachmentService->delete($attachmentDetail);
            DB::commit();
            return AppHelper::sendSuccessResponse('Attachment Deleted successfully');
        }catch(Exception $exception){
            DB::rollBack();
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

}
