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
 * The iPay88 Online Payment Switching Gateway helper.
 *
 * @todo Rename to Helper.
 *
 * @author Maxime Gilbert (DocBu) <docbu@docslab.net>
 */
abstract class Helper {

  /**
   * The iPay88 OPSG HTTP referer value.
   *
   * @var string
   */
  const HTTP_REFERER = 'www.mobile88.com';

  /**
   * The iPay88 OPSG "Malaysia only" area.
   *
   * @var integer
   */
  const AREA_MALAYSIA = 1;

  /**
   * The iPay88 OPSG "multicurrency" area.
   *
   * @var integer
   */
  const AREA_MULTICURRENCY = 2;

  /**
   * The ipay88 OPSG "delayed" payment status.
   *
   * @var integer
   */
  const STATUS_DELAYED = 6;

  /**
   * "Errored" payment status used by this library to handle specific responses
   * from iPay88 OPSG.
   *
   * @see \DocsLab\IPay88\Helper::STATUS_MESSAGE_IPAY88_EXCEEDED_LIMIT
   * @see \DocsLab\IPay88\Helper::$status_messages
   *
   * @var integer
   */
  const STATUS_ERRORED = -1;

  /**
   * The ipay88 OPSG "failed" payment status.
   *
   * @var integer
   */
  const STATUS_FAILED = 0;

  /**
   * The ipay88 OPSG "failed" payment status message.
   *
   * @var string
   */
  const STATUS_MESSAGE_FAILED = 'Payment fail';

  /**
   * The ipay88 OPSG "invalid amount" payment status message.
   *
   * @var string
   */
  const STATUS_MESSAGE_INVALID_AMOUNT = 'Incorrect amount';

  /**
   * The ipay88 OPSG "invalid parameters" payment status message.
   *
   * @var string
   */
  const STATUS_MESSAGE_INVALID_PARAMETERS = 'Invalid parameters';

  /**
   * The ipay88 OPSG "invalid reference" payment status message.
   *
   * @var string
   */
  const STATUS_MESSAGE_INVALID_REFERENCE = 'Record not found';

  /**
   * The ipay88 OPSG "iPay88 canceled" payment status message.
   *
   * @var string
   */
  const STATUS_MESSAGE_IPAY88_CANCELED = 'M88Admin';

  /**
   * The ipay88 OPSG "iPay88 exceeded limit" payment status message.
   *
   * @var string
   */
  const STATUS_MESSAGE_IPAY88_EXCEEDED_LIMIT = 'Limited by per day maximum number of requery';

  /**
   * The ipay88 OPSG "succeeded" payment status message.
   *
   * @var string
   */
  const STATUS_MESSAGE_SUCCEEDED = '00';

  /**
   * The ipay88 OPSG "succeeded" payment status.
   *
   * @var integer
   */
  const STATUS_SUCCEEDED = 1;

  /**
   * The areas supported by iPay88 OPSG.
   *
   * @see \DocsLab\IPay88\Helper::getArea()
   * @see \DocsLab\IPay88\Helper::getAreas()
   * @see \DocsLab\IPay88\Helper::getAreasByCurrency()
   * @see \DocsLab\IPay88\Helper::getCurrenciesByArea()
   *
   * @var array
   */
  protected static $areas = [
    self::AREA_MALAYSIA => ['label' => 'Malaysia'],
    self::AREA_MULTICURRENCY => ['label' => 'Others'],
  ];

  /**
   * The character encodings supported by iPay88 OPSG.
   *
   * The character encoding keys correspond to the values of the iPay88 OPSG
   * "Lang" parameter.
   *
   * Note: some character encoding identifiers are mispelled by iPay88 OPSG.
   * Use {@link \DocsLab\IPay88\Helper::mapCharacterEncodingToIPay88()}
   * to retrieve the character encoding identifier expected by iPay88 OPSG.
   *
   * @see \DocsLab\IPay88\Helper::getCharacterEncoding()
   * @see \DocsLab\IPay88\Helper::getCharacterEncodings()
   * @see \DocsLab\IPay88\Helper::mapCharacterEncodingToIPay88()
   * @see \DocsLab\IPay88\Helper::mapCharacterEncodingToLanguage()
   * @see \DocsLab\IPay88\Helper::mapLanguageToCharacterEncoding()
   *
   * @var array
   */
  protected static $character_encodings = [
    'BIG5' => [
      'label' => 'BIG5',
      'language' => 'zh-hant',
    ],
    'GB2312' => [
      'label' => 'GB2312',
      'language' => 'zh-hans',
    ],
    'GB18030' => [
      'ipay88' => 'GD18030', // Mispelled by iPay88 OPSG.
      'label' => 'GB18030',
      'language' => 'zh-hans',
    ],
    'ISO-8859-1' => [
      'label' => 'ISO-8859-1',
      'language' => 'en',
    ],
    'UTF-8' => [
      'label' => 'UTF-8',
      'language' => 'en',
    ],
  ];

  /**
   * The currencies supported by iPay88 OPSG.
   *
   * The currency keys correspond to the values of the iPay88 OPSG "Currency"
   * parameter.
   *
   * Note: IDR, INR, PHP, and TWD currencies are specified into the technical
   * specifications as supported by iPay88 OSPG for testing purpose only and are
   * not available within this library.
   *
   * @see \DocsLab\IPay88\Helper::getAmountTestValue()
   * @see \DocsLab\IPay88\Helper::getAreasByCurrency()
   * @see \DocsLab\IPay88\Helper::getCurrencies()
   * @see \DocsLab\IPay88\Helper::getCurrenciesByArea()
   * @see \DocsLab\IPay88\Helper::getCurrency()
   *
   * @var array
   */
  protected static $currencies = [
    'AUD' => [
      'amount_test_value' => 1,
      'areas' => [self::AREA_MULTICURRENCY],
      'label' => 'Australian Dollar',
    ],
    'CAD' => [
      'amount_test_value' => 1,
      'areas' => [self::AREA_MULTICURRENCY],
      'label' => 'Canadian Dollar',
    ],
    'EUR' => [
      'amount_test_value' => 1,
      'areas' => [self::AREA_MULTICURRENCY],
      'label' => 'Euro',
    ],
    'GBP' => [
      'amount_test_value' => 1,
      'areas' => [self::AREA_MULTICURRENCY],
      'label' => 'Pound Sterling',
    ],
    'HKD' => [
      'amount_test_value' => 2.5,
      'areas' => [self::AREA_MULTICURRENCY],
      'label' => 'Hong Kong Dollar',
    ],
//     'IDR' => [
//       'amount_test_value' => 3000,
//       'areas' => [self::AREA_MULTICURRENCY],
//       'label' => 'Indonesian Rupiah',
//     ],
//     'INR' => [
//       'amount_test_value' => 15,
//       'areas' => [self::AREA_MULTICURRENCY],
//       'label' => 'Indian Rupee',
//     ],
    'MYR' => [
      'amount_test_value' => 1,
      'areas' => [self::AREA_MALAYSIA, self::AREA_MULTICURRENCY],
      'label' => 'Malaysian Ringgit',
    ],
//     'PHP' => [
//       'amount_test_value' => 15,
//       'areas' => [self::AREA_MULTICURRENCY],
//       'label' => 'Philippine Peso',
//     ],
    'SGD' => [
      'amount_test_value' => 1,
      'areas' => [self::AREA_MULTICURRENCY],
      'label' => 'Singapore Dollar',
    ],
    'THB' => [
      'amount_test_value' => 15,
      'areas' => [self::AREA_MULTICURRENCY],
      'label' => 'Thailand Baht',
    ],
//     'TWD' => [
//       'amount_test_value' => 15,
//       'areas' => [self::AREA_MULTICURRENCY],
//       'label' => 'New Taiwan Dollar',
//     ],
    'USD' => [
      'amount_test_value' => 1,
      'areas' => [self::AREA_MULTICURRENCY],
      'label' => 'US Dollar',
    ],
  ];

  // "The return page URL not exist":
  //  - Make sure the correct merchant code is used.
  //  - Provide the Request URL to support@ipay88.com.my before the integration.
  //  - Make sure response URL is specify through ResponseURL field in request page or had updated in iPay88.

  // "Fail (Card issuing bank do not honor the transaction)": Please contact credit card issuer bank to check whether the card can be used for online purchases.

  /**
   * The errors returned by iPay88 OPSG within payment responses.
   *
   * The error keys correspond to the values of the iPay88 OPSG "ErrDesc"
   * parameter.
   *
   * @see \DocsLab\IPay88\Helper::getError()
   * @see \DocsLab\IPay88\Helper::getErrors()
   *
   * @var array
   */
  protected static $errors = [
    'Customer Cancel Transaction' => [
      'description' => 'The payment has been canceled by the customer.',
      'label' => 'Canceled payment',
    ],
    'Duplicate reference number' => [
      'description' => 'The payment reference has already been processed by iPay88 Online Payment Switching Gateway within another payment request.',
      'label' => 'Invalid payment reference',
      'parameters' => ['RefNo'],
    ],
    'Fail(Bank Declined Transaction)' => [
      'description' => "The payment has been declined by the customer's bank.",
      'label' => 'Declined payment',
    ],
    'Invalid merchant' => [
      'description' => 'The seller identifier is invalid.',
      'label' => 'Invalid seller identifier',
      'parameters' => ['MerchantCode'],
    ],
    'Invalid merchant code' => [
      'description' => 'The seller identifier is invalid.',
      'label' => 'Invalid seller identifier',
      'parameters' => ['MerchantCode'],
    ],
    'Invalid parameters' => [
      'description' => 'One or more parameters are invalid.',
      'label' => 'Invalid parameters',
    ],
    'Overlimit per transaction' => [
      'description' => 'The amount exceeds the allowed limit.',
      'label' => 'Exceeded amount limit',
      'parameters' => ['Amount'],
    ],
    'Payment not allowed' => [
      'description' => 'The payment method is not allowed by iPay88 Online Payment Switching Gateway.',
      'label' => 'Unallowed payment method',
      'parameters' => ['PaymentId'],
    ],
    'Permission not allow' => [
      'description' => 'The notify, the return and/or the request URLs are not allowed by iPay88 Online Payment Switching Gateway.',
      'label' => 'Invalid URLs',
      'parameters' => ['BackendURL', 'ResponseURL'],
    ],
    'Signature not match' => [
      'description' => 'The signature is invalid.',
      'label' => 'Invalid signature',
      'parameters' => ['Signature'],
    ],
    'Status not approved' => [
      'description' => 'The seller is not allowed by iPay88 Online Payment Switching Gateway.',
      'label' => 'Unallowed seller',
      'parameters' => ['MerchantCode'],
    ],
    'Transaction Timeout' => [
      'description' => 'The payment allowed time has expired.',
      'label' => 'Expired payment',
    ],
  ];

  /**
   * The languages supported by iPay88 OPSG.
   *
   * The language identifiers are compliant with IETF BCP 47, RFC 5646, RFC
   * 5645, and RFC 4647.
   *
   * Note: iPay88 OPSG uses the character encoding identifiers as values of the
   * "Lang" parameter.
   * Use {@link \DocsLab\IPay88\Helper::mapLanguageToCharacterEncoding()}
   * to retrieve the character encoding identifier expected by iPay88 OPSG.
   *
   * @see \DocsLab\IPay88\Helper::getLanguage()
   * @see \DocsLab\IPay88\Helper::getLanguages()
   * @see \DocsLab\IPay88\Helper::mapCharacterEncodingToLanguage()
   * @see \DocsLab\IPay88\Helper::mapLanguageToCharacterEncoding()
   *
   * @var array
   */
  protected static $languages = [
    'en' => [
      'character_encoding' => 'UTF-8',
      'character_encodings' => ['ISO-8859-1', 'UTF-8'],
      'label' => 'English',
    ],
    'zh-hans' => [
      'character_encoding' => 'GB18030',
      'character_encodings' => ['GB2312', 'GB18030'],
      'label' => 'Simplified Chinese',
    ],
    'zh-hant' => [
      'character_encoding' => 'BIG5',
      'character_encodings' => ['BIG5'],
      'label' => 'Traditional Chinese',
    ],
  ];

  /**
   * The payment methods supported by iPay88 OPSG.
   *
   * The payment method keys correspond to the values of the iPay88 OPSG
   * "PaymentId" parameter.
   *
   * Delayed payment methods: iPay88 OPSG will initially return "6" (Pending
   * Payment) as status value to the return URL. Once the customer pays, iPay88
   * OPSG will return "1" (Success) as status value to the notify URL.
   *
   * @see \DocsLab\IPay88\Helper::getPaymentMethod()
   * @see \DocsLab\IPay88\Helper::getPaymentMethods()
   *
   * @var array
   */
  protected static $payment_methods = [
    2 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Credit Card',
    ],
    6 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Maybank2U',
    ],
    8 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Alliance Online',
    ],
    10 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'AmOnline',
    ],
    14 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'RHB Online',
    ],
    15 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Hong Leong Online',
    ],
    20 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'CIMB Click',
    ],
    22 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Web Cash',
    ],
    25 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'USD',
      'label' => 'Credit Card',
    ],
    31 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Public Bank Online',
    ],
    35 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'GBP',
      'label' => 'Credit Card',
    ],
    36 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'THB',
      'label' => 'Credit Card',
    ],
    37 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'CAD',
      'label' => 'Credit Card',
    ],
    38 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'SGD',
      'label' => 'Credit Card',
    ],
    39 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'AUD',
      'label' => 'Credit Card',
    ],
    40 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'MYR',
      'label' => 'Credit Card',
    ],
    41 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'EUR',
      'label' => 'Credit Card',
    ],
    42 => [
      'area' => self::AREA_MULTICURRENCY,
      'currency' => 'HKD',
      'label' => 'Credit Card',
    ],
    48 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'PayPal',
    ],
    55 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Credit Card Pre-Auth',
    ],
    102 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Bank Rakyat Internet Banking',
    ],
    103 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Affin Online',
    ],
    122 => [
      'label' => 'Pay4Me',
      'currency' => 'MYR',
      'delayed' => TRUE,
      'area' => self::AREA_MALAYSIA,
    ],
    124 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'BSN Online',
    ],
    134 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Bank Islam',
    ],
    152 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'UOB',
    ],
    163 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Hong Leong PEx+',
    ],
    166 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Bank Muamalat',
    ],
    167 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'OCBC',
    ],
    168 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Standard Chartered Bank',
    ],
    173 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'delay' => 604800, // 7 days
      'delayed' => TRUE,
      'label' => 'CIMB Virtual Account',
    ],
    198 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'HSBC Online Banking',
    ],
    199 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Kuwait Finance House',
    ],
    210 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'Boost Wallet',
    ],
    243 => [
      'area' => self::AREA_MALAYSIA,
      'currency' => 'MYR',
      'label' => 'VCash',
    ],
  ];

  /**
   * The signature types supported by iPay88 OPSG.
   *
   * The signature type keys correspond to the values of the iPay88 OPSG
   * "SignatureType" parameter.
   *
   * @see \DocsLab\IPay88\Helper::getSignatureType()
   * @see \DocsLab\IPay88\Helper::getSignatureTypes()
   *
   * @var array
   */
  protected static $signature_types = [
    'SHA256' => [
      'label' => 'SHA256',
    ],
  ];

  /**
   * The status messages returned by iPay88 OPSG within payment status
   * responses.
   *
   * The status message keys correspond to the raw values of the iPay88 OPSG
   * payment status responses.
   *
   * @see \DocsLab\IPay88\Helper::getStatusMessage()
   * @see \DocsLab\IPay88\Helper::getStatusMessages()
   * @see \DocsLab\IPay88\Message\PaymentStatusResponseMessage
   *
   * @var array
   */
  protected static $status_messages = [
    self::STATUS_MESSAGE_FAILED => [
      'description' => 'The payment has failed.',
      'label' => 'Failed payment',
      'status' => self::STATUS_FAILED,
    ],
    self::STATUS_MESSAGE_INVALID_AMOUNT => [
      'description' => 'The payment amount is invalid.',
      'ipay88_parameter' => 'Amount',
      'label' => 'Invalid payment amount.',
      'status' => self::STATUS_ERRORED,
    ],
    self::STATUS_MESSAGE_INVALID_PARAMETERS => [
      'description' => 'One or more payment parameters are invalid.',
      'label' => 'Invalid payment parameters',
      'status' => self::STATUS_ERRORED,
    ],
    self::STATUS_MESSAGE_INVALID_REFERENCE => [
      'description' => 'The payment reference is invalid.',
      'ipay88_parameter' => 'RefNo',
      'label' => 'Invalid payment reference',
      'status' => self::STATUS_ERRORED,
    ],
    self::STATUS_MESSAGE_IPAY88_CANCELED => [
      'description' => 'The payment has been canceled by iPay88 Online Payment Switching Gateway administrator.',
      'label' => 'Canceled payment by iPay88',
      'status' => self::STATUS_FAILED,
    ],
    self::STATUS_MESSAGE_IPAY88_EXCEEDED_LIMIT => [
      'description' => 'The number of payment status requests exceeds the allowed limit per day.',
      'label' => 'Exceeded limit',
      'status' => self::STATUS_ERRORED,
    ],
    self::STATUS_MESSAGE_SUCCEEDED => [
      'description' => 'The payment has succeeded.',
      'label' => 'Succeeded payment',
      'status' => self::STATUS_SUCCEEDED,
    ],
  ];

  /**
   * The statuses returned by iPay88 OPSG within payment responses.
   *
   * The statuses keys correspond to the values of the iPay88 OPSG "Status"
   * parameter.
   *
   * @see \DocsLab\IPay88\Helper::getStatus()
   * @see \DocsLab\IPay88\Helper::getStatuses()
   * @see \DocsLab\IPay88\Message\PaymentResponseMessage
   *
   * @var array
   */
  protected static $statuses = [
    self::STATUS_DELAYED => [
      'description' => 'The payment is delayed.',
      'label' => 'Delayed payment',
    ],
    self::STATUS_FAILED => [
      'description' => 'The payment failed.',
      'label' => 'Failed payment',
    ],
    self::STATUS_SUCCEEDED => [
      'description' => 'The payment succeeded.',
      'label' => 'Succeeded payment',
    ],
  ];

  /**
   * Formats an amount string value to a float value.
   *
   * @throws \InvalidArgumentException if the passed amount is not a string
   *   value.
   *
   * @param string $amount
   *   The amount string value.
   *
   * @return float
   *   The amount float value.
   */
  public static function formatAmountToFloat($amount) {
    if (!is_string($amount)) {
      throw new \InvalidArgumentException(sprintf('The amount "%s" must be a string value, %s given.', (string) $amount, gettype($amount)));
    }
    return floatval(strtr($amount, ',', ''));
  }

  /**
   * Formats an amount numeric value to a hashable string value.
   *
   * The hashable string value is formatted with two decimals and without
   * decimals separator nor thousands separators (e.g., "12345678").
   *
   * @see \DocsLab\IPay88\MessageInterface::generateMessageSignature()
   *
   * @throws \InvalidArgumentException if the passed amount is not a numeric
   *   value.
   *
   * @param integer|float $amount
   *   The amount numeric value.
   *
   * @return string
   *   The amount hashable string value.
   */
  public static function formatAmountToHashableString($amount) {
    if (!is_int($amount) && !is_float($amount)) {
      throw new \InvalidArgumentException(sprintf('The amount "%s" must be a numeric value, %s given.', (string) $amount, gettype($amount)));
    }
    return (string) number_format($amount, 2, '', '');
  }

  /**
   * Formats an amount numeric value to a string value.
   *
   * The string value is formatted with two decimals, dot "." as decimals
   * separator and comma "," as thousands separators (e.g., "123,456.78").
   *
   * @throws \InvalidArgumentException if the passed amount is not a numeric
   *   value.
   *
   * @param integer|float $amount
   *   The amount numeric value.
   *
   * @return string
   *   The amount string value.
   */
  public static function formatAmountToString($amount) {
    if (!is_int($amount) && !is_float($amount)) {
      throw new \InvalidArgumentException(sprintf('The amount "%s" must be a numeric value, %s given.', (string) $amount, gettype($amount)));
    }
    return number_format($amount, 2, '.', ',');
  }

  /**
   * Gets the amount test value for testing purpose.
   *
   * @param string $currency
   *   The currency identifier.
   *
   * @return float
   *   The amount test value.
   */
  public static function getAmountTestValue($currency) {
    return self::getCurrency($currency)['amount_test_value'];
  }

  /**
   * Gets an area supported by iPay88 OPSG.
   *
   * @throws \InvalidArgumentException if the given area identifier is not
   *   supported by iPay88 OPSG.
   *
   * @param integer $area
   *   The area identifier.
   *
   * @return array
   *   The area informations.
   */
  public static function getArea($area) {
    if (!isset(self::$areas[$area])) {
      throw new \InvalidArgumentException(sprintf('The area "%d" is not supported by iPay88 Online Payment Switching Gateway.', $area));
    }
    return self::$areas[$area];
  }

  /**
   * Gets all areas supported by iPay88 OPSG.
   *
   * @return array
   *   All areas informations keyed by area identifier.
   */
  public static function getAreas() {
    return self::$areas;
  }

  /**
   * Gets the areas supported by iPay88 OPSG for a given currency.
   *
   * @param string $currency
   *   The currency identifier.
   *
   * @return array
   *   The areas informations for the given currency.
   */
  public static function getAreasByCurrency($currency) {
    $currency = self::getCurrency($currency);
    return array_filter(self::$areas, function ($area) use ($currency) {
      return in_array($area, $currency['areas']);
    }, \ARRAY_FILTER_USE_KEY);
  }

  /**
   * Gets a character encoding supported by iPay88 OPSG.
   *
   * @throws \InvalidArgumentException if the given character encoding
   *   identifier is not supported by iPay88 OPSG.
   *
   * @param string $character_encoding
   *   The character encoding identifier.
   *
   * @return array
   *   The character encoding informations.
   */
  public static function getCharacterEncoding($character_encoding) {
    if (!isset(self::$character_encodings[$character_encoding])) {
      throw new \InvalidArgumentException(sprintf('The character encoding "%s" is not supported by iPay88 Online Payment Switching Gateway.', $character_encoding));
    }
    return self::$character_encodings[$character_encoding];
  }

  /**
   * Gets all character enchodings supported by iPay88 OPSG.
   *
   * @return array
   *   All character encodings informations keyed by character encoding
   *   identifier.
   */
  public static function getCharacterEncodings() {
    return self::$character_encodings;
  }

  /**
   * Gets all currencies supported by iPay88 OPSG.
   *
   * @return array
   *   All currencies informations keyed by currency identifier.
   */
  public static function getCurrencies() {
    return self::$currencies;
  }

  /**
   * Gets the currencies supported by iPay88 OPSG for a given area.
   *
   * @param string $area
   *   The area identifier.
   *
   * @return array
   *   The currencies informations for the given area.
   */
  public static function getCurrenciesByArea($area) {
    return array_filter(self::$currencies, function ($currency) use ($area) {
      return in_array($area, $currency['areas']);
    });
  }

  /**
   * Gets a currency supported by iPay88 OPSG.
   *
   * @throws \InvalidArgumentException if the given currency identifier is not
   *   supported by iPay88 OPSG.
   *
   * @param string $currency
   *   The currency identifier.
   *
   * @return array
   *   The currency informations.
   */
  public static function getCurrency($currency) {
    if (!isset(self::$currencies[$currency])) {
      throw new \InvalidArgumentException(sprintf('The currency "%s" is not supported by iPay88 Online Payment Switching Gateway.', $currency));
    }
    return self::$currencies[$currency];
  }

  /**
   * Gets an error returned by iPay88 OPSG within payment responses.
   *
   * @see \DocsLab\IPay88\Message\PaymentResponseMessage
   *
   * @throws \InvalidArgumentException if the given error identifier is invalid
   *   e.g., should not be returned by iPay88 OPSG.
   *
   * @param string $error
   *   The error identifier.
   *
   * @return array
   *   The error informations.
   */
  public static function getError($error) {
    if (!isset(self::$errors[$error])) {
      throw new \InvalidArgumentException(sprintf('The error "%s" is invalid and should not be returned by iPay88 Online Payment Switching Gateway.', $error));
    }
    return self::$errors[$error];
  }

  /**
   * Gets all errors returned by iPay88 OPSG within payment responses.
   *
   * @see \DocsLab\IPay88\Message\PaymentResponseMessage
   *
   * @return array
   *   All errors informations keyed by error identifier.
   */
  public static function getErrors() {
    return self::$errors;
  }

  /**
   * Gets a language supported by iPay88 OPSG.
   *
   * @throws \InvalidArgumentException if the given language identifier is not
   *   supported by iPay88 OPSG.
   *
   * @param string $language
   *   The language identifier.
   *
   * @return array
   *   The language informations.
   */
  public static function getLanguage($language) {
    if (!isset(self::$languages[$language])) {
      throw new \InvalidArgumentException(sprintf('The language "%s" is not supported by iPay88 Online Payment Switching Gateway.', $language));
    }
    return self::$languages[$language];
  }

  /**
   * Gets all languages supported by iPay88 OPSG.
   *
   * @return array
   *   All languages informations keyed by language identifier.
   */
  public static function getLanguages() {
    return self::$languages;
  }

  /**
   * Gets a status returned by iPay88 OPSG within payment responses.
   *
   * @see \DocsLab\IPay88\Message\PaymentResponseMessage
   *
   * @throws \InvalidArgumentException if the given status identifier is invalid
   *   e.g., should not be returned by iPay88 OPSG.
   *
   * @param string $status
   *   The status identifier.
   *
   * @return array
   *   The status informations.
   */
  public static function getStatus($status) {
    if (!isset(self::$status[$status])) {
      throw new \InvalidArgumentException(sprintf('The status "%s" is invalid and should not be returned by iPay88 Online Payment Switching Gateway.', $status));
    }
    return self::$status[$status];
  }

  /**
   * Gets all statuses returned by iPay88 OPSG within payment responses.
   *
   * @see \DocsLab\IPay88\Message\PaymentResponseMessage
   *
   * @return array
   *   All statuses informations keyed by status identifier.
   */
  public static function getStatuses() {
    return self::$statuses;
  }

  /**
   * Gets a status message returned by iPay88 OPSG within payment status
   * responses.
   *
   * @see \DocsLab\IPay88\Message\PaymentStatusResponseMessage
   *
   * @throws \InvalidArgumentException if the given status message identifier is
   *   invalid e.g., should not be returned by iPay88 OPSG.
   *
   * @param string $status_message
   *   The status message identifier.
   *
   * @return array
   *   The status message informations.
   */
  public static function getStatusMessage($status_message) {
    if (!isset(self::$status_messages[$status_message])) {
      throw new \InvalidArgumentException(sprintf('The status message "%s" is invalid and should not be returned by iPay88 Online Payment Switching Gateway.', $status_message));
    }
    return self::$status_messages[$status_message];
  }

  /**
   * Gets all status messages returned by iPay88 OPSG within payment status
   * responses.
   *
   * @see \DocsLab\IPay88\Message\PaymentStatusResponseMessage
   *
   * @return array
   *   All status messages informations keyed by status message identifier.
   */
  public static function getStatusMessages() {
    return self::$status_messages;
  }

  /**
   * Gets a signature type supported by iPay88 OPSG.
   *
   * @throws \InvalidArgumentException if the given signature type identifier is
   *   not supported by iPay88 OPSG.
   *
   * @param string $signature_type
   *   The signature type identifier.
   *
   * @return array
   *   The signature type informations.
   */
  public static function getSignatureType($signature_type) {
    if (!isset(self::$signature_types[$signature_type])) {
      throw new \InvalidArgumentException(sprintf('The signature type "%s" is not supported by iPay88 Online Payment Switching Gateway.', $signature_type));
    }
    return self::$signature_types[$signature_type];
  }

  /**
   * Gets all signature types supported by iPay88 OPSG.
   *
   * @return array
   *   All signature types informationskeyed by signature type identifier.
   */
  public static function getSignatureTypes() {
    return self::$signature_types;
  }







  /**
   * Gets the payment methods supported by iPay88 OPSG.
   *
   * @todo Update documentation.
   *
   * @param integer|NULL $payment_type
   *   Filter the payment methods against the provided payment type, otherwise
   *   all supported payment methods are returned.
   * @param string|NULL $currency
   *   Filter the payment methods against the provided currency, otherwise all
   *   supported payment methods are returned.
   *
   * @return string[][]
   *   The payment methods keyed by identifier.
   */
  public static function getSupportedMethods($payment_type = NULL, $currency = NULL) {
    if (!isset($payment_type) && !isset($currency)) {
      return self::$payment_methods;
    }

    if (isset($payment_type) && isset($currency)) {
      $callback = function ($value) use ($payment_type, $currency) {
        return $payment_type == $value['area'] && $currency == $value['currency'];
      };
    }
    elseif (isset($payment_type)) {
      $callback = function ($value) use ($payment_type) {
        return $payment_type == $value['area'];
      };
    }
    else {
      $callback = function ($value) use ($currency) {
        return $currency == $value['currency'];
      };
    }

    return array_filter(self::$payment_methods, $callback);
  }







  /**
   * Maps a character encoding to the related iPay88 OPSG character encoding.
   *
   * Note: iPay88 OPSG uses mispelled character encoding identifiers. This
   * method ensure to use the character encoding identifier expected by Ipay88
   * OPSG.
   *
   * @param string $character_encoding
   *   The character encoding.
   *
   * @return string
   *   The iPay88 OPSG character encoding.
   */
  public static function mapCharacterEncodingToIPay88($character_encoding) {
    $character_encoding_info = self::getCharacterEncoding($character_encoding);
    return isset($character_encoding_info['ipay88']) ? $character_encoding_info['ipay88'] : $character_encoding;
  }

  /**
   * Maps a character encoding to the default language supported by iPay88 OPSG.
   *
   * Note: iPay88 OPSG uses character encoding as language identifier within
   * payment requests (iPay88 OPSG parameter: "Lang").
   *
   * @param string $character_encoding
   *   The character encoding.
   *
   * @return string
   *   The language.
   */
  public static function mapCharacterEncodingToLanguage($character_encoding) {
    return self::getCharacterEncoding($character_encoding)['language'];
  }

  /**
   * Maps a language to the default character encoding supported by iPay88 OPSG.
   *
   * Note: iPay88 OPSG uses character encoding as language identifier within
   * payment requests (iPay88 OPSG parameter: "Lang").
   *
   * @param string $language
   *   The language.
   *
   * @return string
   *   The character encoding.
   */
  public static function mapLanguageToCharacterEncoding($language) {
    return self::getLanguage($language)['character_encoding'];
  }

}
