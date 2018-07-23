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

use DocsLab\IPay88\Event\PaymentResponseEvent;
use DocsLab\IPay88\Helper;
use DocsLab\IPay88\PaymentMessageAbstract;
use DocsLab\IPay88\ResponseMessageInterface;

/**
 * The payment response from iPay88 Online Payment Switching Gateway.
 *
 * @see \DocsLab\IPay88\Event\PaymentResponseEvent
 * @see \DocsLab\IPay88\Message\PaymentRequestMessage
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class PaymentResponseMessage extends PaymentMessageAbstract implements ResponseMessageInterface {

  /**
   * {@inheritdoc}
   */
  const EVENT_CLASS = PaymentResponseEvent::class;

  /**
   * {@inheritdoc}
   *
   * epayment/entry.asp when request error occurs (Signature not match,...)
   * ePayment/Payresult_SendNotificationDomain.asp when customer proceeds to
   * payment page
   */
  const MESSAGE_URL = 'https://www.mobile88.com/epayment/entry.asp';
  //https://www.mobile88.com/epayment/PaymentCancel.asp
  //https://www.mobile88.com/ePayment/Payresult_SendNotificationDomain.asp

  /**
   * {@inheritdoc}
   */
  const RELATED_MESSAGE_CLASS = PaymentRequestMessage::class;

  /**
   * The credit card authorization code (iPay88 OPSG parameter: "AuthCode").
   *
   * @var string|NULL
   */
  protected $credit_card_authorization_code = NULL;

  /**
   * The credit card holder name (iPay88 OPSG parameter: "CCName").
   *
   * @var string|NULL
   */
  protected $credit_card_holder_name = NULL;

  /**
   * The credit card issuer country (iPay88 OPSG parameter: "S_country").
   *
   * @var string|NULL
   */
  protected $credit_card_issuer_country = NULL;

  /**
   * The credit card issuer name (iPay88 OPSG parameter: "S_bankname").
   *
   * @var string|NULL
   */
  protected $credit_card_issuer_name = NULL;

  /**
   * The credit card number (iPay88 OPSG parameter: "CCNo").
   *
   * The credit card number is partialy masked: only the first six and last four
   * digits are visible (e.g., "123456xxxxxx7890").
   *
   * @var string|NULL
   */
  protected $credit_card_number = NULL;

  /**
   * The payment error (iPay88 OPSG parameter: "ErrDesc").
   *
   * @var string|NULL
   */
  protected $payment_error = NULL;

  /**
   * The payment identifier (iPay88 OPSG parameter: "TransId").
   *
   * @var string
   */
  protected $payment_identifier;

  /**
   * The payment status (iPay88 OPSG parameter: "Status").
   *
   * @var string
   */
  protected $payment_status;

  /**
   * Constructs a payment response.
   *
   * @param string $seller_identifier
   *   The seller identifier (MerchantCode) provided by iPay88 OPSG.
   * @param string $payment_reference
   *   The reference (RefNo) provided to iPay88 OPSG within the payment request.
   * @param string $payment_identifier
   *   The payment identifier (TransId) returned by iPay88 OPSG.
   * @param string $payment_method
   *   The payment method (PaymentId) returned by iPay88 OPSG.
   * @param string $payment_status
   *   The payment status (Status) returned by iPay88 OPSG.
   * @param string $payment_amount
   *   The payment amount (Amount) returned by iPay88 OPSG, formatted with two
   *   decimals, dot "." as decimals separator and comma "," as thousands
   *   separators (e.g., "123,456.78").
   * @param string $payment_currency
   *   The payment currency (Currency) returned by iPay88 OPSG.
   * @param string $message_signature
   *   The message signature (Signature) returned by iPay88 OPSG.
   * @param string|NULL $payment_error
   *   The seller error (ErrDesc) returned by iPay88 OPSG.
   * @param string|NULL $payment_comment
   *   The payment comment (Remark) provided to iPay88 OPSG within the payment
   *   request.
   * @param string|NULL $credit_card_authorization_code
   *   The credit card authorization code (AuthCode) returned by iPay88 OPSG.
   * @param string|NULL $credit_card_number
   *   The credit card number (CCNo) returned by iPay88 OPSG.
   * @param string|NULL $credit_card_holder_name
   *   The credit card holder name (CCName) returned by iPay88 OPSG.
   * @param string|NULL $credit_card_issuer_name
   *   The credit card issuer name (S_bankname) returned by iPay88 OPSG.
   * @param string|NULL $credit_card_issuer_country
   *   The credit card issuer country (S_country) returned by iPay88 OPSG.
   */
  public function __construct($seller_identifier, $payment_reference, $payment_identifier, $payment_method, $payment_status, $payment_amount, $payment_currency, $message_signature, $payment_error = NULL, $payment_comment = NULL, $credit_card_authorization_code = NULL, $credit_card_number = NULL, $credit_card_holder_name = NULL, $credit_card_issuer_name = NULL, $credit_card_issuer_country = NULL) {
    $this
      ->setCreditCardAuthorizationCode($credit_card_authorization_code)
      ->setCreditCardHolderName($credit_card_holder_name)
      ->setCreditCardIssuerCountry($credit_card_issuer_country)
      ->setCreditCardIssuerName($credit_card_issuer_name)
      ->setCreditCardNumber($credit_card_number)
      ->setMessageSignature($message_signature)
      ->setPaymentAmount($payment_amount)
      ->setPaymentComment($payment_comment)
      ->setPaymentCurrency($payment_currency)
      ->setPaymentError($payment_error)
      ->setPaymentIdentifier($payment_identifier)
      ->setPaymentMethod($payment_method)
      ->setPaymentReference($payment_reference)
      ->setPaymentStatus($payment_status)
      ->setSellerIdentifier($seller_identifier);
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return sprintf('Payment response %s (reference %s)', $this->getPaymentIdentifier(), $this->getPaymentReference());
  }

  /**
   * {@inheritdoc}
   *
   * @return \DocsLab\IPay88\Message\PaymentResponseMessage
   *   A new payment response.
   */
  public static function createFromArray(array $array) {
    // Ensures that all required keys are defined to avoid PHP warnings.
    $array += ['MerchantCode' => NULL, 'RefNo' => NULL, 'TransId' => NULL, 'PaymentId' => NULL, 'Status' => NULL, 'Amount' => NULL, 'Currency' => NULL, 'Signature' => NULL, 'ErrDesc' => NULL, 'Remark' => NULL, 'AuthCode' => NULL, 'CCNo' => NULL, 'CCName' => NULL, 'S_bankname' => NULL, 'S_country' => NULL];
    return new static($array['MerchantCode'], $array['RefNo'], $array['TransId'], $array['PaymentId'], $array['Status'], Helper::formatAmountToFloat($array['Amount']), $array['Currency'], $array['Signature'], $array['ErrDesc'], $array['Remark'], $array['AuthCode'], $array['CCNo'], $array['CCName'], $array['S_bankname'], $array['S_country']);
  }

  /**
   * {@inheritdoc}
   *
   * @link https://www.mobile88.com/epayment/testing/testsignature_response_256.asp
   */
  public function generateMessageSignature($seller_private_key) {
    return hash('sha256', implode('', [
      $seller_private_key,
      $this->getSellerIdentifier(),
      $this->getPaymentMethod(),
      $this->getPaymentReference(),
      Helper::formatAmountToHashableString($this->getPaymentAmount()),
      $this->getPaymentCurrency(),
      $this->getPaymentStatus(),
    ]));
  }

  /**
   * Gets the credit card authorization code (iPay88 OPSG parameter:
   * "AuthCode").
   *
   * @return string|NULL
   *   The credit card authorization code.
   */
  public function getCreditCardAuthorizationCode() {
    return $this->credit_card_authorization_code;
  }

  /**
   * Gets the credit holder name (iPay88 OPSG parameter: "CCName").
   *
   * @return string|NULL
   *   The credit card holder name.
   */
  public function getCreditCardHolderName() {
    return $this->credit_card_holder_name;
  }

  /**
   * Gets the credit card issuer country (iPay88 OPSG parameter: "S_country").
   *
   * @return string|NULL
   *   The credit card issuer country.
   */
  public function getCreditCardIssuerCountry() {
    return $this->credit_card_issuer_country;
  }

  /**
   * Gets the credit card issuer name (iPay88 OPSG parameter: "S_bankname").
   *
   * @return string|NULL
   *   The credit card issuer.
   */
  public function getCreditCardIssuerName() {
    return $this->credit_card_issuer_name;
  }

  /**
   * Gets the credit card number (iPay88 OPSG parameter: "CCNo").
   *
   * The credit card number is partialy hidden: only the first six and last four
   * digits are visible (e.g., "123456xxxxxx7890").
   *
   * @return string|NULL
   *   The credit card number.
   */
  public function getCreditCardNumber() {
    return $this->credit_card_number;
  }

  /**
   * Gets the payment error (iPay88 OPSG parameter: "ErrDesc").
   *
   * @return string|NULL
   *   The payment error.
   */
  public function getPaymentError() {
    return $this->payment_error;
  }

  /**
   * Gets the payment identifier (iPay88 OPSG parameter: "TransId").
   *
   * @return string
   *   The payment identifier.
   */
  public function getPaymentIdentifier() {
    return $this->payment_identifier;
  }

  /**
   * Gets the payment status (iPay88 OPSG parameter: "Status").
   *
   * @return string
   *   The payment status.
   */
  public function getPaymentStatus() {
    return $this->payment_status;
  }

  /**
   * Indicates whether the payment is delayed.
   *
   * @return boolean
   *   Whether the payment is delayed.
   */
  public function isDelayed() {
    return Helper::STATUS_DELAYED == $this->getPaymentStatus();
  }

  /**
   * Indicates whether the payment is failed.
   *
   * @return boolean
   *   Whether the payment is failed.
   */
  public function isFailed() {
    return Helper::STATUS_FAILED == $this->getPaymentStatus();
  }

  /**
   * Indicates whether the payment is succeeded.
   *
   * @return boolean
   *   Whether the payment is succeeded.
   */
  public function isSucceeded() {
    return Helper::STATUS_SUCCEEDED == $this->getPaymentStatus();
  }

  /**
   * {@inheritdoc}
   */
  public static function loadValidatorMetadata(\Symfony\Component\Validator\Mapping\ClassMetadata $metadata) {
    parent::loadValidatorMetadata($metadata);

    foreach ($metadata->getPropertyMetadata('payment_method')[0]->getConstraints() as $constraint) {
      $constraint->groups[] = 'SignaturePart';
    }

    $default_group = $metadata->getDefaultGroup();

    $metadata
      // AuthCode
      ->addPropertyConstraints('credit_card_authorization_code', [
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 20,
          'maxMessage' => 'The credit card authorization code {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // CCName
      ->addPropertyConstraints('credit_card_holder_name', [
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 200,
          'maxMessage' => 'The credit card holder name {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // S_country
      ->addPropertyConstraints('credit_card_issuer_country', [
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 100,
          'maxMessage' => 'The credit card country {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // S_bankname
      ->addPropertyConstraints('credit_card_issuer_name', [
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 100,
          'maxMessage' => 'The credit card issuer name {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // CCNo
      ->addPropertyConstraints('credit_card_number', [
        // iPay88 technical specifications specifies a length of 16 digits for
        // credit card number but it should be 19 digits to be in accordance
        // with ISO/IEC 7812.
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 16,
          'maxMessage' => 'The credit card number {{ value }} must have {{ limit }} characters or less.',
        ]),
        new \Symfony\Component\Validator\Constraints\Regex([
          'message' => 'The credit card number {{ value }} must be partially hidden with just the first six and last four digits visible  (e.g., "123456xxxxxx7890").',
          'pattern' => '/^\d{6}x{2,6}\d{4}$/',
        ]),
      ])
      // ErrDesc
      ->addPropertyConstraints('payment_error', [
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 100,
          'maxMessage' => 'The error {{ value }} must have {{ limit }} characters or less.',
        ]),
//         new \Symfony\Component\Validator\Constraints\Choice([
//           'choices' => array_keys(Helper::getErrorMessages()),
//           'message' => 'The seller error {{ value }} must be a valid one: "'. implode('", "', array_keys(Helper::getErrorMessages())) . '".',
//         ]),
      ])
      // TransId
      ->addPropertyConstraints('payment_identifier', [
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 30,
          'maxMessage' => 'The payment identifier {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // PaymentId
      ->addPropertyConstraints('payment_method', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The payment method must not be blank.',
        ]),
      ])
      // Status
      ->addPropertyConstraints('payment_status', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The payment status must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'groups' => ['SignaturePart', $default_group],
          'max' => 1,
          'maxMessage' => 'The payment status {{ value }} must have {{ limit }} characters or less.',
        ]),
//         new \Symfony\Component\Validator\Constraints\Choice([
//           'choices' => array_keys(Helper::getPaymentStatuses()),
//           'groups' => ['SignaturePart'],
//           'message' => 'The payment status {{ value }} must be a valid one: "'. implode('", "', array_keys(Helper::getPaymentStatuses())) . '".',
//         ]),
      ]);
  }

  /**
   * Sets the credit card authorization code (iPay88 OPSG parameter:
   * "AuthCode").
   *
   * @param string $credit_card_authorization_code
   *   The credit card authorization code.
   *
   * @return \DocsLab\IPay88\Message\PaymentResponseMessage
   *   This payment response.
   */
  public function setCreditCardAuthorizationCode($credit_card_authorization_code) {
    $this->credit_card_authorization_code = $credit_card_authorization_code;
    return $this;
  }

  /**
   * Sets the credit card holder name (iPay88 OPSG parameter: "CCName").
   *
   * @param string $credit_card_holder_name
   *   The credit card holder name.
   *
   * @return $this
   *   This payment response.
   */
  public function setCreditCardHolderName($credit_card_holder_name) {
    $this->credit_card_holder_name = $credit_card_holder_name;
    return $this;
  }

  /**
   * Sets the credit card issuer country (iPay88 OPSG parameter: "S_country").
   *
   * @param string $credit_card_issuer_country
   *   The credit card issuer country.
   *
   * @return $this
   *   This payment response.
   */
  public function setCreditCardIssuerCountry($credit_card_issuer_country) {
    $this->credit_card_issuer_country = $credit_card_issuer_country;
    return $this;
  }

  /**
   * Sets the credit card issuer name (iPay88 OPSG parameter: "S_bankname").
   *
   * @param string $credit_card_issuer_name
   *   The credit card issuer name.
   *
   * @return $this
   *   This payment response.
   */
  public function setCreditCardIssuerName($credit_card_issuer_name) {
    $this->credit_card_issuer_name = $credit_card_issuer_name;
    return $this;
  }

  /**
   * Sets the credit card number (iPay88 OPSG parameter: "CCNo").
   *
   * The credit card number must be partialy masked: only the first six and last
   * four digits must be visible (e.g., "123456xxxxxx7890").
   *
   * @param string $credit_card_number
   *   The credit card number.
   *
   * @return $this
   *   This payment response.
   */
  public function setCreditCardNumber($credit_card_number) {
    $this->credit_card_number = $credit_card_number;
    return $this;
  }

  /**
   * Sets the payment error (iPay88 OPSG parameter: "ErrDesc").
   *
   * @param string $payment_error
   *   The payment error.
   *
   * @return $this
   *   This payment response.
   */
  public function setPaymentError($payment_error) {
    $this->payment_error = $payment_error;
    return $this;
  }

  /**
   * Sets the payment identifier (iPay88 OPSG parameter: "TransId").
   *
   * @param string $payment_identifier
   *   The payment identifier.
   *
   * @return $this
   *   This payment response.
   */
  public function setPaymentIdentifier($payment_identifier) {
    $this->payment_identifier = $payment_identifier;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setPaymentMethod($payment_method) {
    // Reset the signature as the payment method is part of the signature hash.
    $this->message_signature = NULL;
    return parent::setPaymentMethod($payment_method);
  }

  /**
   * Sets the payment status (iPay88 OPSG parameter: "Status").
   *
   * @param string $payment_status
   *   The payment status.
   *
   * @return $this
   *   This payment response.
   */
  public function setPaymentStatus($payment_status) {
    $this->payment_status = $payment_status;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'AuthCode' => $this->getCreditCardAuthorizationCode(),
      'CCName' => $this->getCreditCardHolderName(),
      'CCNo' => $this->getCreditCardNumber(),
      'ErrDesc' => $this->getPaymentError(),
      'S_bankname' => $this->getCreditCardIssuerName(),
      'S_country' => $this->getCreditCardIssuerCountry(),
      'Status' => $this->getPaymentStatus(),
      'TransId' => $this->getPaymentIdentifier(),
    ] + parent::toArray();
  }

}
