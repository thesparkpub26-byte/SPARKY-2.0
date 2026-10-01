<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

/**
 * Turns away the passwords attackers try first: the usual worst offenders, the site's own name, and
 * anything built from the person's own email or name. Combine with {@see self::rules()} for the full policy.
 */
class NotCommonPassword implements DataAwareRule, ValidationRule
{
    private const COMMON = [
        'password', 'password1', 'password12', 'password123', 'passw0rd', 'p@ssw0rd', 'qwerty', 'qwerty12', 'qwerty123',
        'qwertyuiop', '12345678', '123456789', '1234567890', '11111111', '00000000', 'abcd1234', 'abc12345', 'abc123456',
        'iloveyou', 'iloveyou1', 'admin123', 'administrator', 'welcome1', 'welcome123', 'letmein123', 'monkey123',
        'dragon123', 'football1', 'baseball1', 'sunshine1', 'princess1', 'superman1', 'trustno1', 'spark123', 'thespark',
        'thespark1', 'thespark123', 'cspc1234', 'cspc12345', 'cspcnabua', 'nabua1234', 'camsur123',
    ];

    /** @var array<string, mixed> */
    private array $data = [];

    /** The whole password policy: 8+ characters, letters and numbers, nothing predictable. */
    public static function rules(): array
    {
        return [Password::min(8)->letters()->numbers(), new self()];
    }

    /** Receives the other form fields (name, email) so passwords built from them can be refused. */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /** Refuses passwords that are on the common list or too easy to guess. */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = strtolower((string) $value);
        $squashed = preg_replace('/[^a-z0-9]/', '', $password);

        if (in_array($password, self::COMMON, true) || in_array($squashed, self::COMMON, true)) {
            $fail('That password is too common. Please choose something harder to guess.');
            return;
        }

        // Nothing built from the person's own email or name
        $email = strtolower((string) ($this->data['email'] ?? ''));
        $local = strstr($email, '@', true) ?: $email;
        $name = preg_replace('/[^a-z0-9]/', '', strtolower((string) ($this->data['name'] ?? '')));

        foreach ([$local, $name] as $personal) {
            $personal = preg_replace('/[^a-z0-9]/', '', (string) $personal);
            if (strlen($personal) >= 4 && str_contains($squashed, $personal)) {
                $fail('Your password should not contain your name or email.');
                return;
            }
        }
    }
}
