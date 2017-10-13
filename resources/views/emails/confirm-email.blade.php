@component('mail::message')
# Introduction

Please confirm your email address.

@component('mail::button', ['url' => url('register/confirm?token=' . $user->confirmation_token)])
COnfirm Your Email Address.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
