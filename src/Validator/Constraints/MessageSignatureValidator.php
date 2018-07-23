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

use DocsLab\IPay88\MessageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * The constraint validator validating the signature value of messages to or
 * from iPay88 Online Payment Switching Gateway.
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
class MessageSignatureValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   *
   * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException if
   *   $constraint is not a \DocsLab\IPay88\Validator\Constraint\MessageSignature
   *   object or if the constraint context object doesn't implement
   *   \DocsLab\IPay88\MessageInterface.
   */
  public function validate($value, Constraint $constraint) {
    /** @var \DocsLab\IPay88\Validator\Constraints\MessageSignature $constraint */
    if (!$constraint instanceof MessageSignature) {
      throw new UnexpectedTypeException($constraint, MessageSignature::class);
    }
    /** @var \DocsLab\IPay88\MessageInterface $message */
    if (!(($message = $this->context->getObject()) instanceof MessageInterface)) {
      throw new UnexpectedTypeException($message, MessageInterface::class);
    }

    if ($message->generateMessageSignature($constraint->getSellerPrivateKey()) != $value) {
      $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
    }
  }

}
