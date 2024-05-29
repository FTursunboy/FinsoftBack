<?php

namespace App\Rules;

use App\Models\ExchangeRate;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class CodeRule implements Rule
{
    public $codeVerification;
    public User $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->user = User::getByPhone(request()->input('phone'))->firstOrFail();
        $this->codeVerification = VerificationCode::query()
            ->where('user_id', $this->user?->id)
            ->latest()
            ->firstOrFail()
            ->getModel();

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (
            $value == $this->codeVerification?->code
        ) {
            $this->user->codes()->delete();
            return true;
        }

        $this->codeVerification->increment('attempts');

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->codeVerification->attempts > VerificationCode::MAX_ATTEMPTS) {
            $this->user?->codes()->delete();
            return __('validation.code_expired');
        }

        return __('validation.code_invalid', [
            'attempts' => VerificationCode::MAX_ATTEMPTS - $this->codeVerification->attempts,
        ]);
    }
}
