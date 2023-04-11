<?php 
namespace App\UserService;

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
    if ($name == "") {
      return FALSE;
    }
    elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * Function to check the formate of UserName
   * 
   *   @return bool
   *     returns true or false based on the formate of username
   */
  private function userNameValidation(){
    if ($this->userName == '') {
      return FALSE;
    }
    elseif (!preg_match("/^[a-zA-Z0-9]*$/", $this->userName)) {
      return FALSE;
    }
    return TRUE;
  }
  /**
   * Function to Validate the Password field formate
   * 
   *   @return bool
   *     returns True or False based on the passwoed formate
   */
  private function validatePassword() {
    if ( $this->password == "") {
      return FALSE;
    }
    elseif (!preg_match("/[a-z]/",$this->password)) {
      return FALSE;
    }
    elseif (!preg_match("/[A-Z]/",$this->password)) {
      return FALSE;
    }
    elseif (!preg_match("/[0-9]/",$this->password)) {
      return FALSE;
    }
    elseif (!preg_match("/[@#$%&!]/",$this->password)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
  * Function for trimming the data.
  *   @param string $data
  *     takes the string data to be trimmed

  *   @return string
  *     returns the trimmed data
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
  *     returnss True or False based on confirm Password field match
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
  *     returns true or false based on the image formate
  */
  public function coverImageFormate() {
    if(!is_null($this->imageFile)){
      // generate a random name for the file but keep the extension
      $extension = $this->imageFile->getClientOriginalExtension();
      if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension != 'gif') {
        return FALSE;
      }
      else {
        $this->randomPicName = uniqid().".".$this->imageFile->getClientOriginalExtension();
        $path = "../public/cover/";
        $this->imageFile->move($path, $this->randomPicName); // move the file to a path
        return TRUE;
      }
    }
    else {
      return TRUE;
    }
  }
}
