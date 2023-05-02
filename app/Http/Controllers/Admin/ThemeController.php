<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThemeRequest;
use App\Http\Requests\UpdateThemeRequest;
use App\Http\Requests\MassDestroyThemeRequest;
use App\Http\Requests\StoreThemeSelectRequest;
use App\Models\Theme;
use App\Models\ThemeMedium;
use App\Models\ThemeSelection;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Config;

class ThemeController extends Controller
{
    public function index(Request $request)
    {

        abort_if(Gate::denies('theme_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax())
        {
                $query = Theme::with(['theme_medias'])->select(sprintf('%s.*', (new Theme())->table));
                $table = Datatables::of($query);
                $table->addColumn('placeholder', '&nbsp;');
                $table->addColumn('actions', '&nbsp;');

                $table->editColumn('actions', function ($row) {
                $viewGate = 'theme_show';
                $editGate = 'theme_edit';
                $deleteGate = 'theme_delete';
                $crudRoutePart = 'themes';

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
                $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
             });
                $table->editColumn('themeurl', function ($row) {
                return $row->themeurl ? $row->themeurl : '';
             });
             //    $table->addColumn('image', function ($theme_medias) {
             //    return $row->theme_medias ? $row->theme_medias->image : '';
             // });
                $table->editColumn('image', function ($row) {
                if(!empty($row->theme_medias) && sizeof($row->theme_medias) > 0){
                    return $row->theme_medias[0]->image;
                }
                return ''; 
             });
                $table->rawColumns(['actions', 'placeholder', 'theme_medias']);
                return $table->make(true);
        }
        $themes = Theme::get();
        $theme_medias = ThemeMedium::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.themes.title_singular')." ".trans('global.listing') ]];
        return view('admin.themes.index',compact('themes','theme_medias','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('theme_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data =[];
        $type = 'Add';
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.themes.index'),'name' => trans('cruds.themes.title') ],['name' => trans('locale.Add')." ".trans('cruds.themes.title_singular') ]];
        return view('admin.themes.createupdate',compact('list','breadcrumbs','type','data'));
    }

    public function store(StoreThemeRequest $request)
    {
        try {
                $client_id = Config::get('client_id');
                $themes = Theme::create($request->except('images'));
                $theme_id = $themes->id;
                if (isset($request->images) && !empty($request->images))
                {
                    $path = "public/$client_id";
                    foreach($request->images as $imagefile)
                    {
                        $theme_id = $themes->id;
                        $this->checkFolder($path);
                        $this->checkFolder($path.'/themes');
                        $this->checkFolder($path .'/themes/'.$theme_id);
                        $refrence_id = mt_rand( 1000, 9999);
                        $imagename = time().$refrence_id.'.png';
                        $image = file_get_contents($imagefile['imageurl']);
                        Storage::disk('public')->put($client_id."/themes/$theme_id/$imagename", $image, 'public');
                    
                        ThemeMedium::create([
                            'theme_id' => $theme_id,
                            'image' => $imagename
                         ]);
                    }
                }
            }catch (\Exception $e) {
                return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
            }
        if ($request->ajax()) {
                $url = route('admin.themes.edit' , ['theme' => $theme_id]);
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.THEME_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.THEME_ADDED_SUCCESSFULLY.msg'),
                  ['url'=>$url]
                );
        }   
        else
        {
            return redirect('/admin/themes')->with('message', __('constants.messages.THEME_ADDED_SUCCESSFULLY.msg'));
        }
    }

    public function edit(Theme $themes, $id)
    {
        abort_if(Gate::denies('theme_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $type = 'Edit';
        $list = [];
        $data['themes'] = Theme::where('id', $id)->first();
        $data['images'] = ThemeMedium::where('theme_id',$data['themes']->id)->get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.themes.index'),'name' => trans('cruds.themes.title') ],['name' => trans('locale.Edit')." ".trans('cruds.themes.title_singular') ]];
        return view('admin.themes.createupdate', compact('list','data','breadcrumbs','type'));
    }

    public function update(UpdateThemeRequest $request,$id)
    {
        try {
                $client_id = Config::get('CLIENT_ID');
                $params = collect($request->all());
                $objtheme = Theme::find($params['id']);
                $theme_id =  $objtheme->id;
                $saveMedia = ThemeMedium::where(['theme_id' => $theme_id])->pluck('id')->toArray();
                $newMedia = collect($params['images'])->pluck('id')->toArray();
                $removeMedia = array_diff($saveMedia, $newMedia);

                if (!empty($removeMedia))
                {
                    $objRemoveMedia = ThemeMedium::whereIn('id', $removeMedia)->get();
                    foreach($objRemoveMedia as $key => $objSingleMedia)
                    {
                        Storage::disk('public')->delete("$client_id/themes/$theme_id/$objSingleMedia->image");
                    }
                    ThemeMedium::whereIn('id', $removeMedia)->delete();
                }
                if(!empty($params['images']))
                {
                    $themes = Theme::where('id',$id)->update($request->except(['images']));
                    foreach($params['images'] as $key => $imageData)
                    {
                        $refrence_id = mt_rand( 1000, 9999);
                        $imagename = time().$refrence_id.'.png';
                        $objThemeMedia = ThemeMedium::where('id', $imageData['id'])->first();
                        if (empty($objThemeMedia))
                        {
                            $objThemeMedia = new ThemeMedium;
                            $image = file_get_contents($imageData['imageurl']);
                            Storage::disk('public')->put("$client_id/themes/$theme_id/$imagename", $image, 'public');
                            $objThemeMedia->image = $imagename;
                        }
                        $objThemeMedia->theme_id = $theme_id;
                        $objThemeMedia->save();
                    }
                }
            else
            {
                $themes = Theme::where('id',$id)->update($request->except(['images']));  
            } 
        }   
        catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        
        if ($request->ajax()) {
            $url = route('admin.themes.index');
            return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.THEME_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.THEME_UPDATE_SUCCESSFULLY.msg'),
            ['url'=>$url]
        );
    }
        else {
                return redirect('/admin/themes')->with('message', __('constants.messages.THEME_UPDATE_SUCCESSFULLY.msg'));
            }
    }

    public function show(Theme $themes)
    {
        // abort_if(Gate::denies('theme_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function destroy(Theme $themes,$id)
    {
        try {
              abort_if(Gate::denies('theme_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              Theme::where('id',$id)->delete();   
        } catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.THEME_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.THEME_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyThemeRequest $request)
    {
        try {
              Theme::whereIn('id', request('ids'))->delete(); 
        } catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.THEME_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.THEME_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function selectTheme(Request $request)
    {
       
            abort_if(Gate::denies('select_theme_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $authUser = Config::get('client_id');
            $list['theme'] = Theme::get();
            $list['theme_media'] = ThemeMedium::get()->groupBy('theme_id');
            $list['selected_theme'] = ThemeSelection::where('user_id',$authUser)->first();
            $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'],['name' => 'SelectTheme listing' ]];
            return view('admin.settings.themes.selectedtheme', compact('list','breadcrumbs'));
    }

    public function storeUpdateSelectTheme(StoreThemeSelectRequest $request){
        try{
              $authUser = Config::get('client_id');
              $objThemeSelection = ThemeSelection::where('user_id',$authUser)->first();
                if(empty($objThemeSelection))
                {
                   $objThemeSelection = new ThemeSelection;
                }
                  $objThemeSelection->user_id = $authUser;
                  $objThemeSelection->theme_id = $request->theme_id;
                  $objThemeSelection->save();
            }catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.THEME_SELECTION_SUCCESSFULLY.code'),
            __('constants.messages.THEME_SELECTION_SUCCESSFULLY.msg'),
        );
    }

}
