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

namespace DocsLab\IPay88\Event;

use DocsLab\IPay88\EventAbstract;
use DocsLab\IPay88\Events;

/**
 * The payment response event.
 *
 * @see \DocsLab\IPay88\Events
 * @see \DocsLab\IPay88\Message\PaymentResponseMessage
 *
 * @method \DocsLab\IPay88\Message\PaymentResponseMessage getMessage()
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class PaymentResponseEvent extends EventAbstract {

  /**
   * {@inheritdoc}
   */
  const EVENT_NAME = Events::PAYMENT_RESPONSE;

  /**
   * {@inheritdoc}
   */
  const RELATED_EVENT_CLASS = PaymentRequestEvent::class;

}
