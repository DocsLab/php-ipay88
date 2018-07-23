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

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Base class for message events thrown by the iPay88 Online Payment Switching
 * Gateway client.
 *
 * @see \DocsLab\IPay88\EventInterface
 *
 * @method \DocsLab\IPay88\MessageInterface getSubject()
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
abstract class EventAbstract extends GenericEvent implements EventInterface {

  /**
   * The event name.
   *
   * @see \DocsLab\IPay88\Events
   *
   * @var string
   */
  const EVENT_NAME = NULL;

  /**
   * The related event class name.
   *
   * @see \DocsLab\IPay88\Events
   *
   * @var string|NULL
   */
  const RELATED_EVENT_CLASS = NULL;

  /**
   * Constructs a message event.
   *
   * @param \DocsLab\IPay88\MessageInterface $message
   *   The message of the gateway event.
   * @param array $arguments
   *   Additional arguments to store within the message event.
   */
  public function __construct(MessageInterface $message, array $arguments = []) {
    parent::__construct($message, $arguments);
  }

  /**
   * {@inheritdoc}
   */
  public static function getEventName() {
    return static::EVENT_NAME;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessage() {
    return parent::getSubject();
  }

  /**
   * {@inheritdoc}
   */
  public static function getRelatedEventClass() {
    return static::RELATED_EVENT_CLASS;
  }

  /**
   * {@inheritdoc}
   */
  public static function getRelatedEventName() {
    return static::getRelatedEventClass()::RELATED_EVENT_NAME;
  }

}
