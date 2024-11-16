<?php

// // import
// require('../DAL/AbstractionDAL.php');
// require('../DTO/SupplierDTO.php');


class SupplierDAL extends AbstractionDAL
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
              // do bảng enetrBallot có tham chiếu khóa ngoại đến thuộc tính khóa codeSupplier của bảng Supplier
              // kiểm tra nếu có khóa ngoại tham chiếu đến thì không được xóa

              $check_data_Supplier = "SELECT * FROM product WHERE supplierCode = '$code'";

              $resutl_1 = $this->actionSQL->query($check_data_Supplier);

              if ($resutl_1->num_rows < 1) {
                     $string = "DELETE FROM supplier WHERE codeSupplier = '$code'";

                     return $this->actionSQL->query($string);
              } else {
                     return false;
              }
       }

       // xóa một đối tượng bằng cách truyền đối tượng vào
       function delete($obj)
       {
              if ($obj != null) {
                     $code = $obj->getCodeSupplier();
                     // do bảng enetrBallot có tham chiếu khóa ngoại đến thuộc tính khóa codeSupplier của bảng Supplier
                     // kiểm tra nếu có khóa ngoại tham chiếu đến thì không được xóa

                     $check_data_Supplier = "SELECT * FROM product WHERE supplierCode = '$code'";

                     $resutl_1 = $this->actionSQL->query($check_data_Supplier);

                     if ($resutl_1->num_rows < 1) {
                            $string = "DELETE FROM supplier WHERE codeSupplier = '$code'";

                            return $this->actionSQL->query($string);
                     } else {
                            return false;
                     }
              } else {
                     return null;
              }
       }

       // lấy ra mảng các đối tượng
       function getListObj()
       {
              // Mảng để lưu trữ các đối tượng
              $array_list = array();

              // Câu lệnh truy vấn
              $string = 'SELECT * FROM Supplier';

              // Thực hiện truy vấn
              $result = $this->actionSQL->query($string);

              // Kiểm tra số hàng được trả về
              if ($result->num_rows > 0) {
                     // Lặp qua các dòng kết quả và thêm vào mảng
                     while ($data = $result->fetch_assoc()) {
                            $codeSupplier = $data['codeSupplier'];
                            $nameSupplier = $data['nameSupplier'];
                            $address = $data['address'];
                            $email = $data['email'];
                            $brandSupplier = $data['brandSupplier'];
                            $phoneNumber = $data['phoneNumber'];

                            // Tạo đối tượng SupplierDTO và thêm vào mảng
                            $supplier = new SupplierDTO($codeSupplier, $nameSupplier, $address, $email, $brandSupplier, $phoneNumber);
                            array_push($array_list, $supplier);
                     }
                     return $array_list;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     // echo "Không có dữ liệu được trả về từ truy vấn.";
                     return null;
              }
       }

       function getListResultSearch($str)
       {
              // Mảng để lưu trữ các đối tượng
              $array_list = array();

              

              // Sử dụng câu truy vấn có điều kiện tìm kiếm dựa vào $str
              $string = "SELECT * FROM Supplier 
                     WHERE  codeSupplier LIKE '%$str%'
              ";

              //  BẢO MẬT CÁCH 1
              // $stmt = $this->actionSQL->prepare("SELECT * FROM Supplier WHERE codeSupplier LIKE ?");
              // $searchTerm = "%$str%";
              // $stmt->bind_param("s", $searchTerm);
              // $stmt->execute();
              // $result = $stmt->get_result();


              // BẢO MẬT CÁCH 2
              // $str = mysqli_real_escape_string($this->actionSQL, $str);

              // $string = "SELECT * FROM Supplier
              //        WHERE  codeSupplier LIKE '%$str%'
              // ";


              // Thực hiện truy vấn
              $result = $this->actionSQL->query($string);

              // Kiểm tra số hàng được trả về
              if ($result->num_rows > 0) {
                     // Lặp qua các dòng kết quả và thêm vào mảng
                     while ($data = $result->fetch_assoc()) {
                            $codeSupplier = $data['codeSupplier'];
                            $nameSupplier = $data['nameSupplier'];
                            $address = $data['address'];
                            $email = $data['email'];
                            $brandSupplier = $data['brandSupplier'];
                            $phoneNumber = $data['phoneNumber'];

                            // Tạo đối tượng SupplierDTO và thêm vào mảng
                            $supplier = new SupplierDTO(
                                   $codeSupplier,
                                   $nameSupplier,
                                   $address,
                                   $email,
                                   $brandSupplier,
                                   $phoneNumber
                            );
                            array_push($array_list, $supplier);
                     }
                     return $array_list;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     return null;
              }
       }



       // lấy ra một đối tượng dựa theo mã đối tượng
       function getObj($code)
       {
              // Câu lệnh truy vấn
              $string = "SELECT * FROM Supplier WHERE codeSupplier = '$code'";

              // Thực hiện truy vấn
              $result = $this->actionSQL->query($string);

              if ($result->num_rows > 0) {
                     $data = $result->fetch_assoc();
                     $codeSupplier = $data['codeSupplier'];
                     $nameSupplier = $data['nameSupplier'];
                     $address = $data['address'];
                     $email = $data['email'];
                     $brandSupplier = $data['brandSupplier'];
                     $phoneNumber = $data['phoneNumber'];

                     // Tạo đối tượng SupplierDTO và trả về
                     $supplier = new SupplierDTO($codeSupplier, $nameSupplier, $address, $email, $brandSupplier, $phoneNumber);
                     return $supplier;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     // echo "Không có dữ liệu được trả về từ truy vấn.";
                     return null;
              }
       }

       // thêm một đối tượng 
       function addObj($obj)
       {
              if ($obj != null) {
                     $codeSupplier = $obj->getCodeSupplier();
                     // Kiểm tra xem có bị trùng thuộc tính khóa không
                     $check = "SELECT * FROM Supplier WHERE codeSupplier = '$codeSupplier'";
                     $resultCheck = $this->actionSQL->query($check);

                     if ($resultCheck->num_rows < 1) {
                            $nameSupplier = $obj->getNameSupplier();
                            $address = $obj->getAddress();
                            $email = $obj->getEmail();
                            $brandSupplier = $obj->getBrandSupplier();
                            $phoneNumber = $obj->getPhoneNumber();

                            // Câu lệnh truy vấn
                            $string = "INSERT INTO Supplier (codeSupplier, nameSupplier, address, email, brandSupplier, phoneNumber) VALUES ('$codeSupplier', '$nameSupplier', '$address', '$email', '$brandSupplier', '$phoneNumber')";

                            return $this->actionSQL->query($string);
                     } else {
                            return false;
                     }
              } else {
                     return false;
              }
       }

       // sửa một đối tượng
       function upadateObj($obj)
       {
              if ($obj != null) {
                     $codeSupplier = $obj->getCodeSupplier();
                     $nameSupplier = $obj->getNameSupplier();
                     $address = $obj->getAddress();
                     $email = $obj->getEmail();
                     $brandSupplier = $obj->getBrandSupplier();
                     $phoneNumber = $obj->getPhoneNumber();

                     // Câu lệnh UPDATE
                     $string = "UPDATE Supplier 
                                 SET nameSupplier = '$nameSupplier', 
                                     address = '$address', 
                                     email = '$email', 
                                     brandSupplier = '$brandSupplier', 
                                     phoneNumber = '$phoneNumber' 
                                 WHERE codeSupplier = '$codeSupplier'";

                     return $this->actionSQL->query($string);
              } else {
                     return false;
              }
       }
}

// check

// $check = new SupplierDAL();

// print_r($check->getListObj());

// echo $check->getObj('NCC001')->getCodeSupplier();

// echo $check->addObj(new SupplierDTO('test','','','','',''));

// echo $check->upadateObj(new SupplierDTO('test','hi','','','',''));

// echo $check->delete(new SupplierDTO('NCC001','hi','','','',''));
