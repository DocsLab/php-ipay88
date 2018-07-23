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

use DocsLab\IPay88\Event\PaymentStatusResponseEvent;
use DocsLab\IPay88\Helper;
use DocsLab\IPay88\MessageAbstract;
use DocsLab\IPay88\ResponseMessageInterface;

/**
 * The payment status response from iPay88 Online Payment Switching Gateway.
 *
 * @see \DocsLab\IPay88\Event\PaymentStatusResponseEvent
 * @see \DocsLab\IPay88\Message\PaymentStatusRequestMessage
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class PaymentStatusResponseMessage extends MessageAbstract implements ResponseMessageInterface {

  /**
   * {@inheritdoc}
   */
  const EVENT_CLASS = PaymentStatusResponseEvent::class;

  /**
   * {@inheritdoc}
   */
  const MESSAGE_URL = 'https://www.mobile88.com/epayment/enquiry.asp';

  /**
   * {@inheritdoc}
   */
  const RELATED_MESSAGE_CLASS = PaymentStatusRequestMessage::class;

  /**
   * The payment status message.
   *
   * @see \DocsLab\IPay88\Helper::getPaymentStatusMessages()
   *
   * @var string
   */
  protected $payment_status_message;

  /**
   * Constructs a payment status response.
   *
   * @param string $payment_status_message
   *   The payment status message returned by iPay88 OPSG.
   */
  public function __construct($payment_status_message) {
    $this->setPaymentStatusMessage($payment_status_message);
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return sprintf(
      'Payment status response "%s"',
      Helper::getStatusMessage($this->getPaymentStatusMessage())['label']
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function createFromArray(array $array) {
    return new static(array_pop($array));
  }

  /**
   * Gets the payment status message.
   *
   * @see \DocsLab\IPay88\Helper::getPaymentStatusMessage() for a
   *   definition of the payment status message.
   *
   * @return string
   *  The payment status message.
   */
  public function getPaymentStatusMessage() {
    return $this->payment_status_message;
  }

  /**
   * Indicates whether the payment is canceled.
   *
   * @return boolean
   *   Whether the payment is canceled.
   */
  public function isCanceled() {
    return Helper::STATUS_MESSAGE_IPAY88_CANCELED == $this->getPaymentStatusMessage();
  }

  /**
   * Indicates whether the related payment status request was errored.
   *
   * @return boolean
   *   Whether the related payment status request was errored.
   */
  public function isErrored() {
    return Helper::STATUS_ERRORED == Helper::getStatusMessage($this->getPaymentStatusMessage())['status'];
  }

  /**
   * Indicates whether the payment failed.
   *
   * @return boolean
   *   Whether the payment failed.
   */
  public function isFailed() {
    return Helper::STATUS_FAILED == Helper::getStatusMessage($this->getPaymentStatusMessage())['status'];
  }

  /**
   * {@inheritdoc}
   */
  public static function isMessageSignatureRequired() {
    return FALSE;
  }

  /**
   * Indicates whether the payment succeeded.
   *
   * @return boolean
   *   Whether the payment succeeded.
   */
  public function isSucceeded() {
    return Helper::STATUS_SUCCEEDED == Helper::getStatusMessage($this->getPaymentStatusMessage())['status'];
  }

  /**
   * {@inheritdoc}
   */
  public static function loadValidatorMetadata(\Symfony\Component\Validator\Mapping\ClassMetadata $metadata) {
    $metadata
      // PaymentStatusMessage
      ->addPropertyConstraints('payment_status_message', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The payment status message must not be blank.',
        ]),
      ]);
  }

  /**
   * Sets the payment status message.
   *
   * @param string $payment_status_message
   *  The payment status message.
   *
   * @return $this
   *   This payment status response.
   */
  public function setPaymentStatusMessage($payment_status_message) {
    $this->payment_status_message = $payment_status_message;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return ['PaymentStatusMessage' => $this->getPaymentStatusMessage()];
  }

}
