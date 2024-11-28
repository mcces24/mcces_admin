<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Mews\Captcha\Facades\Captcha;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $this->ensureIsNotRateLimited();
        
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        $attempts = session()->get('login_attempts');
        if ($attempts >= 3) {
            $rules['captcha'] = 'required|captcha';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'The email address you entered is invalid.',
            'password.required' => 'Please enter your password.',
            'captcha.required' => 'The CAPTCHA is required.',
            'captcha.captcha' => 'The CAPTCHA is incorrect.',
        ];
    }
    
     /**
     * Override failedValidation to return a 200 status with custom errors.
     */
    protected function failedValidation(Validator $validator)
    {
        // Throw HttpResponseException with 200 status code
        throw new HttpResponseException(
            response()->json([
                'status' => 'failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200)
        );
    }

    public function generateCaptcha()
    {
        // Custom CAPTCHA generation: 5 or 6 alphanumeric characters
        return Captcha::create('default', true)->setLength(5)->setChars('23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ');
    }
    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $attempts = RateLimiter::attempts($this->throttleKey());
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
    
            // Check if the email exists in the database
            $user = \App\Models\Admin::where('email', $this->email)->first();
    
            // Throw different error messages based on whether the user exists
            if ($user) {
                $message = trans('auth.password'); // Message for incorrect password
            } else {
                $message = trans('auth.failed'); // Message indicating email doesn't exist
            }

            return response()->json(['status' => 'failed', 'message' => $message, 'attempts' => $attempts]);
        }

        RateLimiter::clear($this->throttleKey());

        return response()->json(['status' => 'success', 'message' => 'Login successful. Redirecting...']);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $attempts = RateLimiter::attempts($this->throttleKey());
        if ($attempts >= 3) {
            session()->put('login_attempts', $attempts);
        }
       
        // Check if the user has exceeded the maximum allowed attempts for their current time window
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            // If too many attempts have been made, trigger the lockout event
            event(new Lockout($this));

            // Retrieve the number of seconds left until the user can try again
            $seconds = RateLimiter::availableIn($this->throttleKey());

            // Throw an HttpResponseException with the throttle information
            throw new HttpResponseException(
                response()->json([
                    'status' => 'failed',
                    'message' => trans('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60), // Display time in minutes, rounded up
                    ]),
                    'attempts' => $attempts,
                ], 200)
            );
        }

        RateLimiter::hit($this->throttleKey(), decaySeconds: 200); // Increment the login attempts
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
