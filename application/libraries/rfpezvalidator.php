<?php

class RfpezValidator extends Laravel\Validator {

  /**
   * Validate that an attribute is a valid e-mail address.
   *
   * @param  string  $attribute
   * @param  mixed   $value
   * @return bool
   */
  protected function validate_dotgovonly($attribute, $value)
  {
    return preg_match('/\.gov$/', $value);
  }

}