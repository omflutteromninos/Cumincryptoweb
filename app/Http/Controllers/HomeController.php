<?php

namespace App\Http\Controllers;

use App\Model\Coin;
use App\Model\ContactUs;
use App\Model\CustomPage;
use App\Model\Faq;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function test()
    {
        return "hello";
    }
    // landing home page
    public function home()
    {
        $data['content'] = allsetting();
        $data['custom_links'] = CustomPage::orderBy('data_order','asc')->get();
        $data['faqs'] = Faq::where('status',1)->orderBy('created_at', 'desc')->get();
        $data['coins'] = Coin::where('status',1)->orderBy('created_at', 'desc')->get();
        return view('landing.landing',$data);
    }

    // custom page
    public function getCustomPage($id,$key){
        $data['content'] = allsetting();
        $data['custom_links'] = CustomPage::orderBy('data_order','asc')->get();
        $data['item'] = CustomPage::find($id);

        return view('landing.custom_page',$data);
    }

    public function contactUs(Request $request){
        $validatedData = $request->validate([
           'name'=>'required',
           'email'=>'required|email',
           'phone'=>'required',
           'address'=>'required',
           'description' => 'required',
            'g-recaptcha-response' => isset(allsetting()['google_recapcha']) && (allsetting()['google_recapcha'] == STATUS_ACTIVE) ? 'required|captcha' : ''
        ]);

        $data = [
            'name'=> $request->name,
            'email'=> $request->email,
            'phone' => $request->phone,
            'address'=> $request->address,
            'description'=> $request->description
        ];
        try{
            ContactUs::create($data);
            return redirect()->back()->with('success', __("Contact form submitted successfully"));
        }catch (\Exception $e){
            return redirect()->back()->with('dismiss',$e->getMessage());
        }
    }

}
