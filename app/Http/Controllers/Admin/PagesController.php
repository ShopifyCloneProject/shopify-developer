<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ThemeSetting;
use App\Models\Collection;
use App\Models\Product;
use App\Models\MainMenu;
use App\Models\PageMedia;
use App\Models\PageOption;

use Exception;
use Storage;
use Image;
use Helper;
use DB;
class PagesController extends Controller
{
  public function homepage()
  {   
      $homePageData = ThemeSetting::with('pagemedias')->where('page',1)->orderBy('order')->get();
      $objCollections = Collection::select('id','title')->where('status',1)->get();
      $objProducts    = Product::select('id','title')->where('status',1)->limit(50)->get();
      $menuData = MainMenu::where('level',1)->orderBy('order')->get();
      $selectedCollection = [];
      $selectedCollectionIds = [];

      $optionValue = Helper::getOption('home_collections');
      if($optionValue != ''){
          $selectedCollection = explode(', ', $optionValue);
          $selectedCollection = Collection::select('id','title')->whereIn('id', $selectedCollection)->where('status', 1)->get();
      }
      if(!empty($selectedCollection))
      {
        $selectedCollectionIds = $selectedCollection->pluck('id')->toArray();
      }
        foreach($objCollections as $key => $objCollection)
        {
          $objCollections[$key]['checked'] = false;
          if(in_array($objCollection->id, $selectedCollectionIds))
          {
            $objCollections[$key]['checked'] = true;
          }
        }


      $list = [
        'pageSectionData' => $homePageData,
        'objCollections'   => $objCollections,
        'objProducts'   => $objProducts,
        'menudata'      => $menuData,
        'selectedCollection' => $selectedCollection
      ];

      return view('admin.pages.home.index', compact('list'));
  }

  public function menu()
  {   
      $menuData = MainMenu::where('level',1)->orderBy('order')->get();
      $objCollections = Collection::select('id','title')->where('status',1)->get();
      $objProducts    = Product::select('id','title')->where('status',1)->limit(50)->get();
      $list = [
        'menudata'      => $menuData,
        'objCollections' => $objCollections,
        'objProducts'   => $objProducts
      ];
      $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('locale.Menu')." ".trans('global.listing') ]];
      return view('admin.pages.common.menu', compact('list','breadcrumbs'));
  }

  public function saveMenu(Request $request)
  {

    try {
       $params = collect($request->all());
         $existing = [];
        if(!empty($params['menuData'])){
          foreach($params['menuData'] as $menukey => $menu)
          {
            $objAllMainMenu = MainMenu::where(['menuname' => $menu['menuname'], 'level' => 1])->first();
            if(empty($objAllMainMenu))
            {
              $objAllMainMenu = new MainMenu;
            }
            $objAllMainMenu->menuname = $menu['menuname'];

            if($menu['setlink'] == 'makechild')
            {
              $objAllMainMenu->setlink = 'makechild';
            }
            else if($menu['setlink'] == 'chooseoption')
            {
              if($menu['category'] == 'collection')
              {
                $objAllMainMenu->category = 'collection';
              }
              else if($menu['category'] == 'product')
              {
                $objAllMainMenu->category = 'product';
              }
              $objAllMainMenu->setlink = 'chooseoption';
              $objAllMainMenu->category_product_relation = $menu['categoryrelation']['id'];
            }
            else
            {
              $objAllMainMenu->setlink = 'directurl';
              $objAllMainMenu->url = $menu['url'];
            }

            $objAllMainMenu->level = 1;
            $objAllMainMenu->order = $menukey;
            $objAllMainMenu->save();
            $existing[] = $objAllMainMenu->id;
          }
        }
        $objAllMainMenuId =  MainMenu::where('level',1)->get()->pluck('id')->toArray();
         $removeMainMenuId = array_diff($objAllMainMenuId, $existing);
         if(!empty($removeMainMenuId))
         {
          MainMenu::whereIn('id', $removeMainMenuId)->delete();
         }

         \Artisan::call('config:clear');
         return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.MENU_SET_SUCCESSFULLY.code'),
                __('constants.messages.MENU_SET_SUCCESSFULLY.msg'),
                 []
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

  public function childhomepage($level = 2, $parentid = 0)
  {
      
       $menuData = MainMenu::where('level',$level)->orderBy('order')->get();
       $objCollections = Collection::select('id','title')->where('status',1)->get();
       $objProducts    = Product::select('id','title')->where('status',1)->limit(50)->get();
       $parentmenuname = MainMenu::whereId($parentid)->first()->menuname;
       $list = [
        'level'         => $level,
        'parentmenuname' => $parentmenuname,
        'parentid'      => $parentid,
        'menudata'      => $menuData,
        'objCollections'   => $objCollections,
        'objProducts'   => $objProducts,
      ];
      return view('admin.pages.home.child', compact('list'));
  }

  public function saveHomePagesSettings(Request $request)
  {
     try {
             $client_id = env('CLIENT_ID');
             $params = collect($request->all());
             foreach ($params['pageData'] as $key => $value) {
                ThemeSetting::where(['sectionname' => $value['sectionname'],'page' =>1])->update(['order' => $key, 'status' => $value['status']]);
                $this->checkFolder("public/$client_id");
                if($value['sectionname'] == 'header')
                {
                  if(empty($value['logo']))
                  {
                    ThemeSetting::where(['sectionname' => 'header', 'page' =>1])->update(['logo' => null]);
                  }
                  else if($value['logo'][0]['id'] == 'new')
                  {
                     
                     $this->checkFolder("public/$client_id/logo");
                     $this->checkFolder("public/$client_id/logo/120/");
                      $refrence_id = mt_rand( 1000, 9999);
                      $imagename = time().$refrence_id.'.png';
                      $image = file_get_contents($value['logo'][0]['imageurl']);
                      Storage::disk('public')->put($client_id."/logo/$imagename", $image, 'public');
                      $image = Image::make(storage_path("app/public/".$client_id."/logo/$imagename"))->resize(120, 61);
                      $image->save(storage_path("app/public/".$client_id."/logo/120/$imagename"));
                      ThemeSetting::where(['sectionname' => 'header'])->update(['logo' => $imagename]);
                  }

                  if(empty($value['icon']))
                  {
                    ThemeSetting::where(['sectionname' => 'header', 'page' =>1])->update(['icon' => null]);
                  }
                  else if($value['icon'][0]['id'] == 'new')
                  {
                     
                     $this->checkFolder("public/$client_id/icon");
                      $refrence_id = mt_rand( 1000, 9999);
                      $imagename = time().$refrence_id.'.ico';
                      $image = file_get_contents($value['icon'][0]['imageurl']);
                      Storage::disk('public')->put($client_id."/icon/$imagename", $image, 'public');
                      ThemeSetting::where(['sectionname' => 'header'])->update(['icon' => $imagename]);
                  }
                  if(empty($value['title'])){
                      ThemeSetting::where(['sectionname' => 'header', 'page' =>1])->update(['title' => null]);
                  }
                      ThemeSetting::where(['sectionname' => 'header', 'page' => $value['page']])->update(['title' => $value['title']]);
                  
                }
                else if($value['sectionname'] == 'slider')
                {
                  $existingMediaId = [];
                  foreach($value['pagemedias'] as $sliderKey => $objSlider)
                  {
                    if(isset($objSlider['id']))
                    {
                      $objPageMedia = PageMedia::where('id',$objSlider['id'])->first();
                    }
                    else
                    {
                      $objPageMedia = new PageMedia;
                    }

                    if($objSlider['imagedata'][0]['id'] == 'new')
                    {
                      
                      $this->checkFolder("public/$client_id/slider");
                      $this->checkFolder("public/$client_id/slider/1920");
                      $this->checkFolder("public/$client_id/slider/1550");
                      $refrence_id = mt_rand( 1000, 9999);
                      $imagename = time().$refrence_id.'.png';
                      $image = file_get_contents($objSlider['imagedata'][0]['imageurl']);
                      Storage::disk('public')->put("$client_id/slider/$imagename", $image, 'public');
                      $image = Image::make(storage_path("app/public/".$client_id."/slider/$imagename"))->resize(1920, 700);
                      $image->save(storage_path("app/public/".$client_id."/slider/1920/$imagename"));
                      $image1 = Image::make(storage_path("app/public/".$client_id."/slider/$imagename"))->resize(1550, 1000);
                      $image1->save(storage_path("app/public/".$client_id."/slider/1550/$imagename"));
                      
                      $objPageMedia->src = $imagename;
                      $objPageMedia->section_id = 2;
                      $objPageMedia->align = $objSlider['align'];
                      $objPageMedia->text1 = $objSlider['text1'];
                      $objPageMedia->text2 = $objSlider['text2'];
                      $objPageMedia->url = $objSlider['url'];

                    }

                    $objPageMedia->order = $sliderKey;
                    $objPageMedia->save();
                    $existingMediaId[] = $objPageMedia->id;
                  }

                  $objCurrentMediaIds = PageMedia::where('section_id',2)->get()->pluck('id')->toArray();
                  $removePageMediaId = array_diff($objCurrentMediaIds, $existingMediaId);
                   if(!empty($removePageMediaId))
                   {
                    PageMedia::whereIn('id', $removePageMediaId)->delete();
                   }


                }
                 else if($value['sectionname'] == 'accessories')
                {
                  $existingMediaId = [];
                  foreach($value['pagemedias'] as $sliderKey => $objSlider)
                  {
                    if(isset($objSlider['id']))
                    {
                      $objPageMedia = PageMedia::where('id',$objSlider['id'])->first();
                    }
                    else
                    {
                      $objPageMedia = new PageMedia;
                    }

                    if($objSlider['imagedata'][0]['id'] == 'new')
                    {
                      
                      $this->checkFolder("public/$client_id/accessories");
                      $this->checkFolder("public/$client_id/accessories/570");
                      $this->checkFolder("public/$client_id/accessories/330");
                      $refrence_id = mt_rand( 1000, 9999);
                      $imagename = time().$refrence_id.'.png';
                      $image = file_get_contents($objSlider['imagedata'][0]['imageurl']);
                      Storage::disk('public')->put($client_id."/accessories/$imagename", $image, 'public');
                      $image = Image::make(storage_path("app/public/".$client_id."/accessories/$imagename"))->resize(570, 534);
                      $image->save(storage_path("app/public/".$client_id."/accessories/570/$imagename"));
                      $image1 = Image::make(storage_path("app/public/".$client_id."/accessories/$imagename"))->resize(330, 309);
                      $image1->save(storage_path("app/public/".$client_id."/accessories/330/$imagename"));
                      
                      $objPageMedia->src = $imagename;
                      $objPageMedia->section_id = 3;
                      $objPageMedia->text1 = $objSlider['text1'];
                      $objPageMedia->text2 = $objSlider['text2'];
                      $objPageMedia->url = $objSlider['url'];

                    }

                    $objPageMedia->order = $sliderKey;
                    $objPageMedia->save();
                    $existingMediaId[] = $objPageMedia->id;
            
                  }

                  $objCurrentMediaIds = PageMedia::where('section_id',3)->get()->pluck('id')->toArray();
                  $removePageMediaId = array_diff($objCurrentMediaIds, $existingMediaId);
                   if(!empty($removePageMediaId))
                   {
                    PageMedia::whereIn('id', $removePageMediaId)->delete();
                   }

                }
                else if($value['sectionname'] == 'logo')
                {
                  $existingMediaId = [];
                  foreach($value['pagemedias'] as $logoKey => $objLogo)
                  {

                    if(isset($objLogo['id']))
                    {
                      $objPageMedia = PageMedia::where('id',$objLogo['id'])->first();
                    }
                    else
                    {
                      $objPageMedia = new PageMedia;
                    }

                    if($objLogo['imagedata'][0]['id'] == 'new')
                    {
                      
                      $this->checkFolder("public/$client_id/companylogo");
                      $this->checkFolder("public/$client_id/companylogo/240");
                      $refrence_id = mt_rand( 1000, 9999);
                      $imagename = time().$refrence_id.'.png';
                      $image = file_get_contents($objLogo['imagedata'][0]['imageurl']);
                      Storage::disk('public')->put($client_id."/companylogo/$imagename", $image, 'public');
                      $image = Image::make(storage_path("app/public/".$client_id."/companylogo/$imagename"))->resize(240, 90);
                      $image->save(storage_path("app/public/".$client_id."/companylogo/240/$imagename"));
                      $objPageMedia->src = $imagename;
                      $objPageMedia->section_id = 4;
                      $objPageMedia->url = $objLogo['url'];

                    }

                    $objPageMedia->order = $logoKey;
                    $objPageMedia->save();
                    $existingMediaId[] = $objPageMedia->id;
                  }

                  $objCurrentMediaIds = PageMedia::where('section_id',4)->get()->pluck('id')->toArray();
                  $removePageMediaId = array_diff($objCurrentMediaIds, $existingMediaId);
                   if(!empty($removePageMediaId))
                   {
                    PageMedia::whereIn('id', $removePageMediaId)->delete();
                   }

                }
                else if($value['sectionname'] == 'besttrends')
                {
                  $existingMediaId = [];
                  foreach($value['pagemedias'] as $trendsKey => $objTrends)
                  {
                    if(isset($objTrends['id']))
                    {
                      $objPageMedia = PageMedia::where('id',$objTrends['id'])->first();
                    }
                    else
                    {
                      $objPageMedia = new PageMedia;
                    }

                    if($objTrends['imagedata'][0]['id'] == 'new')
                    {
                      
                      $this->checkFolder("public/$client_id/trends");
                      $this->checkFolder("public/$client_id/trends/1755");
                      $this->checkFolder("public/$client_id/trends/330");
                      $refrence_id = mt_rand( 1000, 9999);
                      $imagename = time().$refrence_id.'.png';
                      $image = file_get_contents($objTrends['imagedata'][0]['imageurl']);
                      Storage::disk('public')->put($client_id."/trends/$imagename", $image, 'public');
                      $image = Image::make(storage_path("app/public/".$client_id."/trends/$imagename"))->resize(1755, 726);
                      $image->save(storage_path("app/public/".$client_id."/trends/1755/$imagename"));
                      $image1 = Image::make(storage_path("app/public/".$client_id."/trends/$imagename"))->resize(330, 245);
                      $image1->save(storage_path("app/public/".$client_id."/trends/330/$imagename"));
                      
                      $objPageMedia->src = $imagename;
                      $objPageMedia->section_id = 6;
                      $objPageMedia->text1 = $objTrends['text1'];
                      $objPageMedia->text2 = $objTrends['text2'];
                      $objPageMedia->url = $objTrends['url'];

                    }

                    $objPageMedia->order = $trendsKey;
                    $objPageMedia->save();
                    $existingMediaId[] = $objPageMedia->id;
                  }

                  $objCurrentMediaIds = PageMedia::where('section_id',6)->get()->pluck('id')->toArray();
                  $removePageMediaId = array_diff($objCurrentMediaIds, $existingMediaId);
                   if(!empty($removePageMediaId))
                   {
                    PageMedia::whereIn('id', $removePageMediaId)->delete();
                   }


                }
                else if($value['sectionname'] == 'collection')
                {
                  $ids = '';
                  $categories = array_column($value['selectedCollections'], 'id');
                  if(!empty($categories)){
                    $ids = implode(', ', $categories);
                  }

                  $pageOption = PageOption::where('option_name', 'home_collections')->first();
                  if(!$pageOption){
                    $pageOption = new PageOption();
                  }
                  $pageOption->option_name = 'home_collections';
                  $pageOption->option_value = $ids;
                  $pageOption->save();
                }


             }

              \Artisan::call('config:clear');
         return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SETTINGS_SET_SUCCESFULLY.code'),
                __('constants.messages.SETTINGS_SET_SUCCESFULLY.msg'),
                 []
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

  public function saveLevelPage(Request $request)
  { 
        try{

          $params = collect($request->all());
          $level = $params['level'];
          $parentid = $params['parentid'];
          $existing = [];
          if(!empty($params['levelData'])){
            foreach($params['levelData'] as $menukey => $menu)
            {
              $objAllMainMenu = MainMenu::where(['menuname' => $menu['menuname'], 'level' => $level])->first();
              if(empty($objAllMainMenu))
              {
                $objAllMainMenu = new MainMenu;
              }
              $objAllMainMenu->menuname = $menu['menuname'];

              if($menu['setlink'] == 'makechild')
              {
                $objAllMainMenu->setlink = 'makechild';
              }
              else if($menu['setlink'] == 'chooseoption')
              {
                if($menu['category'] == 'collection')
                {
                  $objAllMainMenu->category = 'collection';
                }
                else if($menu['category'] == 'product')
                {
                  $objAllMainMenu->category = 'product';
                }
                $objAllMainMenu->setlink = 'chooseoption';
                $objAllMainMenu->category_product_relation = $menu['categoryrelation']['id'];
              }
              else
              {
                $objAllMainMenu->setlink = 'directurl';
                $objAllMainMenu->url = $menu['url'];
              }

              $objAllMainMenu->relation = $parentid;
              $objAllMainMenu->level = $level;
              $objAllMainMenu->order = $menukey;
              $objAllMainMenu->save();
              $existing[] = $objAllMainMenu->id;
            }
          }
           $objAllMainMenuId =  MainMenu::where('level',$level)->get()->pluck('id')->toArray();
           $removeMainMenuId = array_diff($objAllMainMenuId, $existing);
           if(!empty($removeMainMenuId))
           {
            MainMenu::whereIn('id', $removeMainMenuId)->delete();
           }
            \Artisan::call('config:clear');
           return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.LEVEL_SET_SUCCESFULLY.code'),
                __('constants.messages.LEVEL_SET_SUCCESFULLY.msg'),
                 []
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

  // Account Settings
  public function account_settings()
  {
    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "Account Settings"]];
    return view('/content/pages/page-account-settings', ['breadcrumbs' => $breadcrumbs]);
  }

  // Profile
  public function profile()
  {
    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "Profile"]];

    return view('/content/pages/page-profile', ['breadcrumbs' => $breadcrumbs]);
  }

  // FAQ
  public function faq()
  {
    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "FAQ"]];
    return view('/content/pages/page-faq', ['breadcrumbs' => $breadcrumbs]);
  }

  // Knowledge Base
  public function knowledge_base()
  {
    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "Knowledge Base"]];
    return view('/content/pages/page-knowledge-base', ['breadcrumbs' => $breadcrumbs]);
  }

  // Knowledge Base Category
  public function kb_category()
  {
    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "/page/knowledge-base", 'name' => "Knowledge Base"], ['name' => "Category"]];
    return view('/content/pages/page-kb-category', ['breadcrumbs' => $breadcrumbs]);
  }

  // Knowledge Base Question
  public function kb_question()
  {
    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "/page/knowledge-base", 'name' => "Knowledge Base"], ['link' => "/page/kb-category", 'name' => "Category"], ['name' => "Question"]];
    return view('/content/pages/page-kb-question', ['breadcrumbs' => $breadcrumbs]);
  }

  // pricing
  public function pricing()
  {
    $pageConfigs = ['pageHeader' => false];
    return view('/content/pages/page-pricing', ['pageConfigs' => $pageConfigs]);
  }

  // blog list
  public function blog_list()
  {
    $pageConfigs = ['contentLayout' => 'content-detached-right-sidebar', 'bodyClass' => 'content-detached-right-sidebar'];

    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "javascript:void(0)", 'name' => "Blog"], ['name' => "List"]];

    return view('/content/pages/page-blog-list', ['breadcrumbs' => $breadcrumbs, 'pageConfigs' => $pageConfigs]);
  }

  // blog detail
  public function blog_detail()
  {
    $pageConfigs = ['contentLayout' => 'content-detached-right-sidebar', 'bodyClass' => 'content-detached-right-sidebar'];

    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "javascript:void(0)", 'name' => "Blog"], ['name' => "Detail"]];

    return view('/content/pages/page-blog-detail', ['breadcrumbs' => $breadcrumbs, 'pageConfigs' => $pageConfigs]);
  }

  // blog edit
  public function blog_edit()
  {
    $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "javascript:void(0)", 'name' => "Blog"], ['name' => "Edit"]];

    return view('/content/pages/page-blog-edit', ['breadcrumbs' => $breadcrumbs]);
  }

  //Product detail page settings
  public function indexProductDetail(){
      $pageSettings = ThemeSetting::where('page', 2)->orderBy('order')->get();
      $objCollections = Collection::select('id','title')->where('status', 1)->get();
      $objProducts    = Product::select('id','title')->where('status', 1)->limit(50)->get();
      $menuData = MainMenu::where('level', 1)->orderBy('order')->get();

      $layoutPosition = Helper::getOption('detail_layout_position');
      $charlimit = Helper::getOption('short_description_limit');
      $advanceSettings = [
          'detail_layout_position' => $layoutPosition == "" ? 1 : $layoutPosition,
          'short_description_limit' => $charlimit == "" ? 500 : $charlimit
      ];

      $list = [
        'pageSectionData' => $pageSettings,
        'objCollections'  => $objCollections,
        'objProducts'     => $objProducts,
        'menudata'        => $menuData,
        'advanceSettings' => $advanceSettings,
      ];
      return view('admin.pages.home.productdetail', compact('list'));
  }

  public function saveProductDetailSettings(Request $request)
  {
    try{
            $params = collect($request->all());
            foreach ($params['pageData'] as $key => $value) 
            {
              //update order
              ThemeSetting::where(['sectionname' => $value['sectionname'], 'page' => 2])->update(['order' => $key, 'status' => $value['status']]);
            }

            foreach ($params['advanceSettings'] as $option => $value) 
            {
              $pageOption = PageOption::where('option_name', $option)->first();
              if(!$pageOption){
                $pageOption = new PageOption();
              }
              $pageOption->option_name = $option;
              $pageOption->option_value = $value;
              $pageOption->save();
            }

          \Artisan::call('config:clear');
          return $this->successResponse(
              __('constants.SUCCESS_STATUS'),
              __('constants.messages.SETTINGS_SET_SUCCESFULLY.code'),
              __('constants.messages.SETTINGS_SET_SUCCESFULLY.msg'),
               []
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
}
