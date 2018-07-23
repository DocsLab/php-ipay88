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

namespace DocsLab\IPay88\Exception;

use DocsLab\IPay88\ExceptionInterface;
use DocsLab\IPay88\MessageInterface;

/**
 * The exception thrown when a request to or a response from iPay88 Online
 * Payment Switching Gateway is invalid.
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class InvalidMessageException extends \InvalidArgumentException implements ExceptionInterface {

  /**
   * The gateway message to or from iPay88 OPSG throwing the validation
   * exception.
   *
   * @var \DocsLab\IPay88\MessageInterface
   */
  protected $gateway_message;

  /**
   * Constructs a message validation exception.
   *
   * @see \DocsLab\IPay88\Client::validateMessage()
   *
   * @param \DocsLab\IPay88\MessageInterface $gateway_message
   *   The message to or from iPay88 OPSG throwing this validation exception.
   */
  public function __construct(MessageInterface $gateway_message) {
    parent::__construct(sprintf('%s is invalid and was not processed by iPay88 Online Payment Switching Gateway.', $gateway_message));
    $this->gateway_message = $gateway_message;
  }

  /**
   * {@inheritdoc}
   */
  public function getGatewayMessage() {
    return $this->gateway_message;
  }

  /**
   * Gets the gateway message validation violations.
   *
   * @return \Symfony\Component\Validator\ConstraintViolationListInterface|NULL
   *   The gateway message validation violations.
   */
  public function getGatewayMessageValidationViolations() {
    return $this->gateway_message->getMessageValidationViolations();
  }

}
