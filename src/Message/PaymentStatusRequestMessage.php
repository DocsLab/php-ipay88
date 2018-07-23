<?php

/*
 * This file is part of the docslab/ipay88 package, a PHP client for iPay88
 * Online Payment Switching Gateway.
 *
 * Copyright (C) 2018 Maxime Gilbert (DocBu) <docbu@docslab.net>
 *
 * This package is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * This package is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace DocsLab\IPay88\Message;

use DocsLab\IPay88\Event\PaymentStatusRequestEvent;
use DocsLab\IPay88\Helper;
use DocsLab\IPay88\MessageAbstract;
use DocsLab\IPay88\RequestMessageInterface;

/**
 * The payment status request to iPay88 Online Payment Switching Gateway.
 *
 * @see \DocsLab\IPay88\Event\PaymentStatusRequestEvent
 * @see \DocsLab\IPay88\Message\PaymentStatusResponseMessage
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class PaymentStatusRequestMessage extends MessageAbstract implements RequestMessageInterface {

  /**
   * {@inheritdoc}
   */
  const EVENT_CLASS = PaymentStatusRequestEvent::class;

  /**
   * {@inheritdoc}
   */
  const MESSAGE_URL = 'https://www.mobile88.com/epayment/enquiry.asp';

  /**
   * {@inheritdoc}
   */
  const RELATED_MESSAGE_CLASS = PaymentStatusResponseMessage::class;

  /**
   * Constructs a payment status request.
   *
   * @param string $seller_identifier
   *   The seller identifier (MerchantCode) provided by iPay88.
   * @param string $payment_reference
   *   The payment reference (RefNo) identifying the original payment request.
   * @param string $payment_amount
   *   The payment amount (Amount) with two decimals, dot "." as decimals
   *   separator and comma "," as thousands separators (e.g., "123,456.78").
   */
  public function __construct($seller_identifier, $payment_reference, $payment_amount) {
    $this
      ->setPaymentAmount($payment_amount)
      ->setPaymentReference($payment_reference)
      ->setSellerIdentifier($seller_identifier);
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return sprintf('Payment status request %s', $this->getPaymentReference());
  }

  /**
   * {@inheritdoc}
   *
   * @return \DocsLab\IPay88\Message\PaymentStatusRequestMessage
   *   A new payment status request.
   */
  public static function createFromArray(array $array) {
    return new static($array['MerchantCode'], $array['RefNo'], Helper::formatAmountToFloat($array['Amount']));
  }

}
