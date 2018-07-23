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
 * The iPay88 Online Payment Switching Gateway client.
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class Client {

  /**
   * The version of the iPay88 OPSG technical specifications implemented by this
   * library.
   *
   * Contact {@link mailto://support@ipay88.com.my iPay88 support team} to have
   * a copy of the technical specifications and to ensure this library is up to
   * date.
   *
   * @var string
   */
  const VERSION = '1.6.4';

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $http_client;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $event_dispatcher;

  /**
   * The seller identifier (iPay88 OPSG parameter: "MerchantCode").
   *
   * @var string
   */
  protected $seller_identifier;

  /**
   * The seller merchant key (iPay88 OPSG parameter: "MerchantKey").
   *
   * @var string
   */
  protected $seller_private_key;

  /**
   * The validator used to validate iPay88 OPSG messages.
   *
   * @var \Symfony\Component\Validator\Validator\ValidatorInterface
   */
  protected $validator;

  /**
   * Constructs a gateway client.
   *
   * @param string $seller_identifier
   *   The seller identifier (MerchantCode) provided by iPay88.
   * @param string $seller_private_key
   *   The seller private key (MerchantKey) provided by iPay88.
   */
  public function __construct($seller_identifier = NULL, $seller_private_key = NULL) {
    $this
      ->setSellerIdentifier($seller_identifier)
      ->setSellerPrivateKey($seller_private_key);
  }

  /**
   * Creates a message.
   *
   * @param string $message_class
   *   The message class name.
   * @param array $message_parameters
   *   The message parameters.
   * @param \DocsLab\IPay88\MessageInterface $related_message
   *   The related payment request.
   * @param array $event_arguments
   *   The event arguments.
   * @param boolean $throw_exception
   *   Whether validation violations must throw an exception.
   *
   * @return \DocsLab\IPay88\MessageInterface
   *   The message.
   */
  protected function createMessage($message_class, array $message_parameters, MessageInterface $related_message = NULL, array $event_arguments = NULL, $throw_exception = FALSE) {
    /** @var \DocsLab\IPay88\MessageInterface $message_class */
    $message = $message_class::createFromArray($message_parameters)
      ->setRelatedMessage($related_message);

    if ($this->event_dispatcher && ($event_class = $message_class::getEventClass())) {
      $this->event_dispatcher->dispatch(
        $event_class::getEventName(),
        new $event_class($message, $event_arguments)
      );
    }

    if ($this->validator) {
      $this->validateMessage($message, NULL, $event_arguments, $throw_exception);
    }

    return $message;
  }

  /**
   * Creates a payment notify response.
   *
   * @param array $parameters
   *   The payment notify response parameters.
   * @param \DocsLab\IPay88\Message\PaymentRequestMessage $request
   *   The related payment request.
   * @param array $event_arguments
   *   The event arguments.
   * @param boolean $throw_exception
   *   Whether validation violations must throw an exception.
   *
   * @return \DocsLab\IPay88\Message\PaymentNotifyResponseMessage
   *   The payment notify response.
   */
  public function createPaymentNotifyResponse(array $parameters, Message\PaymentRequestMessage $request = NULL, array $event_arguments = NULL, $throw_exception = FALSE) {
    return $this->createMessage(Message\PaymentNotifyResponseMessage::class, $parameters, $request, $event_arguments, $throw_exception);
  }

  /**
   * Creates a payment request.
   *
   * @param array $parameters
   *   The payment request parameters.
   * @param \DocsLab\IPay88\Message\PaymentResponseMessage $response
   *   The related payment response.
   * @param array $event_arguments
   *   The event arguments.
   * @param boolean $throw_exception
   *   Whether validation violations must throw an exception.
   *
   * @return \DocsLab\IPay88\Message\PaymentRequestMessage
   *   The payment request.
   */
  public function createPaymentRequest(array $parameters, Message\PaymentResponseMessage $response = NULL, array $event_arguments = NULL, $throw_exception = FALSE) {
    if (!isset($parameters['MerchantCode'])) {
      $parameters['MerchantCode'] = $this->seller_identifier;
    }
    return $this->createMessage(Message\PaymentRequestMessage::class, $parameters, $response, $event_arguments, $throw_exception);
  }

  /**
   * Creates a payment response.
   *
   * @param array $parameters
   *   The payment response parameters.
   * @param \DocsLab\IPay88\Message\PaymentRequestMessage $request
   *   The related payment request.
   * @param array $event_arguments
   *   The event arguments.
   * @param boolean $throw_exception
   *   Whether validation violations must throw an exception.
   *
   * @return \DocsLab\IPay88\Message\PaymentResponseMessage
   *   The payment response.
   */
  public function createPaymentResponse(array $parameters, Message\PaymentRequestMessage $request = NULL, array $event_arguments = NULL, $throw_exception = FALSE) {
    return $this->createMessage(Message\PaymentResponseMessage::class, $parameters, $request, $event_arguments, $throw_exception);
  }

  /**
   * Creates a payment status request.
   *
   * @param array $parameters
   *   The payment status request parameters.
   * @param \DocsLab\IPay88\Message\PaymentStatusResponseMessage $response
   *   The related payment status response.
   * @param array $event_arguments
   *   The event arguments.
   * @param boolean $throw_exception
   *   Whether validation violations must throw an exception.
   *
   * @return \DocsLab\IPay88\Message\PaymentStatusRequestMessage
   *   The payment status request.
   */
  public function createPaymentStatusRequest(array $parameters, Message\PaymentStatusResponseMessage $response = NULL, array $event_arguments = NULL, $throw_exception = FALSE) {
    return $this->createMessage(Message\PaymentStatusRequestMessage::class, $parameters, $response, $event_arguments, $throw_exception);
  }

  /**
   * Creates a payment status response.
   *
   * @param array $parameters
   *   The payment status response parameters.
   * @param \DocsLab\IPay88\Message\PaymentStatusRequestMessage $request
   *   The related payment status request.
   * @param array $event_arguments
   *   The event arguments.
   * @param boolean $throw_exception
   *   Whether validation violations must throw an exception.
   *
   * @return \DocsLab\IPay88\Message\PaymentStatusResponseMessage
   *   The payment status response.
   */
  public function createPaymentStatusResponse(array $parameters, Message\PaymentStatusRequestMessage $request = NULL, array $event_arguments = NULL, $throw_exception = FALSE) {
    return $this->createMessage(Message\PaymentStatusResponseMessage::class, $request, $parameters, $event_arguments, $throw_exception);
  }

  /**
   * Gets the event dispatcher used to dispatch events.
   *
   * @throws \LogicException if Symfony EventDispatcher Component is not
   *   installed.
   *
   * @param boolean $default
   *   Whether or not to create a default event dispatcher.
   *
   * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
   *   The event dispatcher.
   */
  public function getEventDispatcher($default = FALSE) {
    if (!interface_exists('\Symfony\Component\EventDispatcher\EventDispatcherInterface')) {
      throw new \LogicException('iPay88 Online Payment Switching Gateway PHP client library requires Symfony EventDispatcher Component (`composer require symfony/event-dispatcher`) to dispatch events.');
    }
    if (!isset($this->event_dispatcher) && $default) {
      $this->event_dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
    }
    return $this->event_dispatcher;
  }

  /**
   * Gets the HTTP client used to send request to iPay88 OPSG.
   *
   * @throws \LogicException if Guzzle HTTP Client is not installed.
   *
   * @param boolean $default
   *   Whether or not to create a default HTTP client.
   *
   * @return \GuzzleHttp\ClientInterface
   *   The HTTP client.
   */
  public function getHttpClient($default = FALSE) {
    if (!interface_exists('\GuzzleHttp\ClientInterface')) {
      throw new \LogicException('iPay88 Online Payment Switching Gateway PHP client library requires Guzzle HTTP Client (`composer require guzzlehttp/guzzle`) to send requests.');
    }
    if (!isset($this->http_client) && $default) {
      $this->http_client = new \GuzzleHttp\Client();
    }
    return $this->http_client;
  }

  /**
   * Gets the validator used to validate message.
   *
   * @throws \LogicException if Symfony Validator Component is not installed.
   *
   * @param boolean $default
   *   Whether or not to create a default validator.
   *
   * @return \Symfony\Component\Validator\Validator\ValidatorInterface
   *   The validator.
   */
  public function getValidator($default = FALSE) {
    if (!interface_exists('\Symfony\Component\Validator\Validator\ValidatorInterface')) {
      throw new \LogicException('iPay88 Online Payment Switching Gateway PHP client library requires Symfony Validator Component (`composer require symfony/validator`) to handle messages validations.');
    }
    if (!isset($this->validator) && $default) {
      // Load validator metadata directly from the message to validate.
      $this->validator = \Symfony\Component\Validator\Validation::createValidatorBuilder()
        ->addMethodMapping('loadValidatorMetadata')
        ->getValidator();
    }
    return $this->validator;
  }

  /**
   * Sends a request to iPay88 OPSG and returns the response received from it.
   *
   * @throws \InvalidArgumentException if the request message class doesn't
   *   implement \DocsLab\IPay88\MessageInterface.
   * @throws \InvalidArgumentException if the request message class doesn't have
   *   a related response message class.
   * @throws \UnexpectedValueException if the HTTP response status code is not
   *   200.
   *
   * @param string $request_class
   *   The request class name.
   * @param array $request_parameters
   *   The request parameters.
   * @param array $event_arguments
   *   The event arguments.
   * @param array $http_options
   *   The HTTP request options passed to the HTTP client.
   * @param boolean $throw_validation_exception
   *   Whether validation violations must throw an exception.
   *
   * @return \DocsLab\IPay88\MessageInterface
   *   The response related to the request.
   */
  public function request($request_class, array $request_parameters, array $event_arguments = NULL, array $http_options = [], $throw_validation_exception = FALSE) {
    // Ensure HTTP client is already defined or create a default one.
    $this->getHttpClient(TRUE);

    if (!in_array(RequestMessageInterface::class, class_implements($request_class))) {
      throw new \InvalidArgumentException(sprintf('The request class "%s" must implements "%s".', $request_class, RequestMessageInterface::class));
    }

    /** @var \DocsLab\IPay88\MessageInterface $request_class */
    /** @var \DocsLab\IPay88\MessageInterface $response_class */
    if (!($response_class = $request_class::getRelatedMessageClass())) {
      throw new \InvalidArgumentException(sprintf('The request class "%s" must be related to a response class.', $request_class));
    }

    $event_arguments += ['http_options' => $http_options];

    $request = $this->createMessage($request_class, $request_parameters, NULL, $event_arguments, $throw_validation_exception);

    $http_response = $this->http_client->request(
      $request->getMessageHttpMethod(),
      $request->getMessageUrl(),
      ['form_params' => $request->toArray()] + $http_options
    );

    if (200 != $http_response->getStatusCode()) {
      throw new \UnexpectedValueException(sprintf(
        'The HTTP response received from iPay88 Online Payment Switching Gateway is invalid: "%s".',
        $http_response->getReasonPhrase() ?: (string) $http_response->getStatusCode()
      ));
    }

    $event_arguments += ['http_response' => $http_response];

    // Ensure the response content is casted as an array, specially for string
    // response returned by iPay88 OPSG.
    return $this->createMessage($response_class, (array) $http_response->getBody()->getContents(), $request, $event_arguments, $throw_validation_exception);
  }

  /**
   * Sets the event dispatcher.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   *
   * @return $this
   *   This gateway client.
   */
  public function setEventDispatcher(\Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher) {
    $this->event_dispatcher = $event_dispatcher;
    return $this;
  }

  /**
   * Sets the HTTP client.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   *
   * @return $this
   *   This gateway client.
   */
  public function setHttpClient(\GuzzleHttp\ClientInterface $http_client) {
    $this->http_client = $http_client;
    return $this;
  }

  /**
   * Sets the seller identifer (iPay88 OPSG parameter: "MerchantCode") provided
   * by iPay88 during the seller registration process.
   *
   * @param string $seller_identifier
   *   The seller identifier.
   *
   * @return $this
   *   This gateway client.
   */
  public function setSellerIdentifier($seller_identifier) {
    $this->seller_identifier = $seller_identifier;
    return $this;
  }

  /**
   * Sets the seller private key (iPay88 OPSG parameter: "MerchantKey") provided
   * by iPay88 during the seller registration process.
   *
   * @param string $seller_private_key
   *   The seller private key.
   *
   * @return $this
   *   This gateway client.
   */
  public function setSellerPrivateKey($seller_private_key) {
    $this->seller_private_key = $seller_private_key;
    return $this;
  }

  /**
   * Sets the validator used to validate messages.
   *
   * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
   *   The validator
   *
   * @return $this
   *   This gateway client.
   */
  public function setValidator(\Symfony\Component\Validator\Validator\ValidatorInterface $validator) {
    $this->validator = $validator;
    return $this;
  }

  /**
   * Validates a message to or from iPay88 OPSG.
   *
   * @todo Check the payment amount from iPay88 OPSG is match with yours.
   * @todo Should validate against $_SERVER['REMOTE_ADDR'] or $_SERVER['REMOTE_HOST']
   * http://php.net/manual/fr/function.gethostbyaddr.php
   * @todo Should validate against the Origin (server name only) or Referer HTTP header, but not set too.
   *
   * @see \DocsLab\IPay88\Event\MessageValidationViolationsEvent
   * @see \DocsLab\IPay88\Client::getValidator()
   * @see \DocsLab\IPay88\Client::setEventDispatcher()
   * @see \DocsLab\IPay88\Client::setValidator()
   * @see \DocsLab\IPay88\MessageInterface::isMessageSignatureRequired()
   * @see \DocsLab\IPay88\MessageInterface::loadValidatorMetadata()
   *
   * @throws \DocsLab\IPay88\Exception\InvalidMessageException if message
   *   validation occur and $throw_exception was set to TRUE.
   * @throws \InvalidArgumentException if the message signature must be
   *   validated but no seller private key was defined.
   * @throws \LogicException if the message signature must be validated but a
   *   wrong metadata class was retrieved by the validator.
   *
   * @param MessageInterface $message
   *   The message to validate.
   * @param array $validation_groups
   *   The validation groups.
   * @param array $event_arguments
   *   The arguments passed to the message validation violations event if event
   *   dispatcher has been defined.
   * @param boolean $throw_exception
   *   The flag indicating whether message validation violations must throw an
   *   exception.
   *
   * @return \Symfony\Component\Validator\ConstraintViolationListInterface
   *   The message validation violations.
   */
  public function validateMessage(MessageInterface $message, array $validation_groups = NULL, array $event_arguments = NULL, $throw_exception = FALSE) {
    // Ensure a validator is already defined or create a default one.
    $this->getValidator(TRUE);

    if ($message::isMessageSignatureRequired()) {
      if (empty($this->seller_private_key)) {
        throw new \InvalidArgumentException(sprintf('The seller private key is expected to validate the message "%s".', $message));
      }
      /** @var \Symfony\Component\Validator\Mapping\ClassMetadata $metadata */
      if (!($metadata = $this->validator->getMetadataFor($message)) instanceof \Symfony\Component\Validator\Mapping\ClassMetadata) {
        throw new \LogicException(sprintf('Metadata class extending "\Symfony\Component\Validator\Mapping\ClassMetadata" is expected to validate the message "%s" signature, "%s" given.', $message, get_class($metadata)));
      }
      $metadata->addPropertyConstraint('message_signature', new \DocsLab\IPay88\Validator\Constraints\MessageSignature($this->seller_private_key));
    }

    $violations = $this->validator->validate($message, NULL, $validation_groups);

    if (count($violations)) {
      $message->setMessageValidationViolations($violations);

      if ($this->event_dispatcher) {
        $this->event_dispatcher->dispatch(
          Events::MESSAGE_VALIDATION_VIOLATIONS,
          new \DocsLab\IPay88\Event\MessageValidationViolationsEvent($message, $event_arguments)
        );
      }

      if ($throw_exception) {
        throw new Exception\InvalidMessageException($message, $violations);
      }
    }

    return $violations;
  }

}