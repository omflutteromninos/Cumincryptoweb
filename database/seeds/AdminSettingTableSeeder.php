<?php

use App\Model\AdminSetting;
use Illuminate\Database\Seeder;

class AdminSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminSetting::firstOrCreate(['slug'=>'coin_price'],['value'=>'2.50']);
        AdminSetting::firstOrCreate(['slug'=>'coin_name'],['value'=>'Cpoket']);
        AdminSetting::firstOrCreate(['slug'=>'app_title'],['value'=>'C Poket']);
        AdminSetting::firstOrCreate(['slug'=>'maximum_withdrawal_daily'],['value'=>'3']);
        AdminSetting::firstOrCreate(['slug'=>'mail_from'],['value'=>'noreply@cpoket.com']);
        AdminSetting::firstOrCreate(['slug'=>'admin_coin_address'],['value'=>'address']);
        AdminSetting::firstOrCreate(['slug'=>'base_coin_type'],['value'=>'BTC']);
        AdminSetting::firstOrCreate(['slug'=>'minimum_withdrawal_amount'],['value'=>.005]);
        AdminSetting::firstOrCreate(['slug'=>'maximum_withdrawal_amount'],['value'=>12]);

        AdminSetting::firstOrCreate(['slug' => 'maintenance_mode'],['value' => 'no']);
        AdminSetting::firstOrCreate(['slug' => 'logo'],['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'login_logo'],['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'landing_logo'],['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'favicon'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'copyright_text'], ['value' => 'Copyright@2020']);
        AdminSetting::firstOrCreate(['slug' => 'pagination_count'], ['value' => '10']);
        AdminSetting::firstOrCreate(['slug' => 'point_rate'], ['value' => '1']);
        //General Settings
        AdminSetting::firstOrCreate(['slug' => 'lang'], ['value' => 'en']);
        AdminSetting::firstOrCreate(['slug' => 'company_name'], ['value' => 'Test Company']);
        AdminSetting::firstOrCreate(['slug' => 'primary_email'], ['value' => 'test@email.com']);

        AdminSetting::firstOrCreate(['slug' => 'sms_getway_name'], ['value' => 'twillo']);
        AdminSetting::firstOrCreate(['slug' => 'twillo_secret_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'twillo_auth_token'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'twillo_number'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'ssl_verify'], ['value' => '']);

        AdminSetting::firstOrCreate(['slug' => 'mail_driver'], ['value' => 'SMTP']);
        AdminSetting::firstOrCreate(['slug' => 'mail_host'], ['value' => 'smtp.mailtrap.io']);
        AdminSetting::firstOrCreate(['slug' => 'mail_port'], ['value' => 2525]);
        AdminSetting::firstOrCreate(['slug' => 'mail_username'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'mail_password'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'mail_encryption'], ['value' => 'null']);
        AdminSetting::firstOrCreate(['slug' => 'mail_from_address'], ['value' => '']);


        AdminSetting::firstOrCreate(['slug' => 'braintree_client_token'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'braintree_environment'], ['value' => 'sandbox']);
        AdminSetting::firstOrCreate(['slug' => 'braintree_merchant_id'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'braintree_public_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'braintree_private_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'sms_getway_name'], ['value' => 'twillo']);
        AdminSetting::firstOrCreate(['slug' => 'clickatell_api_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'number_of_confirmation'], ['value' => '6']);
        AdminSetting::firstOrCreate(['slug' => 'referral_commission_percentage'], ['value' => '10']);
        AdminSetting::firstOrCreate(['slug' => 'referral_signup_reward'], ['value' => 10]);
        AdminSetting::firstOrCreate(['slug' => 'max_affiliation_level'], ['value' => 10]);


        // Coin Api
        AdminSetting::firstOrCreate(['slug' => 'coin_api_user'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'coin_api_pass'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'coin_api_host'], ['value' => 'test5']);
        AdminSetting::firstOrCreate(['slug' => 'coin_api_port'], ['value' => 'test']);


        // Send Fees
        AdminSetting::firstOrCreate(['slug' => 'send_fees_type'], ['value' => 1]);
        AdminSetting::firstOrCreate(['slug' => 'send_fees_fixed'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'send_fees_percentage'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'max_send_limit'], ['value' => 0]);
        //order settings
        AdminSetting::firstOrCreate(['slug' => 'deposit_time'], ['value' => 1]);

        //coin payment
        AdminSetting::firstOrCreate(['slug' => 'COIN_PAYMENT_PUBLIC_KEY'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'COIN_PAYMENT_PRIVATE_KEY'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'COIN_PAYMENT_CURRENCY'], ['value' => 'BTC']);
        AdminSetting::firstOrCreate(['slug' => 'ipn_merchant_id'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'ipn_merchant_id'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'ipn_secret'], ['value' => '']);

        AdminSetting::firstOrCreate(['slug' => 'payment_method_coin_payment'], ['value' => 1]);
        AdminSetting::firstOrCreate(['slug' => 'payment_method_bank_deposit'], ['value' => 1]);

        // Send Fees
        AdminSetting::firstOrCreate(['slug' => 'membership_bonus_type'], ['value' => 1]);
        AdminSetting::firstOrCreate(['slug' => 'membership_bonus_fixed'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'membership_bonus_percentage'], ['value' => 0]);

        //default coin withdrawal system
        AdminSetting::firstOrCreate(['slug' => 'chain_link'], ['value' => "https://data-seed-prebsc-1-s1.binance.org:8545"]);
        AdminSetting::firstOrCreate(['slug' => 'contract_address'], ['value' => ""]);
        AdminSetting::firstOrCreate(['slug' => 'wallet_address'], ['value' => ""]);
        AdminSetting::firstOrCreate(['slug' => 'private_key'], ['value' => ""]);
        AdminSetting::firstOrCreate(['slug' => 'contract_decimal'], ['value' => 18]);
        AdminSetting::firstOrCreate(['slug' => 'gas_limit'], ['value' => 43000]);
        AdminSetting::firstOrCreate(['slug' => 'contract_coin_name'], ['value' => 'ETH']);

        // kyc setting
        AdminSetting::firstOrCreate(['slug' => 'kyc_enable_for_withdrawal'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'kyc_nid_enable_for_withdrawal'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'kyc_passport_enable_for_withdrawal'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'kyc_driving_enable_for_withdrawal'], ['value' => 0]);

        //swap disable/enable
        AdminSetting::firstOrCreate(['slug' => 'swap_enabled'], ['value' => 1]);

    }
}
