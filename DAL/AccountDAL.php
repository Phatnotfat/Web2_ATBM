<?php
// import
require('./AbstractionDAL.php');
require('../DTO/AccountDTO.php');

class AccountDAL extends AbstractionDAL
{
       private $actionSQL = null;
       public function __construct()
       {
              parent::__construct();
              $this->actionSQL = parent::getConn();

              // if ($this->actionSQL != null) {
              //        echo 'thanh cong';
              // }
       }
       // xóa một đối tượng bởi mã đối tượng 
       function deleteByID($code)
       {
              // do bảng FeedBack, Comment, Orders, EnterBallot có liên kết khóa ngoại đến thuộc tính userName của bảng Accounts. Nên khi xóa bảng phải kiểm tra dữ liệu của các khóa ngoại trước. Nếu thỏa các bảng kia không có tham chiếu đến dữ liệu đang được xóa thì mới cho phép xóa. Còn không sẽ báo lỗi.

              // kiểm tra dữ liệu các bảng có khóa ngoại tham chiếu
              $check_data_Feedback = "SELECT * FROM feedback WHERE userName = '$code'";
              $check_data_Comment = "SELECT * FROM comment WHERE userNameComment = '$code'";
              $check_data_Orders = "SELECT * FROM orders WHERE userName = '$code'";
              $check_data_EnterBallot = "SELECT * FROM enterballot WHERE userName = '$code'";

              $resutl_1 = $this->actionSQL->query($check_data_Feedback);
              $resutl_2 = $this->actionSQL->query($check_data_Comment);
              $resutl_3 = $this->actionSQL->query($check_data_Orders);
              $resutl_4 = $this->actionSQL->query($check_data_EnterBallot);

              // nếu tất cả các câu lệnh truy suất cho ra số dòng truy suất đều = 0 --> thỏa
              if ($resutl_1->num_rows < 1 && $resutl_2->num_rows < 1 && $resutl_3->num_rows < 1 && $resutl_4->num_rows < 1) {

                     $string = "DELETE FROM accounts WHERE userName = '$code' ";
                     // thuc hien truy suat
                     return $this->actionSQL->query($string);
              } else {
                     return false;
              }
       }

       // xóa một đối tượng bằng cách truyền đối tượng vào
       function delete($obj)
       {
              if ($obj != null) {
                     $code = $obj->getUsername();
                     // do bảng FeedBack, Comment, Orders, EnterBallot có liên kết khóa ngoại đến thuộc tính userName của bảng Accounts. Nên khi xóa bảng phải kiểm tra dữ liệu của các khóa ngoại trước. Nếu thỏa các bảng kia không có tham chiếu đến dữ liệu đang được xóa thì mới cho phép xóa. Còn không sẽ báo lỗi.

                     // kiểm tra dữ liệu các bảng có khóa ngoại tham chiếu
                     $check_data_Feedback = "SELECT * FROM feedback WHERE userName = '$code'";
                     $check_data_Comment = "SELECT * FROM comment WHERE userNameComment = '$code'";
                     $check_data_Orders = "SELECT * FROM orders WHERE userName = '$code'";
                     $check_data_EnterBallot = "SELECT * FROM enterballot WHERE userName = '$code'";

                     $resutl_1 = $this->actionSQL->query($check_data_Feedback);
                     $resutl_2 = $this->actionSQL->query($check_data_Comment);
                     $resutl_3 = $this->actionSQL->query($check_data_Orders);
                     $resutl_4 = $this->actionSQL->query($check_data_EnterBallot);

                     // nếu tất cả các câu lệnh truy suất cho ra số dòng truy suất đều = 0 --> thỏa
                     if ($resutl_1->num_rows < 1 && $resutl_2->num_rows < 1 && $resutl_3->num_rows < 1 && $resutl_4->num_rows < 1) {

                            $string = "DELETE FROM accounts WHERE userName = '$code' ";
                            // thuc hien truy suat
                            return $this->actionSQL->query($string);
                     } else {
                            return false;
                     }
              }else{
                     return false;
              }
       }

       // lấy ra mảng các đối tượng
       function getListobj()
       {
              //array return
              $array_list = array();
              // cau lenh truy suat
              $string = 'SELECT * FROM accounts';

              // thuc hien truy suat
              $result = $this->actionSQL->query($string);


              // Kiểm tra số hàng được trả về
              if ($result->num_rows > 0) {
                     // Lấy dữ liệu và đưa vào mảng
                     while ($data = $result->fetch_assoc()) {
                            $userName = $data["userName"];
                            $passWord = $data["passWord"];
                            $dateCreated = $data["dateCreated"];
                            $accountStatus = $data["accountStatus"];
                            $name = $data["name"];
                            $address = $data["address"];
                            $email = $data["email"];
                            $phoneNumber = $data["phoneNumber"];
                            $birth = $data["birth"];
                            $sex = $data["sex"];
                            $codePermission = $data["codePermissions"];
                            $account = new AccountDTO($userName, $passWord, $dateCreated, $accountStatus, $name, $address, $email, $phoneNumber, $birth, $sex, $codePermission);
                            array_push($array_list, $account);
                     }
                     return $array_list;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     // echo "Không có dữ liệu được trả về từ truy vấn.";
                     return null;
              }
       }

       // lấy ra một đối tượng dựa theo mã đối tượng
       function getobj($code)
       {
              // cau lenh truy vấn
              $string = "SELECT * FROM accounts WHERE userName = '$code'";
              // thuc hien truy suat
              $result = $this->actionSQL->query($string);

              if ($result->num_rows > 0) {
                     $data = $result->fetch_assoc();
                     $userName = $data["userName"];
                     $passWord = $data["passWord"];
                     $dateCreated = $data["dateCreated"];
                     $accountStatus = $data["accountStatus"];
                     $name = $data["name"];
                     $address = $data["address"];
                     $email = $data["email"];
                     $phoneNumber = $data["phoneNumber"];
                     $birth = $data["birth"];
                     $sex = $data["sex"];
                     $codePermission = $data["codePermissions"];

                     $account = new AccountDTO($userName, $passWord, $dateCreated, $accountStatus, $name, $address, $email, $phoneNumber, $birth, $sex, $codePermission);

                     return $account;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     echo "Không có dữ liệu được trả về từ truy vấn.";
                     return null;
              }
       }

       // thêm một đối tượng 
       function addobj($obj)
       {
              $userName = $obj->getUsername();
              // kiểm tra xem có bị trùng thuọc tính khóa không
              $check = "SELECT * FROM accounts WHERE userName = '$userName'";
              $resultCheck = $this->actionSQL->query($check);

              if ($obj != null && $resultCheck->num_rows < 1) {
                     $passWord = $obj->getPassword();
                     $dateCreate = $obj->getDateCreate();
                     $accountStatus = $obj->getAccountStatus();
                     $name = $obj->getName();
                     $address = $obj->getAddress();
                     $email = $obj->getEmail();
                     $phoneNumber = $obj->getPhoneNumber();
                     $birth = $obj->getBirth();
                     $sex = $obj->getSex();
                     $codePermission = $obj->getCodePermission();

                     // cau lenh truy vấn
                     // INSERT IGNORE: nếu gặp trùng lắp khóa chính thì nó sẽ không thêm dữ liệu vào 
                     $string = "INSERT INTO accounts (userName, passWord, dateCreated, accountStatus, name, address, email, phoneNumber, birth, sex, codePermissions) VALUES ('$userName', '$passWord', '$dateCreate', '$accountStatus', '$name', '$address', '$email', '$phoneNumber', '$birth', '$sex', '$codePermission')";

                     return $this->actionSQL->query($string);
              } else {
                     return false;
              }
       }

       // sửa một đối tượng
       function upadateobj($obj)
       {
              if ($obj != null) {
                     $userName = $obj->getUsername();
                     $passWord = $obj->getPassword();
                     $dateCreate = $obj->getDateCreate();
                     $accountStatus = $obj->getAccountStatus();
                     $name = $obj->getName();
                     $address = $obj->getAddress();
                     $email = $obj->getEmail();
                     $phoneNumber = $obj->getPhoneNumber();
                     $birth = $obj->getBirth();
                     $sex = $obj->getSex();
                     $codePermission = $obj->getCodePermission();

                     // Câu lệnh UPDATE
                     $string = "UPDATE accounts 
                                SET passWord = '$passWord', 
                                    dateCreated = '$dateCreate', 
                                    accountStatus = '$accountStatus', 
                                    name = '$name', 
                                    address = '$address', 
                                    email = '$email', 
                                    phoneNumber = '$phoneNumber', 
                                    birth = '$birth', 
                                    sex = '$sex', 
                                    codePermissions = '$codePermission' 
                                WHERE userName = '$userName'";

                     return $this->actionSQL->query($string);
              } else {
                     return false;
              }
       }
}

// check

// $check = new AccountDAL();
// $data = $check->getListobj();

// print_r($data);
// foreach($data as $obj){
//        echo $obj . "<br>";
// }

// $dataobj = $check->getobj('PhucApuTruong');
// echo $dataobj;

// $account = new AccountDTO(
//        'username123',
//        'password111',
//        '2024-03-08',
//        'active',
//        'John Doe',
//        '123 Main St',
//        'john@example.com',
//        '0823072871',
//        '1990-01-01',
//        'Male',
//        'admin'
// );
// echo $check->addobj($account);
// echo $check->upadateobj($account);

// echo $check->deleteByID('JohnDoe');

// echo $check->delete($account);