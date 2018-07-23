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
 * Interface for messages to or from iPay88 Online Payment Switching Gateway.
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
interface MessageInterface {

  /**
   * Gets the properties of the message to be shown when dumping it.
   *
   * @return array
   *   The message properties.
   */
  public function __debugInfo();

  /**
   * Gets the textual representation of the message.
   *
   * @return string
   *   The message textual representation.
   */
  public function __toString();

  /**
   * Creates a new message from a PHP array.
   *
   * @todo Sanitize the parameters.
   *
   * @param array $array
   *   The message parameters keyed by iPay88 OPSG parameter names.
   *
   * @return \DocsLab\IPay88\MessageInterface
   *   A new message.
   */
  public static function createFromArray(array $array);

  /**
   * Generates a message signature.
   *
   * The needed properties to generate the message signature should be validated
   * by calling {@link \DocsLab\IPay88\Client::validateMessage()} with
   * ['SignaturePart'] as $groups parameter value.
   *
   * @see \DocsLab\IPay88\Client::validateMessage()
   * @see \DocsLab\IPay88\MessageInterface::MESSAGE_SIGNATURE_TEST_URL
   *
   * @param string $seller_private_key
   *   The seller private key (MerchantKey) provided by iPay88 during the seller
   *   registration process.
   *
   * @return string|NULL
   *   The message signature or NULL if not required.
   */
  public function generateMessageSignature($seller_private_key);

  /**
   * Gets the event class name.
   *
   * @see \DocsLab\IPay88\Events
   *
   * @return string
   *   The event class name.
   */
  public static function getEventClass();

  /**
   * Gets the event name.
   *
   * @see \DocsLab\IPay88\Events
   *
   * @return string
   *   The event name.
   */
  public static function getEventName();

  /**
   * Gets the message HTTP method.
   *
   * @return string
   *   The message HTTP method.
   */
  public function getMessageHttpMethod();

  /**
   * Gets the message signature (iPay88 OPSG parameter: "Signature").
   *
   * @see \DocsLab\IPay88\MessageInterface::isMessageSignatureRequired()
   *
   * @return string|NULL
   *   The message signature or NULL if not required.
   */
  public function getMessageSignature();

  /**
   * Gets the message signature test URL.
   *
   * @see \DocsLab\IPay88\MessageInterface::MESSAGE_SIGNATURE_TEST_URL
   *
   * @return string|NULL
   *   The message signature test URL or NULL if not defined.
   */
  public function getMessageSignatureTestUrl();

  /**
   * Gets the message URL from where the message comes or to where the message
   * goes.
   *
   * @return string
   *   The message URL.
   */
  public function getMessageUrl();

  /**
   * Gets the message validation violations.
   *
   * @see \DocsLab\IPay88\Client::validateMessage()
   *
   * @return \Symfony\Component\Validator\ConstraintViolationListInterface|NULL
   *   The message validation violations.
   */
  public function getMessageValidationViolations();

  /**
   * Gets the payment amount (iPay88 OPSG parameter: "Amount").
   *
   * @return float
   *   The payment amount.
   */
  public function getPaymentAmount();

  /**
   * Gets the payment reference (iPay88 OPSG parameter: "RefNo") submitted to
   * iPay88 OPSG within the payment request.
   *
   * @return string
   *   The payment reference.
   */
  public function getPaymentReference();

  /**
   * Gets the related message.
   *
   * E.g., the response related to a request or the request related to a
   * response.
   *
   * @return \DocsLab\IPay88\MessageInterface|NULL
   *   The related message or NULL if none is related.
   */
  public function getRelatedMessage();

  /**
   * Gets the related message class name.
   *
   * E.g., the response class name related to a request or the request class
   * name related to a response.
   *
   * @return string|NULL
   *   The related message class name or NULL if none is related.
   */
  public static function getRelatedMessageClass();

  /**
   * Gets the seller identifier (iPay88 OPSG parameter: "MerchantCode") provided
   * by iPay88 during the seller registration process.
   *
   * @return string
   *   The seller identifier.
   */
  public function getSellerIdentifier();

  /**
   * Indicates whether the message has validation violations.
   *
   * @return boolean
   *   Whether the message has validation violations.
   */
  public function hasMessageValidationViolations();

  /**
   * Indicates whether the message signature is required.
   *
   * @return boolean
   *   Whether the message signature is required.
   */
  public static function isMessageSignatureRequired();

  /**
   * Callback method used by a
   * \Symfony\Component\Validator\Validator\ValidatorInterface to set the
   * validator metadata constraints.
   *
   * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
   *   The validator metadata.
   */
  public static function loadValidatorMetadata(\Symfony\Component\Validator\Mapping\ClassMetadata $metadata);

  /**
   * Sets the message HTTP method.
   *
   * @param string $message_http_method
   *   The message HTTP method.
   *
   * @return $this
   *   This message.
   */
  public function setMessageHttpMethod($message_http_method);

  /**
   * Sets the message signature (iPay88 OPSG parameter: "Signature").
   *
   * @param string $message_signature
   *   The message signature.
   *
   * @return $this
   *   This message.
   */
  public function setMessageSignature($message_signature);

  /**
   * Sets the message URL from where the message comes or to where the message
   * goes.
   *
   * @param string $message_url
   *   The message URL.
   *
   * @return $this
   *   This message.
   */
  public function setMessageUrl($message_url);

  /**
   * Sets the message validation violations.
   *
   * @see \DocsLab\IPay88\Client::validateMessage()
   *
   * @param \Symfony\Component\Validator\ConstraintViolationListInterface $message_validation_violations
   *   The message validation violations.
   *
   * @return $this
   *   This message.
   */
  public function setMessageValidationViolations(\Symfony\Component\Validator\ConstraintViolationListInterface $message_validation_violations);

  /**
   * Sets the payment amount (iPay88 OPSG parameter: "Amount").
   *
   * The message signature must be updated afterward if required.
   *
   * @see \DocsLab\IPay88\MessageInterface::generateMessageSignature()
   * @see \DocsLab\IPay88\MessageInterface::setMessageSignature()
   *
   * @param float $payment_amount
   *   The payment amount.
   *
   * @return $this
   *   This message.
   */
  public function setPaymentAmount($payment_amount);

  /**
   * Sets the payment reference (iPay88 OPSG parameter: "RefNo") submitted to
   * iPay88 OPSG within the payment request.
   *
   * The message signature must be updated afterward if required.
   *
   * @see \DocsLab\IPay88\MessageInterface::generateMessageSignature()
   * @see \DocsLab\IPay88\MessageInterface::setMessageSignature()
   *
   * @param string $payment_reference
   *   The payment reference.
   *
   * @return $this
   *   This message.
   */
  public function setPaymentReference($payment_reference);

  /**
   * Sets the related message.
   *
   * E.g., the response related to a request or the request related to a
   * response.
   *
   * @see \DocsLab\IPay88\MessageInterface::getRelatedMessageClass()
   *
   * @throws \InvalidArgumentException if the related message class is defined
   *   by this message and the related message is not of this type.
   *
   * @param \DocsLab\IPay88\MessageInterface
   *   The related message.
   *
   * @return $this
   *   This message.
   */
  public function setRelatedMessage(MessageInterface $related_message = NULL);

  /**
   * Sets the seller identifier (iPay88 OPSG parameter: "MerchantCode") provided
   * by iPay88 during the seller registration process.
   *
   * The message signature must be updated afterward if required.
   *
   * @see \DocsLab\IPay88\MessageInterface::generateMessageSignature()
   * @see \DocsLab\IPay88\MessageInterface::setMessageSignature()
   *
   * @param string $seller_identifier
   *   The seller identifier.
   *
   * @return $this
   *   This message.
   */
  public function setSellerIdentifier($seller_identifier);

  /**
   * Gets the message parameters as PHP array keyed by iPay88 OPSG parameter
   * names.
   *
   * @see \DocsLab\IPay88\MessageInterface::formatAmount()
   *
   * @return array
   *   The message parameters.
   */
  public function toArray();

}
