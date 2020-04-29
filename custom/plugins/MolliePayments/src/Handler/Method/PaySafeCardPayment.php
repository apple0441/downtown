<?php

namespace Kiener\MolliePayments\Handler\Method;

use Kiener\MolliePayments\Handler\PaymentHandler;
use Mollie\Api\Types\PaymentMethod;

class PaySafeCardPayment extends PaymentHandler
{
    public const PAYMENT_METHOD_NAME = PaymentMethod::PAYSAFECARD;
    public const PAYMENT_METHOD_DESCRIPTION = 'paysafecard';

    /** @var string */
    protected $paymentMethod = self::PAYMENT_METHOD_NAME;
}