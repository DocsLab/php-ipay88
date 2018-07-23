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

use DocsLab\IPay88\Event\PaymentRequestEvent;
use DocsLab\IPay88\Helper;
use DocsLab\IPay88\PaymentMessageAbstract;
use DocsLab\IPay88\RequestMessageInterface;

/**
 * The payment request to iPay88 Online Payment Switching Gateway.
 *
 * @see \DocsLab\IPay88\Event\PaymentRequestEvent
 * @see \DocsLab\IPay88\Message\PaymentResponseMessage
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class PaymentRequestMessage extends PaymentMessageAbstract implements RequestMessageInterface {

  /**
   * {@inheritdoc}
   */
  const EVENT_CLASS = PaymentRequestEvent::class;

  /**
   * {@inheritdoc}
   */
  const MESSAGE_SIGNATURE_TEST_URL = 'https://www.mobile88.com/epayment/testing/testsignature_256.asp';

  /**
   * {@inheritdoc}
   */
  const MESSAGE_URL = 'https://www.mobile88.com/epayment/entry.asp';

  /**
   * {@inheritdoc}
   */
  const RELATED_MESSAGE_CLASS = PaymentResponseMessage::class;

  /**
   * The customer email address (iPay88 OPSG parameter: "UserEmail").
   *
   * @var string
   */
  protected $customer_email_address;

  /**
   * The customer name (iPay88 OPSG parameter: "UserName").
   *
   * @var string
   */
  protected $customer_name;

  /**
   * The customer phone number (iPay88 OPSG parameter: "UserContact").
   *
   * @var string
   */
  protected $customer_phone_number;

  /**
   * The message character encoding (iPay88 OPSG parameter: "Lang").
   *
   * @var string
   */
  protected $message_character_encoding;

  /**
   * The message signature type (iPay88 OPSG parameter: "SignatureType").
   *
   * @var string
   */
  protected $message_signature_type;

  /**
   * The notify URL (iPay88 OPSG parameter: "BackendURL").
   *
   * @var string
   */
  protected $notify_url;

  /**
   * The payment description (iPay88 OPSG parameter: "ProdDesc").
   *
   * @var string|NULL
   */
  protected $payment_description;

  /**
   * The return URL (iPay88 OPSG parameter: "ResponseURL").
   *
   * @var string
   */
  protected $return_url;

  /**
   * Constructs a payment request.
   *
   * @param string $seller_identifier
   *   The seller identifier (MerchantCode) provided by iPay88.
   * @param string $payment_reference
   *   The payment reference (RefNo) identifying the request.
   * @param string $payment_amount
   *   The payment amount (Amount) with two decimals, dot "." as decimals
   *   separator and comma "," as thousands separators (e.g., "123,456.78").
   * @param string $payment_currency
   *   The payment currency (Currency).
   * @param string $payment_description
   *   The payment description (ProdDesc).
   * @param string $customer_name
   *   The customer name (UserName).
   * @param string $customer_email_address
   *   The customer email address (UserEmail).
   * @param string $customer_phone_number
   *   The customer phone number (UserContact).
   * @param string $message_character_encoding
   *   The message character encoding (Lang).
   * @param string $message_signature_type
   *   The message signature type (SignatureType).
   * @param string $return_url
   *   The return URL (ResponseURL).
   * @param string $notify_url
   *   The notify URL (BackendURL).
   * @param string $payment_method
   *   The payment method (PaymentId).
   * @param string $payment_comment
   *   The payment comment (Remark).
   */
  public function __construct($seller_identifier, $payment_reference, $payment_amount, $payment_currency, $payment_description, $customer_name, $customer_email_address, $customer_phone_number, $message_character_encoding, $message_signature_type, $return_url, $notify_url, $payment_method = NULL, $payment_comment = NULL) {
    $this
      ->setCustomerEmailAddress($customer_email_address)
      ->setCustomerName($customer_name)
      ->setCustomerPhoneNumber($customer_phone_number)
      ->setMessageCharacterEncoding($message_character_encoding)
      ->setMessageSignatureType($message_signature_type)
      ->setNotifyUrl($notify_url)
      ->setPaymentAmount($payment_amount)
      ->setPaymentComment($payment_comment)
      ->setPaymentCurrency($payment_currency)
      ->setPaymentDescription($payment_description)
      ->setPaymentMethod($payment_method)
      ->setPaymentReference($payment_reference)
      ->setReturnUrl($return_url)
      ->setSellerIdentifier($seller_identifier);
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return sprintf('Payment request %s', $this->getPaymentReference());
  }

  /**
   * {@inheritdoc}
   *
   * @return \DocsLab\IPay88\Message\PaymentRequestMessage
   *   A new payment request.
   */
  public static function createFromArray(array $array) {
    return new static($array['MerchantCode'], $array['RefNo'], Helper::formatAmountToFloat($array['Amount']), $array['Currency'], $array['ProdDesc'], $array['UserName'], $array['UserEmail'], $array['UserContact'], $array['Lang'], $array['SignatureType'], $array['ResponseURL'], $array['BackendURL'], $array['PaymentId'], $array['Remark']);
  }

  /**
   * {@inheritdoc}
   */
  public function generateMessageSignature($seller_private_key) {
    if ('SHA256' == $this->message_signature_type) {
      return hash('sha256', implode('', [
        $seller_private_key,
        $this->getSellerIdentifier(),
        $this->getPaymentReference(),
        Helper::formatAmountToHashableString($this->getPaymentAmount()),
        $this->getPaymentCurrency()
      ]));
    }
    return NULL;
  }

  /**
   * Gets the customer email address (iPay88 OPSG parameter: "UserEmail").
   *
   * @return string
   *   The customer email address.
   */
  public function getCustomerEmailAddress() {
    return $this->customer_email_address;
  }

  /**
   * Gets the customer name (iPay88 OPSG parameter: "UserName").
   *
   * @return string
   *   The customer name.
   */
  public function getCustomerName() {
    return $this->customer_name;
  }

  /**
   * Gets the customer phone number (iPay88 OPSG parameter: "UserContact").
   *
   * @return string
   *   The customer phone number.
   */
  public function getCustomerPhoneNumber() {
    return $this->customer_phone_number;
  }

  /**
   * Gets the message character encoding (iPay88 OPSG parameter: "Lang").
   *
   * @return string
   *   The message character encoding.
   */
  public function getMessageCharacterEncoding() {
    return $this->message_character_encoding;
  }

  /**
   * Gets the message signature type (iPay88 OPSG parameter: "SignatureType").
   *
   * @return string
   *   The message signature type.
   */
  public function getMessageSignatureType() {
    return $this->message_signature_type;
  }

  /**
   * Gets the notify URL (iPay88 OPSG parameter: "BackendURL").
   *
   * @return string
   *   The notify URL.
   */
  public function getNotifyUrl() {
    return $this->notify_url;
  }

  /**
   * Gets the payment description (iPay88 OPSG parameter: "ProdDesc").
   *
   * @return string
   *   The payment description.
   */
  public function getPaymentDescription() {
    return $this->payment_description;
  }

  /**
   * Gets the return URL (iPay88 OPSG parameter: "ResponseURL").
   *
   * @return string
   *   The return URL.
   */
  public function getReturnUrl() {
    return $this->return_url;
  }

  /**
   * {@inheritdoc}
   */
  public static function loadValidatorMetadata(\Symfony\Component\Validator\Mapping\ClassMetadata $metadata) {
    parent::loadValidatorMetadata($metadata);

    $default_group = $metadata->getDefaultGroup();

    $metadata
      // UserEmail
      ->addPropertyConstraints('customer_email_address', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The customer email address must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 100,
          'maxMessage' => 'The customer email address {{ value }} must have {{ limit }} characters or less.',
        ]),
        new \Symfony\Component\Validator\Constraints\Email([
          'message' => 'The customer email address {{ value }} must be a valid email address.',
        ]),
      ])
      // UserName
      ->addPropertyConstraints('customer_name', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The customer name must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 100,
          'maxMessage' => 'The customer name {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // UserContact
      ->addPropertyConstraints('customer_phone_number', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The customer phone number must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 20,
          'maxMessage' => 'The customer phone number {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // ProdDesc
      ->addPropertyConstraints('payment_description', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The payment description must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 100,
          'maxMessage' => 'The payment description {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // Lang
      ->addPropertyConstraints('message_character_encoding', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The message encoding must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 20,
          'maxMessage' => 'The message encoding {{ value }} must have {{ limit }} characters or less.',
        ]),
        new \Symfony\Component\Validator\Constraints\Choice([
          'choices' => array_keys(Helper::getCharacterEncodings()),
          'message' => 'The message encoding {{ value }} must be a valid one: "'. implode('", "', array_keys(Helper::getCharacterEncodings())) . '".',
        ]),
      ])
      // BackendURL
      ->addPropertyConstraints('notify_url', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The notify URL must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 200,
          'maxMessage' => 'The notify URL {{ value }} must have {{ limit }} characters or less.',
        ]),
        new \Symfony\Component\Validator\Constraints\Url([
          'message' => 'The notify URL {{ value }} must be a valid URL.',
        ]),
      ])
      // PaymentId
      ->addPropertyConstraints('payment_method', [
        new \Symfony\Component\Validator\Constraints\Choice([
          'choices' => array_keys(Helper::getSupportedMethods()),
          'message' => 'The payment method {{ value }} must be a valid one: "'. implode('", "', array_keys(Helper::getSupportedMethods())) . '".',
        ]),
      ])
      // ResponseURL
      ->addPropertyConstraints('return_url', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The return URL must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 200,
          'maxMessage' => 'The return URL {{ value }} must have {{ limit }} characters or less.',
        ]),
        new \Symfony\Component\Validator\Constraints\Url([
          'message' => 'The return URL {{ value }} must be a valid URL.',
        ]),
      ])
      // SignatureType
      ->addPropertyConstraints('message_signature_type', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The message signature type must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'groups' => ['SignaturePart', $default_group],
          'max' => 10,
          'maxMessage' => 'The message signature type {{ value }} must have {{ limit }} characters or less.',
        ]),
        new \Symfony\Component\Validator\Constraints\Choice([
          'choices' => array_keys(Helper::getSignatureTypes()),
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The message signature type {{ value }} must be a valid one: "'. implode('", "', array_keys(Helper::getSignatureTypes())) . '".',
        ]),
      ]);
  }

  /**
   * Sets the customer email address (iPay88 OPSG parameter: "UserEmail").
   *
   * @param string $customer_email_address
   *   The customer email address.
   *
   * @return $this
   *   This payment request.
   */
  public function setCustomerEmailAddress($customer_email_address) {
    $this->customer_email_address = $customer_email_address;
    return $this;
  }

  /**
   * Sets the customer name (iPay88 OPSG parameter: "UserName").
   *
   * @param string $customer_name
   *   The customer name.
   *
   * @return $this
   *   This payment request.
   */
  public function setCustomerName($customer_name) {
    $this->customer_name = $customer_name;
    return $this;
  }

  /**
   * Sets the customer phone number (iPay88 OPSG parameter: "UserContact").
   *
   * @param string $customer_phone_number
   *   The customer phone number.
   *
   * @return $this
   *   This payment request.
   */
  public function setCustomerPhoneNumber($customer_phone_number) {
    $this->customer_phone_number = $customer_phone_number;
    return $this;
  }

  /**
   * Sets the message character encoding (iPay88 OPSG parameter: "Lang").
   *
   * @param string $message_character_encoding
   *   The message character encoding.
   *
   * @return $this
   *   This payment request.
   */
  public function setMessageCharacterEncoding($message_character_encoding) {
    $this->message_character_encoding = $message_character_encoding;
    return $this;
  }

  /**
   * Sets the message signature type (iPay88 parameter: "SignatureType").
   *
   * The message signature must be generated afterward with
   * {@link \DocsLab\IPay88\Message\PaymentRequestMessage::generateMessageSignature()}.
   *
   * @param string $message_signature_type
   *   The message signature type.
   *
   * @return $this
   *   This payment request.
   */
  public function setMessageSignatureType($message_signature_type) {
    // Reset the message signature as it is dependent to the message signature
    // type.
    $this->message_signature = NULL;
    $this->message_signature_type = $message_signature_type;
    return $this;
  }

  /**
   * Sets the notify URL (iPay88 OPSG parameter: "BackendURL").
   *
   * @param string $notify_url
   *   The notify URL.
   *
   * @return $this
   *   This payment request.
   */
  public function setNotifyUrl($notify_url) {
    $this->notify_url = $notify_url;
    return $this;
  }

  /**
   * Sets the payment description (iPay88 OPSG parameter: "ProdDesc").
   *
   * @param string $payment_description
   *   The payment description.
   *
   * @return $this
   *   This payment request.
   */
  public function setPaymentDescription($payment_description) {
    $this->payment_description = $payment_description;
    return $this;
  }

  /**
   * Sets the return URL (iPay88 OPSG parameter: "ResponseURL").
   *
   * @param string $return_url
   *   The return URL.
   *
   * @return $this
   *   This payment request.
   */
  public function setReturnUrl($return_url) {
    $this->return_url = $return_url;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'BackendURL' => $this->getNotifyUrl(),
      'Lang' => $this->getMessageCharacterEncoding(),
      'ProdDesc' => $this->getPaymentDescription(),
      'ResponseURL' => $this->getReturnUrl(),
      'SignatureType' => $this->getMessageSignatureType(),
      'UserContact' => $this->getCustomerPhoneNumber(),
      'UserEmail' => $this->getCustomerEmailAddress(),
      'UserName' => $this->getCustomerName(),
    ] + parent::toArray();
  }

}
