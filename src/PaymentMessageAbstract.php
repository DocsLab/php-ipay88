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
 * Base class for payment messages to or from iPay88 Online Payment Switching
 * Gateway.
 *
 * @see \DocsLab\IPay88\MessageAbstract
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
abstract class PaymentMessageAbstract extends MessageAbstract {

  /**
   * The payment comment (iPay88 OPSG parameter: "Remark").
   *
   * @var string|NULL
   */
  protected $payment_comment = NULL;

  /**
   * The payment currency (iPay88 OPSG parameter: "Currency").
   *
   * @var string
   */
  protected $payment_currency;

  /**
   * The payment method (iPay88 OPSG parameter: "PaymentId").
   *
   * @var string|NULL
   */
  protected $payment_method = NULL;

  /**
   * Gets the payment comment (iPay88 OPSG parameter: "Remark").
   *
   * @return string|NULL
   *   The payment comment.
   */
  public function getPaymentComment() {
    return $this->payment_comment;
  }

  /**
   * Gets the payment currency (iPay88 OPSG parameter: "Currency").
   *
   * @return string
   *   The payment currency.
   */
  public function getPaymentCurrency() {
    return $this->payment_currency;
  }

  /**
   * Gets the payment method (iPay88 OPSG parameter: "PaymentId").
   *
   * @return string|NULL
   *   The payment method.
   */
  public function getPaymentMethod() {
    return $this->payment_method;
  }

  /**
   * {@inheritdoc}
   */
  public static function isMessageSignatureRequired() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function loadValidatorMetadata(\Symfony\Component\Validator\Mapping\ClassMetadata $metadata) {
    parent::loadValidatorMetadata($metadata);

    $default_group = $metadata->getDefaultGroup();

    $metadata
      // Remark
      ->addPropertyConstraints('payment_comment', [
        new \Symfony\Component\Validator\Constraints\Length([
          'max' => 100,
          'maxMessage' => 'The payment comment {{ value }} must have {{ limit }} characters or less.',
        ]),
      ])
      // Currency
      ->addPropertyConstraints('payment_currency', [
        new \Symfony\Component\Validator\Constraints\NotBlank([
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The payment currency must not be blank.',
        ]),
        new \Symfony\Component\Validator\Constraints\Length([
          'groups' => ['SignaturePart', $default_group],
          'max' => 5,
          'maxMessage' => 'The payment currency {{ value }} must have {{ limit }} characters or less.',
        ]),
        new \Symfony\Component\Validator\Constraints\Choice([
          'choices' => array_keys(Helper::getCurrencies()),
          'groups' => ['SignaturePart', $default_group],
          'message' => 'The payment currency {{ value }} must be a valid one: "'. implode('", "', array_keys(Helper::getCurrencies())) . '".',
        ]),
      ])
      // PaymentId
      ->addPropertyConstraints('payment_method', [
        new \Symfony\Component\Validator\Constraints\Choice([
          'choices' => array_keys(Helper::getSupportedMethods()),
          'message' => 'The payment method {{ value }} must be a valid one: "'. implode('", "', array_keys(Helper::getSupportedMethods())) . '".',
        ]),
      ]);
  }

  /**
   * Sets the payment comment (iPay88 OPSG parameter: "Remark").
   *
   * @param string $payment_comment
   *   The payment comment.
   *
   * @return $this
   *   This payment message.
   */
  public function setPaymentComment($payment_comment) {
    $this->payment_comment = $payment_comment;
    return $this;
  }

  /**
   * Sets the payment currency (iPay88 OPSG parameter: "Currency").
   *
   * The message signature must be generated afterward with
   * {@link \DocsLab\IPay88\PaymentMessageAbstract::generateMessageSignature()}.
   *
   * @param string $payment_currency
   *   The payment currency.
   *
   * @return $this
   *   This payment message.
   */
  public function setPaymentCurrency($payment_currency) {
    // Reset the signature as the currency is part of the signature hash.
    $this->message_signature = NULL;
    $this->payment_currency = $payment_currency;
    return $this;
  }

  /**
   * Sets the payment method (iPay88 OPSG parameter: "PaymentId").
   *
   * @param string $payment_method
   *   The payment method.
   *
   * @return $this
   *   This payment message.
   */
  public function setPaymentMethod($payment_method) {
    $this->payment_method = $payment_method;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'Currency' => $this->getPaymentCurrency(),
      'PaymentId' => $this->getPaymentMethod(),
      'Remark' => $this->getPaymentComment(),
    ] + parent::toArray();
  }
}
