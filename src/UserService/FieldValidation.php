<?php 
namespace App\UserService;

/**
 * Trait that contains the validator functions
 */
trait FieldValidation {

  /**
  * Function for the name field validation.
  * 
  * @param string $name
  *   Value of name fields.
  * 
  * @return bool
  *   Returns TRUE or FALSE based on validation.
  */
  private function nameValidation(string $name) {
    if ($name == "" || !preg_match("/^[a-zA-Z-' ]*$/", $name)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Function to check the formate of UserName
   * 
   *   @return bool
   *     Returns true or false based on the formate of username
   */
  private function userNameValidation(){
    if ($this->userName == '' || !preg_match("/^[a-zA-Z0-9]*$/", $this->userName)) {
      return FALSE;
    }
    return TRUE;
  }
  /**
   * Function to Validate the Password field formate
   * 
   *   @return bool
   *     Returns True or False based on the passwoed formate
   */
  private function validatePassword() {
    if ( $this->password == "" || !preg_match("/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&!]))+/", $this->password) || strlen($this->password) < 8) {
      return FALSE;
    }
    return TRUE;
  }

  /**
  * Function for trimming the data.
  *   @param string $data
  *     Takes the string data to be trimmed

  *   @return string
  *     Returns the trimmed data
  */
  public function trimData(string $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  /**
  * Function for checking wheher the password and
  * confirm password fields are same.
  * 
  *   @return bool
  *     Returnss True or False based on confirm Password field match
  */
  private function confPwdmatcher() {
    if ($this->password!= $this->confPwd) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
  * Function to validate the book's thumbnail Formate
  * 
  *   @return bool
  *     Returns true or false based on the image formate
  */
  public function coverImageFormate() {
    if (!is_null($this->imageFile)) {
      // generate a random name for the file but keep the extension
      $extension = $this->imageFile->getClientOriginalExtension();
      if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension != 'gif') {
        return FALSE;
      }
      else {
        $this->randomPicName = uniqid().".".$this->imageFile->getClientOriginalExtension();
        $path = "../public/coverImg/";
        $this->imageFile->move($path, $this->randomPicName); // move the file to a path
      }
    }
    return TRUE;
  }
}
