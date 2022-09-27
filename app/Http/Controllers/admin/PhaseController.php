<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\Admin\PhaseCreateRequest;
use App\Repository\PhaseRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\IcoPhase;

class PhaseController extends Controller
{
    // ico phase list
    public function adminPhaseList()
    {
        $data['title'] = __('Ico Phase List');
        $data['menu'] = 'phase';
        $data['sub_menu'] = 'phase_list';
        $data['phases'] = IcoPhase::where('status', '!=', STATUS_DELETED)->get();

        return view('admin.phase.list', $data);
    }

    // ico phase add
    public function adminPhaseAdd()
    {
        $data['title'] = __('Ico Phase Create');
        $data['menu'] = 'phase';
        $data['sub_menu'] = 'phase_add';

        return view('admin.phase.phase_add', $data);
    }

    /**
     * adminPhaseAddProcess
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function adminPhaseAddProcess(PhaseCreateRequest $request)
    {
        if ($request->isMethod('post')) {
            $response = app(PhaseRepository::class)->phaseAddProcess($request);
            if ($response['success'] == true) {
                return redirect()->route('adminPhaseList')->with('success', $response['message']);
            }

            return redirect()->back()->withInput()->with('dismiss', $response['message']);
        }
        return redirect()->back();
    }

    // phase edit

    public function phaseEdit($id)
    {
        $data['title'] = __('Update Phase');
        $data['menu'] = 'phase';
        $data['sub_menu'] = 'phase_add';
        $id = decrypt($id);
        $data['item'] = IcoPhase::find($id);
        if(isset($data['item'])) {
            return view('admin.phase.phase_add', $data);
        } else {
            return redirect()->back()->with(['dismiss' => __('Invalid phase')]);
        }


    }
    /**
     * Delete  phase
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function phaseDelete($id)
    {
        $id = decrypt($id);
        try {
            $phase = IcoPhase::where('id', $id)->first();
            if ( empty($phase) ) {
                return redirect()->back()->with(['dismiss' => __('Invalid phase')]);
            }
            $phase->status = STATUS_DELETED;
            $phase->save();

            return redirect()->back()->with(['success' => __('Phase deleted successfully.')]);
        } catch (\Exception $exception) {
            return redirect()->back()->with(['dismiss' => __('Something went wrong.')]);
        }
    }

    public function phaseStatusChange($id)
    {
        $id = decrypt($id);
        try {
            $phase = IcoPhase::where('id', $id)->first();
            if ( empty($phase) ) {
                return redirect()->back()->with(['dismiss' => __('Invalid phase')]);
            }
            if ( $phase->status == STATUS_SUCCESS ) {
                $phase->status = STATUS_PENDING;
                $phase->save();
                return redirect()->back()->with(['success' => __('Phase status deactivated successfully')]);

            } else {
                $phase->status = STATUS_SUCCESS;
                $phase->save();
                return redirect()->back()->with(['success' => __('Phase status activated successfully')]);

            }

        } catch (\Exception $exception) {
            return redirect()->back()->with(['dismiss' => __('Something went wrong.')]);
        }
    }
}
