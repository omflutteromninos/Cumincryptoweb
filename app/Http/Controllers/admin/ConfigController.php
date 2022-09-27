<?php

namespace App\Http\Controllers\admin;

use App\Model\AdminSetting;
use App\Model\Coin;
use App\Model\CoinPair;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ConfigController extends Controller
{
    //admin config
    public function adminConfiguration()
    {
        $data['title'] = __("Configuration");

        return view('admin.settings.config', $data);
    }

    // run command
    public function adminRunCommand($type)
    {
        $message = __('Nothing to execute');
        try {
            if($type == COMMAND_TYPE_WALLET) {
               Artisan::call('adjust-wallet-coin');
                $message = __('Coin wallet command executed');
            }
            if($type == COMMAND_TYPE_MIGRATE) {
               Artisan::call('migrate');
                $message = __('Migrate successfully');
            }
            if($type == COMMAND_TYPE_CACHE) {
               Artisan::call('cache:clear');
                $message = __('Application cache cleared successfully');
            }
            if($type == COMMAND_TYPE_CONFIG) {
               Artisan::call('config:clear');
                $message = __('Application config cleared successfully');
            }
            if($type == COMMAND_TYPE_VIEW || $type == COMMAND_TYPE_ROUTE) {
               Artisan::call('view:clear');
               Artisan::call('route:clear');
                $message = __('Application view cleared successfully');
            }
            if($type == COMMAND_TYPE_PASSPORT_INSTALL) {
               Artisan::call('passport:install');
                $message = __('Personal access client created successfully');
            }
            if($type == COMMAND_TYPE_TRADE_FEES) {
                $this->adjustTradeFeesSettings();
                $message = __('Trade fees setting configured successfully');
            }
            if($type == COMMAND_TYPE_TOKEN_DEPOSIT) {
                Artisan::call('custom-token-deposit');
                $message = __('Custom token deposit command run once successfully');
            }
            if($type == COMMAND_TYPE_ADJUST_TOKEN_DEPOSIT) {
                Artisan::call('adjust-token-deposit');
                $message = __('Adjust custom token deposit command run once successfully');
            }
            if($type == COMMAND_TYPE_DISTRIBUTE_MEMBERSHIP_BONUS) {
                Artisan::call('command:membershipbonus');
                $message = __('Membership bonus distribution command run once successfully');
            }

        } catch (\Exception $e) {
            Log::info('command exception--> '. $e->getMessage());
            return redirect()->back()->with('dismiss', $e->getMessage());
        }

        return redirect()->back()->with('success', $message);
    }

    // adjust trade fees settings
    public function adjustTradeFeesSettings()
    {
        try {
            AdminSetting::updateOrCreate(['slug' => 'trade_limit_1'], ['value' => 0]);
            AdminSetting::updateOrCreate(['slug' => 'maker_1'], ['value' => 0]);
            AdminSetting::updateOrCreate(['slug' => 'taker_1'], ['value' => 0]);

        } catch (\Exception $e) {
            Log::info('adjust trade fees settings exception--> '. $e->getMessage());
        }
        return true;
    }


}
