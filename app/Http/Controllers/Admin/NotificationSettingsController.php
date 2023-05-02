<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Requests\MassDestroyNotificationRequest;
use App\Http\Requests\StoreNotificationDetailRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use App\Services\EmailService;
use Gate;
use Auth;
use DB;
use Config;
use Str;

class NotificationSettingsController extends Controller
{

    protected $emailService;
        public function __construct()
      {
          $this->emailService = new EmailService;
      }

    public function index(Request $request)
    {
        abort_if(Gate::denies('notification_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Notification::query()->select(sprintf('%s.*', (new Notification())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', ' ');
            $table->addColumn('actions', ' ');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'notification_show';
                $editGate = 'notification_edit';
                $deleteGate = 'notification_delete';
                $crudRoutePart = 'notifications';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });  
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('category', function ($row) {
                return Notification::Category[$row->category];
            });
            // $table->editColumn('options', function ($row) {
            //     return Notification::Options[$row->options];
            // });
            $table->editColumn('status', function ($row) {
                return ( $row->status || $row->status == 0 ) ? Notification::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder', ]);

            return $table->make(true);
        }
        $data = [];
        $data['notifications'] = Notification::get();
        $data['category'] = Notification::Category;
        $list['options'] = Notification::Options;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.settings.notifications')]];
        return view('admin.notifications.index', compact('data','breadcrumbs'));
    }

    public function create(Request $request){
        abort_if(Gate::denies('notification_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');   
        $list = $data =[];
        $type = 'Add';
        $list['categories'] = Notification::Category;
        $list['options'] = Notification::Options;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.notifications.index'),'name' => trans('cruds.notifications.title')], ['name' => trans('locale.Add')." ".trans('cruds.notifications.title_singular')]];
        return view('admin.notifications.createupdate',compact('list','breadcrumbs','type','data'));

    }

    public function store(StoreNotificationRequest $request){
        try {
            $params = collect($request->all());
            $notifications = new Notification;
            $notifications->title = $params['title'];
            $notifications->description = $params['description'];
            $notifications->category = (string) $params['category'];
            $notifications->options = (string) $params['options'];
            $notifications->status = (int) $params['status'];
            $notifications->email_subject = $params['email_subject'];
            $notifications->email = $params['email'];
            $notifications->email_template = $params['email_template'];
            $notifications->sms = $params['sms'];
            $notifications->sms_template = $params['sms_template'];
            $notifications->variable_description = $params['variable_description'];
            $notifications->save(); 
            $notification_id = $notifications->id;

            $this->dynamicfilecreate($notifications->title,$notifications->email_template,false);

            $url = route('admin.notifications.edit', ['notification' => $notification_id]);
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.NOTIFICATION_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.NOTIFICATION_ADDED_SUCCESSFULLY.msg'),
                ['url'=>$url]
                );
            }
            catch (\Exception $e) {
                return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
            }
    }

    public function edit(Request $request, $id){
        abort_if(Gate::denies('notification_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = []; 
        $type = 'Edit';
        $data['notifications'] = Notification::where('id',$id)->first();
        $list['categories'] = Notification::Category;
        $list['options'] = Notification::Options;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.notifications.index'),'name' => trans('cruds.notifications.title')], ['name' => trans('locale.Edit')." ".trans('cruds.notifications.title_singular')]];
        return view('admin.notifications.createupdate', compact('list','data','type','breadcrumbs'));

    }

    public function update(UpdateNotificationRequest $request, $id)
    {
        try {
                $params = collect($request->all());
                $objNotifications = Notification::where('id',$id)->first();
                if(empty($objNotifications)){
                    $objNotifications = new Notification;
                }
                    $objNotifications->title = $params['title'];
                    $objNotifications->description = $params['description'];
                    $objNotifications->category = (string) $params['category'];
                    $objNotifications->options = (string) $params['options'];
                    $objNotifications->status = (int) $params['status'];
                    $objNotifications->email = $params['email'];
                    $objNotifications->email_subject = $params['email_subject'];
                    $objNotifications->email_template = $params['email_template'];
                    $objNotifications->sms = $params['sms'];
                    $objNotifications->sms_template = $params['sms_template'];
                    $objNotifications->variable_description = $params['variable_description'];
                    $objNotifications->save(); 

                     $this->dynamicfilecreate($objNotifications->title,$objNotifications->email_template,false);

                    $url = route('admin.notifications.index');
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.NOTIFICATION_UPDATE_SUCCESSFULLY.code'),
                        __('constants.messages.NOTIFICATION_UPDATE_SUCCESSFULLY.msg'),
                        ['url'=>$url]
                    );
            }
        catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function destroy(Notification $notifications, $id){
        abort_if(Gate::denies('notification_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');  
        try {
            Notification::where('id',$id)->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.NOTIFICATION_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.NOTIFICATION_DELETE_SUCCESSFULLY.msg'),
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function massDestroy(MassDestroyNotificationRequest $request)
    {
        try {
            Notification::whereIn('id', request('ids'))->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.NOTIFICATION_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.NOTIFICATION_DELETE_SUCCESSFULLY.msg'),
            );
        } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
     public function notificationDetails(Request $request)
    {
        abort_if(Gate::denies('notification_settings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $list = $data = [];
        $data['notifications'] = Notification::get();
        $list['categories'] = Notification::Category;
        $list['options'] = Notification::Options;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.notifications.notification_details')]];

        return view('admin.settings.notifications.index', compact('data','list','breadcrumbs'));
    }

    public function notificationDetailSave(StoreNotificationDetailRequest $request){
        try {
            $params = collect($request->all());
            $authUser = Auth::user();
            $objNotificationUser = NotificationUser::where('notifications_id',$params['notifications_id'])->first();
            $objNotification =  Notification::where('id',$params['notifications_id'])->first();
            if(empty($objNotificationUser)){
                $objNotificationUser = new NotificationUser;
            }
            $objNotificationUser->user_id = $authUser->id;
            $objNotificationUser->notifications_id = $params['notifications_id'];
            $objNotificationUser->email_subject = $params['email_subject'];
            $objNotificationUser->email_template = $params['email_template'];
            $objNotificationUser->sms_template = $params['sms_template'];
            $objNotificationUser->save();
            $objNotification->email = ($params['email'])?1:0;
            $objNotification->sms = ($params['sms'])?1:0;
            $objNotification->save();

            $this->dynamicfilecreate($objNotificationUser->email_subject,$objNotificationUser->email_template,true);
            $url = url("admin/settings/notificationdetails");
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.USER_NOTIFICATION_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.USER_NOTIFICATION_SAVED_SUCCESSFULLY.msg'),
                ['url'=>$url]
            );
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }

    }


    
    public function notificationDetailTemplate($id){
        abort_if(Gate::denies('notification_template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $authUser = Auth::user();
        $data = [];
        $objNotificationDetail = Notification::where('id',$id)->first();
        $data['notificationId'] = $id;
        $data['variable_description'] = $objNotificationDetail->variable_description;
        $data['notificationDetail'] = NotificationUser::where(['notifications_id'=>$id,'user_id'=>$authUser->id])->first();

        if(empty($data['notificationDetail'])){
            $data['notificationId'] = $id;
            $data['notificationDetail'] = $objNotificationDetail;
        }
        $data['notification'] = $objNotificationDetail;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.notifications.notification_template')]];
        return view('admin.settings.notifications.createupdatetemplate',compact('data','breadcrumbs'));
    }

    public function revertToDefault(Request $request){
        try{
            $authUser = Auth::user();
            $params = collect($request->all());
            $notificationDetail = Notification::where('id',$params['notifications_id'])->first();
            $objNotificationUserDetail = NotificationUser::where(['notifications_id'=>$params['notifications_id'],'user_id'=>$authUser->id])->first();
            if(!empty($objNotificationUserDetail)){
                if($params['statusType'] == 'email'){
                    $objNotificationUserDetail->email_subject = $notificationDetail->email_subject;
                    $objNotificationUserDetail->email_template = $notificationDetail->email_template;
                    $objNotificationUserDetail->save();
                }
                elseif ($params['statusType'] == 'sms'){
                    $objNotificationUserDetail->sms_template = $notificationDetail->sms_template;
                    $objNotificationUserDetail->save();
                }
            }
            else{
                $objNotificationUserDetail = new NotificationUser;
;                $objNotificationUserDetail->email_subject = $request->email_subject;
;                $objNotificationUserDetail->email_template = $request->email_template;
                $objNotificationUserDetail->sms_template = $request->sms_template;
                $objNotificationUserDetail->save(); 
            }
             $this->dynamicfilecreate($objNotificationUserDetail->email_subject,$objNotificationUserDetail->email_template,true);

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.NOTIFICATION_REVERT_TO_DEFAULT_SUCCESSFULLY.code'),
                __('constants.messages.NOTIFICATION_REVERT_TO_DEFAULT_SUCCESSFULLY.msg'),
            );
        }
        catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

     public function sendorderemail($user_id)
    {
        $data = [];
        $data['email'] = "maitri@gmail.com";
        // $this->emailService->orderConfirmation($data);
        $this->emailService->orderEdited($data);
        dd("mail sent");
        
    }

    public function dynamicfilecreate($templatename,$templatedetails,$statusUserId){

            $templatename = Str::slug($templatename , "_");
            $dynamicTemplateName  = $templatename;
            if($statusUserId)
            {
                $dynamicTemplateName = Config::get('client_id');
            }
          
            $notificationsfolderPath = Notification::folderPath;
            $this->checkViewFolder($notificationsfolderPath);

            $folderPath = "$notificationsfolderPath/$templatename";
            $this->checkViewFolder($folderPath);
            $myfile = fopen(resource_path($folderPath."/".$dynamicTemplateName.".blade.php"), "w");
            $txtnew = $templatedetails;
            fwrite($myfile, $txtnew);
            fclose($myfile);
    }


}