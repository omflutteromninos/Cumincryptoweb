<?php
Route::post('user/withdrawal-coin/callback', 'user\CoinController@callback')->name('callback');
Route::post('user/withdrawal-coin/deposit/callback', 'user\CoinController@depositCallback')->name('depositCallback');

Route::group(['prefix'=>'user','namespace'=>'user','middleware'=> ['auth','user', 'lang']],function () {

    Route::get('dashboard', 'DashboardController@userDashboard')->name('userDashboard');
    Route::get('show-notification', 'DashboardController@showNotification')->name('showNotification');
    Route::get('get-notification', 'DashboardController@getNotification')->name('getNotification');
    Route::get('faq', 'DashboardController@userFaq')->name('userFaq');
    Route::get('profile', 'ProfileController@userProfile')->name('userProfile');
    Route::post('nid-upload', 'ProfileController@nidUpload')->name('nidUpload');
    Route::post('pass-upload', 'ProfileController@passUpload')->name('passUpload');
    Route::post('driving-licence-upload', 'ProfileController@driveUpload')->name('driveUpload');
    Route::get('referral', 'ReferralController@myReferral')->name('myReferral');
    Route::get('referral-earning-history', 'ReferralController@myReferralEarning')->name('myReferralEarning');
    Route::get('setting', 'SettingController@userSetting')->name('userSetting');
    Route::get('my-pocket', 'WalletController@myPocket')->name('myPocket');
    Route::get('swap-coin-details', 'WalletController@getCoinSwapDetails')->name('getCoinSwapDetails')->middleware('swap-check');
    Route::get('get-rate', 'WalletController@getRate')->name('getRate');
    Route::get('coin-swap', 'WalletController@coinSwap')->name('coinSwap')->middleware('swap-check');
    Route::post('swap-coin', 'WalletController@swapCoin')->name('swapCoin')->middleware('swap-check');
    Route::get('coin-convert-history', 'WalletController@coinSwapHistory')->name('coinSwapHistory')->middleware('swap-check');

    Route::post('g2f-secret-save', 'SettingController@g2fSecretSave')->name('g2fSecretSave');
    Route::post('google-login-enable', 'SettingController@googleLoginEnable')->name('googleLoginEnable');
    Route::post('save-preference', 'SettingController@savePreference')->name('savePreference');

    Route::get('/generate/new-address', 'WalletController@generateNewAddress')->name('generateNewAddress');
    Route::get('/qrcode/generate', 'WalletController@qrCodeGenerate')->name('qrCodeGenerate');
    Route::get('/make-account-default-{account_id}-{ctype}', 'WalletController@makeDefaultAccount')->name('makeDefaultAccount');
    Route::any('/Wallet-create', 'WalletController@createWallet')->name('createWallet');
    Route::get('/wallet-details-{id}', 'WalletController@walletDetails')->name('walletDetails');
    Route::post('/Withdraw/balance', 'WalletController@WithdrawBalance')->name('WithdrawBalance');
    Route::get('transaction-histories', 'WalletController@transactionHistories')->name('transactionHistories');
    Route::post('withdraw-coin-rate', 'WalletController@withdrawCoinRate')->name('withdrawCoinRate');


    Route::get('buy-coin', 'CoinController@buyCoinPage')->name('buyCoin');
    Route::post('buy-coin-rate', 'CoinController@buyCoinRate')->name('buyCoinRate');
    Route::get('bank-details', 'CoinController@bankDetails')->name('bankDetails');
    Route::post('buy-coin', 'CoinController@buyCoin')->name('buyCoinProcess');
    Route::get('buy-coin-by-{address}', 'CoinController@buyCoinByAddress')->name('buyCoinByAddress');
    Route::get('buy-coin-history', 'CoinController@buyCoinHistory')->name('buyCoinHistory');
    Route::get('buy-coin-referral-history', 'CoinController@buyCoinReferralHistory')->name('buyCoinReferralHistory');

    Route::get('my-membership', 'ClubController@myMembership')->name('myMembership');
    Route::get('membership-club-plans', 'ClubController@membershipClubPlan')->name('membershipClubPlan');
    Route::post('transfer-coin-to-club', 'ClubController@transferCoinToClub')->name('transferCoinToClub');
    Route::post('transfer-coin-to-wallet', 'ClubController@transferCoinToWallet')->name('transferCoinToWallet');

    //withdrawal coin external
//    Route::get('withdrawal-coin', 'CoinController@withdrawalCoin')->name('withdrawalCoin');
//    Route::get('default-coin-withdrawal-history', 'CoinController@defaultWithdrawalHistory')->name('defaultWithdrawalHistory');
//    Route::get('withdrawal-coin/check-balance/{balance}', 'CoinController@checkBalance')->name('checkBalance');
//    Route::get('withdrawal-coin/cancel-withdrawal', 'CoinController@withdrawalCancelCallback')->name('withdrawalCancelCallback');

    Route::get('request-coin', 'CoinController@requestCoin')->name('requestCoin');
    Route::get('give-coin-history', 'CoinController@giveCoinHistory')->name('giveCoinHistory');
    Route::get('received-coin-history', 'CoinController@receiveCoinHistory')->name('receiveCoinHistory');
    Route::get('pending-request', 'CoinController@pendingRequest')->name('pendingRequest');
    Route::post('send-coin-request', 'CoinController@sendCoinRequest')->name('sendCoinRequest');
    Route::post('give-coin', 'CoinController@giveCoin')->name('giveCoin');
    Route::get('accept-coin-request-{id}', 'CoinController@acceptCoinRequest')->name('acceptCoinRequest');
    Route::get('decline-coin-request-{id}', 'CoinController@declineCoinRequest')->name('declineCoinRequest');

    Route::group(['middleware'=> ['co-wallet']], function () {
        Route::any('/wallet-import', 'WalletController@importWallet')->name('importWallet');
        Route::get('/wallet/{id}/users', 'WalletController@coWalletUsers')->name('coWalletUsers');
        Route::get('/withdraw/{id}/approvals', 'WalletController@coWalletApprovals')->name('coWalletApprovals');
        Route::get('/approve/withdraw/{id}', 'WalletController@approveCoWalletWithdraw')->name('approveCoWalletWithdraw');
        Route::get('/reject/withdraw/{id}', 'WalletController@rejectCoWalletWithdraw')->name('rejectCoWalletWithdraw');
    });
});

Route::group(['middleware'=> ['auth', 'lang']], function () {
    Route::post('/upload-profile-image', 'user\ProfileController@uploadProfileImage')->name('uploadProfileImage');
    Route::post('/user-profile-update', 'user\ProfileController@userProfileUpdate')->name('userProfileUpdate');
    Route::post('/phone-verify', 'user\ProfileController@phoneVerify')->name('phoneVerify');
    Route::get('/send-sms-for-verification', 'user\ProfileController@sendSMS')->name('sendSMS');
    Route::post('change-password-save', 'user\ProfileController@changePasswordSave')->name('changePasswordSave');
});
