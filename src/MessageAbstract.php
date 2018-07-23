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

namespace DocsLab\IPay88;

/**
 * Base class for messages to or from iPay88 Online Payment Switching Gateway.
 *
 * @see \DocsLab\IPay88\MessageInterface
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
abstract class MessageAbstract implements MessageInterface {

  /**
   * The event class name.
   *
   * @see \DocsLab\IPay88\Events
   *
   * @var string
   */
  const EVENT_CLASS = NULL;

  /**
   * The default message HTTP method used to send or to receive the message.
   *
   * @see \DocsLab\IPay88\MessageAbstract::getMessageHttpMethod()
   *
   * @var string
   */
  const MESSAGE_HTTP_METHOD = 'POST';

  /**
   * The message signature test URL where to test message signature validity.
   *
   * @see \DocsLab\IPay88\MessageAbstract::getMessageSignatureTestUrl()
   *
   * @var string|NULL
   */
  const MESSAGE_SIGNATURE_TEST_URL = NULL;

  /**
   * The default message URL from where the message comes or to where the
   * message goes.
   *
   * @see \DocsLab\IPay88\MessageAbstract::getMessageUrl()
   *
   * @var string|NULL
   */
  const MESSAGE_URL = NULL;

  /**
   * The related message class name.
   *
   * @var string|NULL
   */
  const RELATED_MESSAGE_CLASS = NULL;

  /**
   * The message HTTP method.
   *
   * @see \DocsLab\IPay88\MessageAbstract::MESSAGE_HTTP_METHOD
   *
   * @var string
   */
  protected $message_http_method = NULL;

  /**
   * The message signature (iPay88 OPSG parameter: "Signature").
   *
   * @var string|NULL
   */
  protected $message_signature = NULL;

  /**
   * The message URL from where the message comes or to where the message goes.
   *
   * @see \DocsLab\IPay88\MessageAbstract::MESSAGE_URL
   *
   * @var string|NULL
   */
  protected $message_url = NULL;

  /**
   * The message validation violations.
   *
   * @var \Symfony\Component\Validator\ConstraintViolationListInterface|NULL
   */
  protected $message_validation_violations = NULL;

  /**
   * The payment amount (iPay88 OPSG parameter: "Amount").
   *
   * Validation pattern: '/^\d{1,3}(,\d{3})*\.\d{2}$/'
   *
   * @var int|float
   */
  protected $payment_amount = NULL;

  /**
   * The payment reference (iPay88 OPSG parameter: "RefNo") submitted to iPay88
   * OPSG within the payment request.
   *
   * @var string
   */
  protected $payment_reference = NULL;

  /**
   * The related message.
   *
   * @var \DocsLab\IPay88\MessageInterface|NULL
   */
  protected $related_message = NULL;

  /**
   * The seller identifier (iPay88 OPSG parameter: "MerchantCode") provided by
   * iPay88 during the seller registration process.
   *
   * @var string
   */
  protected $seller_identifier;

  /**
   * {@inheritdoc}
   */
  public function __debugInfo() {
    return $this->toArray();
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return sprintf('Message %s', $this->getPaymentReference());
  }

  /**
   * {@inheritdoc}
   */
  public function generateMessageSignature($seller_private_key) {
    if (static::isMessageSignatureRequired()) {
      // The subclass must implement this method.
      throw new \LogicException(sprintf('%s::generateMessageSignature() must be implemented.', get_called_class()));
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function getEventClass() {
    return static::EVENT_CLASS;
  }

  /**
   * {@inheritdoc}
   */
  public static function getEventName() {
    return static::getEventClass()::EVENT_NAME;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessageHttpMethod() {
    if (!isset($this->message_http_method)) {
      // Set the message URL to its default value.
      $this->setMessageHttpMethod(static::MESSAGE_HTTP_METHOD);
    }
    return $this->message_http_method;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessageSignature() {
    return $this->message_signature;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessageSignatureTestUrl() {
    return static::MESSAGE_SIGNATURE_TEST_URL;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessageUrl() {
    if (!isset($this->message_url)) {
      // Set the message URL to its default value.
      $this->setMessageUrl(static::MESSAGE_URL);
    }
    return $this->message_url;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessageValidationViolations() {
    return $this->message_validation_violations;
  }

  /**
   * {@inheritdoc}
   */
  public function getPaymentAmount() {
    return $this->payment_amount;
  }

  /**
   * {@inheritdoc}
   */
  public function getPaymentReference() {
    return $this->payment_reference;
  }

  /**
   * {@inheritdoc}
   */
  public function getRelatedMessage() {
    return $this->related_message;
  }

  /**
   * {@inheritdoc}
   */
  public static function getRelatedMessageClass() {
    return static::RELATED_MESSAGE_CLASS;
  }

  /**
   * {@inheritdoc}
   */
  public function getSellerIdentifier() {
    return $this->seller_identifier;
  }

  /**
   * {@inheritdoc}
   */
  public function hasMessageValidationViolations() {
    return !empty($this->message_validation_violations);
  }

  /**
   * {@inheritdoc}
   */
  public static function isMessageSignatureRequired() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function loadValidatorMetadata(\Symfony\Component\Validator\Mapping\ClassMetadata $metadata) {
    $default_group = substr(strrchr(get_called_class(), '\\'), 1 );

    $metadata
      // Amount
      ->addPropertyConstraints('payment_amount', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The payment amount must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Type([
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The payment amount {{ value }} must be numeric.',
          'type' => 'numeric',
        ]),
      ])
      // RefNo
      ->addPropertyConstraints('payment_reference', [
        new \Symfony\Component\Validator\Constraints\NotNull([
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The payment reference must not be null.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'groups' => ['SignaturePart', $default_group],
          'max' => 30,
          'maxMessage' => 'The payment reference {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // MerchantCode
      ->addPropertyConstraints('seller_identifier', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The seller identifier must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'groups' => ['SignaturePart', $default_group],
          'max' => 20,
          'maxMessage' => 'The seller identifier {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      ->setGroupSequence(['SignaturePart', $default_group]);

    // Signature
    if (static::isMessageSignatureRequired()) {
      // @see \DocsLab\IPay88\Client::validateMessage() for validation of the
      // message signature value.
      $metadata->addPropertyConstraints('message_signature', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'message' => 'The message signature must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 100,
          'maxMessage' => 'The message signature {{ value }} must have {{ limit }} characters or less.',
        ]),
      ]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setMessageHttpMethod($message_http_method) {
    $this->message_http_method = $message_http_method;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setMessageSignature($message_signature) {
    $this->message_signature = $message_signature;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setMessageUrl($message_url) {
    $this->message_url = $message_url;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setMessageValidationViolations(\Symfony\Component\Validator\ConstraintViolationListInterface $message_validation_violations) {
    $this->message_validation_violations = $message_validation_violations;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setPaymentAmount($payment_amount) {
    // Reset the message signature as the payment amount is part of the hash.
    $this->message_signature = NULL;
    $this->payment_amount = $payment_amount;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setPaymentReference($payment_reference) {
    // Reset the message signature as the payment reference is part of the hash.
    $this->message_signature = NULL;
    $this->payment_reference = $payment_reference;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setRelatedMessage(MessageInterface $related_message = NULL) {
    if (NULL !== $related_message && ($related_message_class = $this->getRelatedMessageClass()) && !($related_message instanceof $related_message_class)) {
      throw new \InvalidArgumentException(sprintf('The related message must be a "%s", "%s" given.', $related_message_class, get_class($related_message)));
    }
    $this->related_message = $related_message;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setSellerIdentifier($seller_identifier) {
    // Reset the message signature as the seller identifier is part of the hash.
    $this->message_signature = NULL;
    $this->seller_identifier = $seller_identifier;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $array = [
      'Amount' => Helper::formatAmountToString($this->getPaymentAmount()),
      'MerchantCode' => $this->getSellerIdentifier(),
      'RefNo' => $this->getPaymentReference(),
    ];

    if (static::isMessageSignatureRequired()) {
      $array['Signature'] = $this->getMessageSignature();
    }

    return $array;
  }

}
