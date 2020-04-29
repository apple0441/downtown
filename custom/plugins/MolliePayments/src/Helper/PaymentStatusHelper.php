<?php

namespace Kiener\MolliePayments\Helper;

use Mollie\Api\Resources\Order;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Types\PaymentStatus;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\Context;

class PaymentStatusHelper
{
    protected $orderTransactionStateHandler;

    /**
     * PaymentStatusHelper constructor.
     *
     * @param OrderTransactionStateHandler $orderTransactionStateHandler
     */
    public function __construct(OrderTransactionStateHandler $orderTransactionStateHandler)
    {
        $this->orderTransactionStateHandler = $orderTransactionStateHandler;
    }

    /**
     * Processes the payment status for a Mollie Order. Uses the transaction state handler
     * to handle the transaction to a new status.
     *
     * @param string $transactionId
     * @param OrderEntity $order
     * @param Order $mollieOrder
     * @param Context $context
     * @return bool
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     * @throws \Shopware\Core\System\StateMachine\Exception\IllegalTransitionException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineInvalidEntityIdException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineInvalidStateFieldException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineNotFoundException
     */
    public function processPaymentStatus(
        string $transactionId,
        OrderEntity $order,
        Order $mollieOrder,
        Context $context
    ) : string
    {
        $paidNumber = 0;
        $authorizedNumber = 0;
        $cancelledNumber = 0;
        $expiredNumber = 0;
        $failedNumber = 0;
        $pendingNumber = 0;
        $payments = $mollieOrder->payments();
        $paymentsTotal = $payments !== null ? $payments->count() : 0;

        /**
         * We gather the states for all payments in order to handle
         * the states of all payments in this order.
         */
        if ($payments !== null && $payments->count() > 0) {
            /** @var Payment $payment */
            foreach ($payments as $payment) {
                if ($payment->isPaid()) {
                    $paidNumber++;
                }

                if ($payment->isCanceled()) {
                    $cancelledNumber++;
                }

                if ($payment->isExpired()) {
                    $expiredNumber++;
                }

                if ($payment->isFailed()) {
                    $failedNumber++;
                }

                if ($payment->isPending()) {
                    $pendingNumber++;
                }

                if ($payment->isAuthorized()) {
                    $authorizedNumber++;
                }
            }
        }

        /**
         * The order is paid.
         */
        if ($mollieOrder->isPaid()) {
            $this->orderTransactionStateHandler->pay($transactionId, $context);
            return PaymentStatus::STATUS_PAID;
        }

        /**
         * The order is cancelled.
         */
        if ($mollieOrder->isCanceled()) {
            $this->orderTransactionStateHandler->cancel($transactionId, $context);
            return PaymentStatus::STATUS_CANCELED;
        }

        /**
         * All payments are authorized, therefore the order payment is authorized. We
         * transition to paid as Shopware 6 has no transition to a authorized state (yet).
         */
        if ($authorizedNumber > 0 && $authorizedNumber === $paymentsTotal) {
            return PaymentStatus::STATUS_OPEN;
        }

        /**
         * All payments are cancelled, therefore the order payment is canceled.
         */
        if ($cancelledNumber > 0 && $cancelledNumber === $paymentsTotal) {
            $this->orderTransactionStateHandler->cancel($transactionId, $context);
            return PaymentStatus::STATUS_CANCELED;
        }

        /**
         * All payments expired, therefore the order payment expired. We transition
         * to cancelled as Shopware 6 has no transition to a expired state (yet).
         */
        if ($expiredNumber > 0 && $expiredNumber === $paymentsTotal) {
            $this->orderTransactionStateHandler->cancel($transactionId, $context);
            return PaymentStatus::STATUS_CANCELED;
        }

        /**
         * All payments failed, therefore the order payment failed. We transition
         * to cancelled as Shopware 6 has no transition to a failed state (yet).
         */
        if ($failedNumber > 0 && $failedNumber === $paymentsTotal) {
            $this->orderTransactionStateHandler->cancel($transactionId, $context);
            return PaymentStatus::STATUS_CANCELED;
        }

        /**
         * All payments are pending, therefore the order payment is pending. We
         * transition to paid as Shopware 6 has no transition to a pending state (yet).
         */
        if ($pendingNumber > 0 && $pendingNumber === $paymentsTotal) {
            return PaymentStatus::STATUS_PAID;
        }

        /**
         * The paid amount is equal to the total amount, therefore the order is paid.
         */
        if ($paidNumber > 0 && $paidNumber === $order->getAmountTotal()) {
            $this->orderTransactionStateHandler->pay($transactionId, $context);
            return PaymentStatus::STATUS_PAID;
        }

        return PaymentStatus::STATUS_OPEN;
    }
}