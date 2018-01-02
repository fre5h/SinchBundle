<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fresh\SinchBundle\Helper;

/**
 * SinchErrorCode.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
final class SinchErrorCode
{
    public const PARAMETER_VALIDATION = 40001;

    public const MISSING_PARAMETER = 40002;

    public const INVALID_REQUEST = 40003;

    public const ILLEGAL_AUTHORIZATION_HEADER = 40100;

    public const THERE_IS_NOT_ENOUGH_FUNDS_TO_SEND_THE_MESSAGE = 40200;

    public const FORBIDDEN_REQUEST = 40300;

    public const INVALID_AUTHORIZATION_SCHEME_FOR_CALLING_THE_METHOD = 40301;

    public const NO_VERIFIED_PHONE_NUMBER_ON_YOUR_SINCH_ACCOUNT = 40303;

    public const SANDBOX_SMS_ONLY_ALLOWED_TO_BE_SENT_TO_VERIFIED_NUMBERS = 40303;

    public const INTERNAL_ERROR = 50000;

    /**
     * Constructor.
     */
    private function __construct()
    {
    }
}
