<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static EMAIL_VERIFICATION()
 */
final class UserTokenTypes extends Enum
{
    const EMAIL_VERIFICATION = 'email_verification';
}
