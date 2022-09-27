<?php

namespace App\Http\Controllers\admin;

use App\Model\AdminSetting;
use App\Model\CustomPage;
use App\Model\Faq;
use App\Repository\SettingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LandingController extends Controller
{
    public function __construct()
    {
        $this->settingRepo = new SettingRepository();
    }

    // custom page list
    public function adminCustomPageList(Request $request)
    {
        $data['title'] = __("Custom Page List");
        if ($request->ajax()) {
            $cp = CustomPage::select('id', 'title', 'key', 'description', 'status', 'created_at')->orderBy('data_order','ASC');
            return datatables($cp)
                ->addColumn('actions', function ($item) {
                    $html = '<input type="hidden" value="'.$item->id.'" class="shortable_data">';
                    $html .= '<ul class="d-flex activity-menu">';

                    $html .= ' <li class="viewuser"><a title="Edit" href="' . route('adminCustomPageEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> <span></span></li>';
                    $html .= delete_html('adminCustomPageDelete',encrypt($item->id));
                    $html .=' </ul>';
                    return $html;
                })
                ->rawColumns(['actions'])->make(true);
        }

        return view('admin.custom-page.custom-pages-list', $data);
    }

    // custom page add
    public function adminCustomPageAdd()
    {
        $data['title'] = __("Add Page");
        return view('admin.custom-page.custom-pages', $data);
    }

    // edit the custom page
    public function adminCustomPageEdit($id)
    {
        $data['title'] = __("Update Page");
        $data['cp'] = CustomPage::findOrFail($id);

        return view('admin.custom-page.custom-pages', $data);
    }

    // custom page save setting
    public function adminCustomPageSave(Request $request)
    {
        $rules = [
            'menu' => 'required|max:255',
            'title' => 'required'
        ];
        $messages = [
            'title.required' => __('Title Can\'t be empty!'),
            'menu.required' => __('Menu Can\'t be empty!'),
            'description.required' => __('Description Can\'t be empty!')
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors[0];

            return redirect()->back()->withInput()->with(['dismiss' => $data['message']]);
        }

        $custom_page = [
            'title' => $request->title
            , 'key' => $request->menu
            , 'description' => $request->description
            , 'status' => STATUS_SUCCESS
        ];

        CustomPage::updateOrCreate(['id' => $request->edit_id], $custom_page);

        if ($request->edit_id) {
            $message = __('Custom page updated successfully');
        } else {
            $message = __('Custom Page created successfully');
        }

        return redirect()->route('adminCustomPageList')->with(['success' => $message]);
    }

    // delete custom page
    public function adminCustomPageDelete($id)
    {
        if (isset($id)) {
            CustomPage::where(['id' => decrypt($id)])->delete();
        }

        return redirect()->back()->with(['success' => __('Deleted Successfully')]);
    }


    // change custom page order
    public function customPageOrder(Request $request)
    {
        $vals = explode(',',$request->vals);
        foreach ($vals as $key => $item){
            CustomPage::where('id',$item)->update(['data_order'=>$key]);
        }

        return response()->json(['message'=>__('Page ordered change successfully')]);
    }

//    Landing Settings
    public function adminLandingSetting(Request $request)
    {
        if (isset($_GET['tab'])) {
            $data['tab']=$_GET['tab'];
        } else {
            $data['tab']='hero';
        }
        $data['adm_setting'] = allsetting();

        return view('admin.settings.landing-settings',$data);
    }

    // save cms setting
    public function adminLandingSettingSave(Request $request)
    {
        $rules = [];
        foreach ($request->all() as $key => $item) {
            if ($request->hasFile($key)) {
                $rules[$key] = 'image|mimes:jpg,jpeg,png|max:2000';
            }
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors;
            return redirect()->back()->with(['dismiss' => $errors[0]]);
        }
        foreach ($request->all() as $key => $item) {
            if (!empty($request->$key)) {
                $setting = AdminSetting::where('slug', $key)->first();
                if (empty($setting)) {
                    $setting = new AdminSetting();
                    $setting->slug = $key;
                }
                if ($request->hasFile($key)) {
                    $setting->value = uploadFile($request->$key, IMG_PATH, isset(allsetting()[$key]) ? allsetting()[$key] : '');
                } else {
                    $setting->value = $request->$key;
                }
                $setting->save();
            }
        }

        return redirect()->back()->with(['success' => __('Landing Page Setting Successfully Updated!')]);

    }
}
