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
 * Interface for message events thrown by the iPay88 Online Payment Switching
 * Gateway client.
 *
 * @see \DocsLab\IPay88\Client
 * @see \DocsLab\IPay88\Events
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
interface EventInterface {

  /**
   * Gets an argument by key.
   *
   * @throws \InvalidArgumentException if event argument doesn't exist.
   *
   * @param mixed $key
   *   The argument key.
   *
   * @return mixed
   *   The argument value.
   */
  public function getArgument($key);

  /**
   * Gets all arguments.
   *
   * @return array
   *   All arguments.
   */
  public function getArguments();

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
   * Gets the message.
   *
   * @return \DocsLab\IPay88\MessageInterface
   *   The message.
   */
  public function getMessage();

  /**
   * Gets the related event class name.
   *
   * E.g., the response event class name for a request event or the request
   * event class name for a response event.
   *
   * @see \DocsLab\IPay88\Events
   *
   * @return string|NULL
   *   The related event class name or NULL if none is related.
   */
  public static function getRelatedEventClass();

  /**
   * Gets the related event name.
   *
   * E.g., the response event name for a request event or the request event name
   * for a response event.
   *
   * @see \DocsLab\IPay88\Events
   *
   * @return string|NULL
   *   The related event name or NULL if none is related.
   */
  public static function getRelatedEventName();

  /**
   * Indicates whether an argument exists.
   *
   * @param mixed $key
   *   The argument key.
   *
   * @return boolean
   *   Whether the argument exists.
   */
  public function hasArgument($key);

  /**
   * Sets an argument.
   *
   * @param mixed $key
   *   The argument key.
   * @param mixed $value
   *   The argument value.
   *
   * @return $this
   *   This message event.
   */
  public function setArgument($key, $value);

  /**
   * Sets all arguments.
   *
   * @param array $arguments
   *   The arguments.
   *
   * @return $this
   *   This message event.
   */
  public function setArguments(array $arguments = []);

}
