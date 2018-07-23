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
 * Interface for exceptions thrown by messages to or from iPay88 Online Payment
 * Switching Gateway.
 *
 * @see \DocsLab\IPay88\Client::validateMessage()
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
interface ExceptionInterface extends \Throwable {

  /**
   * Gets the gateway message throwing the exception.
   *
   * @return \DocsLab\IPay88\MessageInterface|NULL
   *   The gateway message or NULL if not defined.
   */
  public function getGatewayMessage();

}
