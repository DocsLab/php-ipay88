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

namespace DocsLab\IPay88\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * The constraint validating the signature value of messages to or from iPay88
 * Online Payment Switching Gateway.
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class MessageSignature extends Constraint {

  /**
   * The INVALID_MESSAGE_SIGNATURE_ERROR identifier.
   *
   * @var string
   */
  const INVALID_MESSAGE_SIGNATURE_ERROR = 'ef9f3fa2-9e6f-9749-9a55-03392f41dea4';

  /**
   * {@inheritdoc}
   */
  protected static $errorNames = [
    self::INVALID_MESSAGE_SIGNATURE_ERROR => 'INVALID_MESSAGE_SIGNATURE_ERROR',
  ];

  /**
   * @var string
   */
  public $message = 'The message signature "{{ string }}" is invalid.';

  /**
   * The seller private key (iPay88 OPSG parameter: "MerchantKey") used to
   * validate the message signature.
   *
   * @var string
   */
  private $seller_private_key;

  /**
   * Constructs a message signature constraint.
   *
   * @throws \Symfony\Component\Validator\Exception\MissingOptionsException if
   *   $seller_private_key is not set.
   *
   * @param string $seller_private_key
   *   The seller private key (MerchantKey) used to validate the message
   *   signature.
   */
  public function __construct($seller_private_key) {
    if (empty($seller_private_key)) {
      throw new MissingOptionsException('The seller private key must be set for constraint "DocsLab\IPay88\Validator\Constraints\MessageSignature".', []);
    }
    $this->seller_private_key = $seller_private_key;
  }

  /**
   * Gets the seller private key used to validate the message signature.
   *
   * @return string
   *   The seller private key.
   */
  public function getSellerPrivateKey() {
    return $this->seller_private_key;
  }

}
