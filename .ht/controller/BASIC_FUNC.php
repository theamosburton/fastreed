<?php

class BasicFunctions
{
  public $DB_CONNECT;
  private $DB;
  private $AUTH;
  private $INCREMENT = '';

  function __construct()
  {

    // Creating Instances
    $this->DB_CONNECT = new Database();
    $this->AUTH = new Auth();

    // Get Connection
    $this->DB = $this->DB_CONNECT->DBConnection();
  }

  public function closeConnection()
    {
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }

  public function getIP(){
    $IP_ADDRESS = $_SERVER['REMOTE_ADDR'];
    filter_var($IP_ADDRESS, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    return $IP_ADDRESS;
  }

  public function gitIsUpdated(){
      shell_exec('git fetch fastreed');
      // Get the SHA hash of the latest commit on the local and remote branches
      $localSha = shell_exec('git rev-parse HEAD');
      $remoteSha = shell_exec('git rev-parse fastreed/main');
      // Compare the local and remote branches
      // $diff = shell_exec("git diff $localSha $remoteSha");
      if ($localSha != $remoteSha) {
      $return = false;
      // If there are differences, return false
      } else {
      // If there are no differences, return true
      $return = true;
      }
      return $return;
  }

  public function createUpdatedID($tableName, $prefix, $columnName){
    $newID = $this->createNewID($tableName, $prefix);
    $this->INCREMENT = 1;

      do {
          $sql = "SELECT * FROM $tableName WHERE $columnName = '$newID'";
          $result = mysqli_query($this->DB, $sql);
          $rowNo = mysqli_num_rows($result);
          if ($rowNo) {
              $this->INCREMENT++;
              $newID = $this->createNewID($tableName, $prefix);
          }
      } while ($rowNo);

      return $newID;
  }


  public function createNewID($tableName, $prefix){
    $ID_TABLE_NAME = $tableName;
    $date = date('Y-m-d');
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $newID = $year.$month.$day;
    $sql = "SELECT * FROM $ID_TABLE_NAME WHERE tdate ='$date'";
    $result = mysqli_query($this->DB, $sql);
    if (!$result) {
      $noOfRow = $this->realNum('0');
    }else {
      $x =  mysqli_num_rows($result);
      if (!empty($this->INCREMENT) || $this->INCREMENT != 0) {
        $x = $x + 1;
      }
      $noOfRow = $this->realNum($x);
    }

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


  public function convertToOrdinal($number) {
    $suffix = 'th'; // Default suffix is 'th'

    // Exceptions for 1st, 2nd, and 3rd
    if ($number % 10 == 1 && $number % 100 != 11) {
        $suffix = 'st';
    } elseif ($number % 10 == 2 && $number % 100 != 12) {
        $suffix = 'nd';
    } elseif ($number % 10 == 3 && $number % 100 != 13) {
        $suffix = 'rd';
    }

    return $number . $suffix;
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
