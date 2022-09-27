<?php
namespace App\Repository;
use App\Model\Bank;
use App\Model\IcoPhase;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PhaseRepository
{
// phase  save process
    public function phaseAddProcess($request)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        try {
            $st_date = date('Y-m-d H:i:s', strtotime($request->start_date));
            $end_date = date('Y-m-d H:i:s', strtotime($request->end_date));

            $check = IcoPhase::where(function ($query) use ($st_date, $end_date) {
//                $query->whereDateBetween($st_date, ['start_date','end_date']);
                $query->whereRaw('? between start_date and end_date', [$st_date])
                    ->OrwhereRaw('? between start_date and end_date', [$end_date]);
                // ->OrwhereBetween('end_date', [$st_date,$end_date]);
            });

            if ( !empty($request->edit_id) ) {
                $check = $check->where('id', '!=', decrypt($request->edit_id));
            }
            $check = $check->where('status', '!=', STATUS_DELETED);
            $check = $check->exists();

            if ( $check ) {
                $response = ['success' => false, 'message' => __('Phase is already active in this date')];
                return $response;
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => __('Something went wrong')];
            return $response;
        }

        DB::beginTransaction();
        try {
            $data = [
                'start_date' => date("Y-m-d H:i:s", strtotime($request->start_date)),
                'end_date' => date("Y-m-d H:i:s", strtotime($request->end_date)),
                'rate' => $request->rate,
                'amount' => $request->amount,
                'affiliation_level' => $request->affiliation_level,
                // 'affiliation_percentage' => $request->affiliation_percentage,
                'phase_name' => $request->phase_name,
               // 'fees' => isset($request->fees) ? $request->fees : 0,
                'fees' => 0,
                'bonus' => isset($request->bonus) ? $request->bonus : 0,
                'status' => $request->status
            ];
            if (!empty($request->edit_id)) {
                $coin_phases_id = IcoPhase::updateOrcreate(['id' => decrypt($request->edit_id)], $data);
                $response = ['success' => true, 'message' => __('Phase updated successfully')];
            } else {
                $coin_phases_id = IcoPhase::create($data);
                $response = ['success' => true, 'message' => __('New phase created successfully')];
            }

        } catch (\Exception $exception) {
            DB::rollback();
            $response = ['success' => false, 'message' => __('Something went wrong')];
            return $response;
        }
        DB::commit();
        return $response;
    }

// delete bank
    public function deleteBank($id)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        DB::beginTransaction();
        try {
            $item = Bank::where('id',$id)->first();
            if (isset($item)) {
                $delete = $item->update(['status' => 5]);
                if ($delete) {
                    $response = [
                        'success' => true,
                        'message' => __('Bank deleted successfully.')
                    ];
                } else {
                    DB::rollBack();
                    $response = [
                        'success' => false,
                        'message' => __('Operation failed.')
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => __('Data not found.')
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
        DB::commit();
        return $response;
    }

}
