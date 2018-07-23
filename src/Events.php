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
 * The iPay88 Online Payment Switching Gateway events thrown by the gateway
 * client.
 *
 * @todo Rename to Events.
 *
 * @see \DocsLab\IPay88\Client
 * @see \DocsLab\IPay88\EventInterface
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class Events {

  /**
   * The MESSAGE_VALIDATION_VIOLATIONS event occurs once a message validation
   * returns constraint violations.
   *
   * This event allows to handle the violations returned by a message validation
   * e.g., throwing exceptions or displaying message to the end user.
   *
   * @see \DocsLab\IPay88\Event\MessageValidationViolationsEvent
   *
   * @Event("DocsLab\IPay88\Event\MessageValidationViolationsEvent")
   */
  const MESSAGE_VALIDATION_VIOLATIONS = 'docslab.ipay88.message_validation_violations';

  /**
   * The PAYMENT_NOTIFY_RESPONSE event occurs once a payment notify response was
   * created.
   *
   * This event allows to modify or replace the payment notify response.
   *
   * @see \DocsLab\IPay88\Event\PaymentNotifyResponseEvent
   *
   * @Event("DocsLab\IPay88\Event\PaymentNotifyResponseEvent")
   */
  const PAYMENT_NOTIFY_RESPONSE = 'docslab.ipay88.payment_notify_response';

  /**
   * The PAYMENT_REQUEST event occurs once a payment request was created.
   *
   * This event allows to modify or replace the payment request.
   *
   * @see \DocsLab\IPay88\Event\PaymentRequestEvent
   *
   * @Event("DocsLab\IPay88\Event\PaymentRequestEvent")
   */
  const PAYMENT_REQUEST = 'docslab.ipay88.payment_request';

  /**
   * The PAYMENT_RESPONSE event occurs once a payment response was created.
   *
   * This event allows to modify or replace the payment response.
   *
   * @see \DocsLab\IPay88\Event\PaymentResponseEvent
   *
   * @Event("DocsLab\IPay88\Event\PaymentResponseEvent")
   */
  const PAYMENT_RESPONSE = 'docslab.ipay88.payment_response';

  /**
   * The PAYMENT_STATUS_REQUEST event occurs once a payment status request was
   * created.
   *
   * This event allows to modify or replace the payment status request.
   *
   * @see \DocsLab\IPay88\Event\PaymentStatusRequestEvent
   *
   * @Event("DocsLab\IPay88\Event\PaymentStatusRequestEvent")
   */
  const PAYMENT_STATUS_REQUEST = 'docslab.ipay88.payment_status_request';

  /**
   * The PAYMENT_STATUS_RESPONSE event occurs once a payment status reŝponse was
   * created.
   *
   * This event allows to modify or replace the payment status response.
   *
   * @see \DocsLab\IPay88\Event\PaymentStatusResponseEvent
   *
   * @Event("DocsLab\IPay88\Event\PaymentStatusResponseEvent")
   */
  const PAYMENT_STATUS_RESPONSE = 'docslab.ipay88.payment_status_response';

}
