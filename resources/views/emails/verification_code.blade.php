@component('mail::message')
# Account Verification

Your verification code is: **{{ $verificationCode }}**

Please use this code to verify your account.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
