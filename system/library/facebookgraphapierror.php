<?php
// Copyright 2017-present, Facebook, Inc.
// All rights reserved.

// This source code is licensed under the license found in the
// LICENSE file in the root directory of this source tree.

class FacebookGraphAPIError {
  const ACCESS_TOKEN_EXCEPTION_CODE = 190;
  const DUPLICATE_RETAILER_ID_EXCEPTION_CODE = 10800;
  const INVALID_ID_EXCEPTION_CODE = 100;
  const INVALID_ID_EXCEPTION_SUBCODE = 33;

  const ACCESS_TOKEN_EXCEPTION_MESSAGE = 'FACEBOOK_ACCESS_TOKEN_ERROR';

  public function __construct() {
  }

  public function hasErrorMessageFromFBAPICall($result) {
    return isset($result['error']);
  }

  private function getErrorCode($result) {
    return (isset($result['error']['code']))
      ? $result['error']['code']
      : null;
  }

  private function getErrorSubCode($result) {
    return (isset($result['error']['error_subcode']))
      ? $result['error']['error_subcode']
      : null;
  }

  public function checksForAccessTokenErrorAndThrowException(
    $result) {
    if ($this->getErrorCode($result) === self::ACCESS_TOKEN_EXCEPTION_CODE) {
      throw new Exception(
        self::ACCESS_TOKEN_EXCEPTION_MESSAGE,
        self::ACCESS_TOKEN_EXCEPTION_CODE);
    }
  }

  public function getErrorMessageFromFBAPICall($result) {
    if (!$this->hasErrorMessageFromFBAPICall($result)) {
      return null;
    }

    return is_array($result['error'])
      ? json_encode($result['error'])
      : $result['error'];
  }

  public function isInvalidIdError($result) {
    // gets the code and subcode and checks if it is invalid id
    return ($this->getErrorCode($result) == self::INVALID_ID_EXCEPTION_CODE
      && $this->getErrorSubCode($result) == self::INVALID_ID_EXCEPTION_SUBCODE);
  }

  // checks if there is already an existing
  // product object (product or product group) of the retailer_id on FB
  // this is indicated by error code 10800
  public function isDuplicateRetailerError($result) {
    return ($this->getErrorCode($result) ==
      self::DUPLICATE_RETAILER_ID_EXCEPTION_CODE);
  }

  // gets the duplicated product group retailer id
  public function getDuplicateProductGroupRetailerId($result) {
    return ($this->isDuplicateRetailerError($result)
      && isset($result['error']['error_data']['product_group_id']))
      ? $result['error']['error_data']['product_group_id']
      : null;
  }
}
