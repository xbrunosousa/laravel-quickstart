<?php

$appName = env('APP_NAME');

return [
    'verify_your_account' => 'Verify your account',
    'email_verification_sended' => 'Email verification sended',
    'invalid_link_confirmation' => 'Invalid or expired confirmation link',
    'account_successfully_activated' => 'Account successfully activated',
    'login_fail' => 'Invalid email or password',
    'sended_email_forgot_pwd' => 'If this email exists on our system, a link will be sent to create a new password.',
    'email_subject_forgot' => "Reset your password in {$appName}",
    'token_reset_pwd_invalid' => 'Invalid or expired link',
    'email_subject_confirm_reset_pwd' => "Your password in {$appName} has been reseted",
    'reseted_pwd' => 'Your password has been reseted',
    'email' => [
        'salutation' => 'Hello',
        'click_here' => 'Click here',
        'to_activate_account' => 'to activate your accont',
        'to_reset_pwd' => 'to create a new password',
        'reseted_password_body' => 'Your password has been reset. If you are unaware of this action, please contact us'
    ]
];
