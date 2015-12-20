<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Service;

/**
 * SinchErrorCode
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
final class SinchErrorCode
{
    const PARAMETER_VALIDATION = 40001;
    const MISSING_PARAMETER    = 40002;
    const INVALID_REQUEST      = 40003;

    const ILLEGAL_AUTHORIZATION_HEADER = 40100;

    const THERE_IS_NOT_ENOUGH_FUNDS_TO_SEND_THE_MESSAGE = 40200;

    const FORBIDDEN_REQUEST                                       = 40300;
    const INVALID_AUTHORIZATION_SCHEME_FOR_CALLING_THE_METHOD     = 40301;
    const NO_VERIFIED_PHONE_NUMBER_ON_YOUR_SINCH_ACCOUNT          = 40303;
    const SANDBOX_SMS_ONLY_ALLOWED_TO_BE_SENT_TO_VERIFIED_NUMBERS = 40303;

    const INTERNAL_ERROR = 50000;
}
