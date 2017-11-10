<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Zttp\Zttp;

class Recaptcha implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = Zttp::asFormParams()->post('https://www.google.com/recaptcha/api/siteverify',[
            'secret' => config('services.google_recaptcha.secret'),
            'response' => $value,
            'remoteip' => request()->ip()
        ]);

        return $response->json()['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please choose correct recaptcha.';
    }
}
