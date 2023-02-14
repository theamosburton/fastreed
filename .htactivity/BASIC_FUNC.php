<?php

class BasicFunctions
{
  private $DB_CONNECT;
  private $DB;
  private $AUTH;

  function __construct()
  {

    // Creating Instances
    $this->DB_CONNECT = new Database();
    $this->AUTH = new Auth();

    // Get Connection
    $this->DB = $this->DB_CONNECT->DBConnection();
  }

  public function getIP()
  {
    $IP_ADDRESS = $_SERVER['REMOTE_ADDR'];
    filter_var($IP_ADDRESS, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    return $IP_ADDRESS;
  }

  public function createNewID($tableName, $prefix)
  {
    $ID_TABLE_NAME = $tableName;
    $date = date('Y-m-d');
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $newID = $year.$month.$day;
    $sql = "SELECT * FROM $ID_TABLE_NAME WHERE tdate ='$date'";
    $result = mysqli_query($this->DB, $sql);
    $x =  mysqli_num_rows($result);
    $noOfRow = $this->realNum($x);
    if ($noOfRow < 100) {
      $newID .= '00000'.$noOfRow;
    }elseif ($noOfRow < 1000) {
      $newID .= '0000'.$noOfRow;
    }elseif ($noOfRow < 10000) {
      $newID .= '000'.$noOfRow;
    }elseif ($noOfRow < 100000) {
      $newID .= '00'.$noOfRow;
    }elseif ($noOfRow < 1000000) {
      $newID .= '0'.$noOfRow;
    }elseif ($noOfRow < 10000000) {
      $newID .= $noOfRow;
    }
    $newID = $prefix.$newID;
    return $newID;
  }

  public  function realNum($x){
    if ($x < 10) {
      $y = '0'.$x;
    }else {
      $y = $x;
    }
    return $y;
  }

  public function generateOTP($y){
    $DB = $this->DB_CONNECT->DBConnection();
    $randOTP ="";
   for ($x = 1; $x <= $y; $x++) {
       // Set each digit
       $randOTP .= random_int(0, 9);
   }

   $OTP = "";
   $sql = "SELECT * FROM OTP WHERE OTP = '$randOTP'";
   $result = mysqli_query($DB, $sql);
     if (mysqli_num_rows($result)) {
       generateOTP($x);
     }else {
       $OTP = $randOTP;
     }
     return $OTP;
 }

 public function checkOTPEd($inputValue, $dat){
   $DB = $this->DB_CONNECT->DBConnection();
   // get session ID
   $getSessionSql = "SELECT sessionID FROM users_register WHERE $dat = '".$inputValue."'";
   $getSessionID = mysqli_query($DB, $getSessionSql);
   $row = mysqli_fetch_assoc($getSessionID);
   $sessionID = $row['sessionID'];

   // Check otp is sent or not
   $getOTP = "SELECT * FROM OTP WHERE sessionID = '$sessionID'";
   $getOTPResult = mysqli_query($DB, $getOTP);
   if (mysqli_num_rows($getOTPResult)) {
     // Check OTP is expired ot Not
     $row = mysqli_fetch_assoc($getOTPResult);
     $otpTime = $row['sentTime'];
     $expiryTime = $otpTime + 120;//  minutes OTP Expiry
     if ($expiryTime < time()) {
       // DELETE DATA FROM OTP TABLE
       $deleteFromUsersRegisterSql = "DELETE FROM users_register WHERE sessionID = '$sessionID'";
       $deleteFromUsersRegister = mysqli_query($DB, $deleteFromUsersRegisterSql);
       if ($deleteFromUsersRegister) {
         // DELETE DATA FROM OTP table
         $deleteFromOTPSql = "DELETE FROM OTP WHERE sessionID = '$sessionID'";
         $deleteFromOTP = mysqli_query($DB, $deleteFromOTPSql);
         if ($deleteFromOTP) {
           // OTP expired and sucessfuly deleted
           $OTPed = true;
         }else {
           // OTP not expired
           $OTPed = false;
         }
       }else {
         // OTP not expired
         $OTPed = false;
       }
     }else {
       // OTP not expired
       $OTPed = false;
     }
   }else {
     // DELETE DATA FROM OTP TABLE
     $deleteFromUsersRegisterSql = "DELETE FROM users_register WHERE sessionID = '$sessionID'";
     $deleteFromUsersRegister = mysqli_query($DB, $deleteFromUsersRegisterSql);
     if ($deleteFromUsersRegister) {
       // OTP expired and sucessfuly deleted
       $OTPed = true;
     }else {
       // OTP not expired
       $OTPed = false;
     }
   }
   return $OTPed;
 }

}

 ?>
