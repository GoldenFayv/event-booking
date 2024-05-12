<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACCOUNT_CREATION()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MailTypes extends Enum
{
    const ACCOUNT_CREATION = 'Account Creation';
    const EMAIL_VERIFICATION = 'E-Mail Verification';
}
