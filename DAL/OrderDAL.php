<?php
// import
require('./AbstractionDAL.php');
require('../DTO/OrderDTO.php');

class OrderDAL extends AbstractionDAL
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
              // do bảng order có liên quan đến orderDetail
              // khi xóa một đơn hàng trong bảng order thì các chi tiết bên trong đơn hàng đó cũng biến mất bên bảng orderDeatail

              // xóa bên bảng chi tiet don hang trước
              $string1 = "DELETE FROM orderdetail WHERE orderCode = '$code'";

              // xoa ben bang order
              $string2 = "DELETE FROM orders WHERE orderCode = '$code'";

              $resutl1 = $this->actionSQL->query($string1);
              $resutl2 = $this->actionSQL->query($string2);

              return $resutl1 === $resutl2;
       }

       // xóa một đối tượng bằng cách truyền đối tượng vào
       function delete($obj)
       {
              if ($obj != null) {
                     $code = $obj->getOrderCode();
                     // do bảng order có liên quan đến orderDetail
                     // khi xóa một đơn hàng trong bảng order thì các chi tiết bên trong đơn hàng đó cũng biến mất bên bảng orderDeatail

                     // xóa bên bảng chi tiet don hang trước
                     $string1 = "DELETE FROM orderdetail WHERE orderCode = '$code'";

                     // xoa ben bang order
                     $string2 = "DELETE FROM orders WHERE orderCode = '$code'";

                     $resutl1 = $this->actionSQL->query($string1);
                     $resutl2 = $this->actionSQL->query($string2);

                     return $resutl1 === $resutl2;
              }
       }

       // lấy ra mảng các đối tượng
       function getListObj()
       {
              // Câu lệnh truy vấn
              $string = "SELECT * FROM orders";

              // Thực hiện truy suất
              $result = $this->actionSQL->query($string);

              $orders = array();

              if ($result->num_rows > 0) {
                     while ($data = $result->fetch_assoc()) {
                            $orderCode = $data["orderCode"];
                            $dateCreated = $data["dateCreated"];
                            $dateDelivery = $data["dateDelivery"];
                            $dateFinish = $data["dateFinish"];
                            $userName = $data["userName"];
                            $totalMoney = $data["totalMoney"];
                            $codePayments = $data["codePayments"];
                            $codeTransport = $data["codeTransport"];
                            $status = $data["status"];
                            $note = $data["note"];

                            $order = new OrderDTO($orderCode, $dateCreated, $dateDelivery, $dateFinish, $userName, $totalMoney, $codePayments, $codeTransport, $status, $note);
                            $orders[] = $order;
                     }
                     return $orders;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     return null;
              }
       }

       // lấy ra một đối tượng dựa theo mã đối tượng
       function getObj($code)
       {
              // Câu lệnh truy vấn
              $query = "SELECT * FROM orders WHERE orderCode = '$code'";

              // Thực hiện truy vấn
              $result = $this->actionSQL->query($query);

              // Kiểm tra số hàng được trả về
              if ($result->num_rows > 0) {
                     $data = $result->fetch_assoc();
                     $orderCode = $data["orderCode"];
                     $dateCreated = $data["dateCreated"];
                     $dateDelivery = $data["dateDelivery"];
                     $dateFinish = $data["dateFinish"];
                     $userName = $data["userName"];
                     $totalMoney = $data["totalMoney"];
                     $codePayments = $data["codePayments"];
                     $codeTransport = $data["codeTransport"];
                     $status = $data["status"];
                     $note = $data["note"];

                     // Tạo đối tượng OrderDTO và trả về
                     $order = new OrderDTO($orderCode, $dateCreated, $dateDelivery, $dateFinish, $userName, $totalMoney, $codePayments, $codeTransport, $status, $note);
                     return $order;
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
                     // Lấy các thuộc tính từ đối tượng
                     $orderCode = $obj->getOrderCode();

                     // Kiểm tra xem đơn hàng đã tồn tại trong cơ sở dữ liệu chưa
                     $checkQuery = "SELECT * FROM orders WHERE orderCode = '$orderCode'";
                     $resultCheck = $this->actionSQL->query($checkQuery);

                     // Nếu đối tượng không rỗng và đơn hàng chưa tồn tại
                     if ($resultCheck->num_rows < 1) {
                            // Lấy các thuộc tính khác từ đối tượng
                            $dateCreated = $obj->getDateCreated();
                            $dateDelivery = $obj->getDateDelivery();
                            $dateFinish = $obj->getDateFinish();
                            $userName = $obj->getUserName();
                            $totalMoney = $obj->getTotalMoney();
                            $codePayments = $obj->getCodePayments();
                            $codeTransport = $obj->getCodeTransport();
                            $status = $obj->getStatus();
                            $note = $obj->getNote();

                            // Câu lệnh truy vấn để thêm đối tượng vào bảng orders
                            $insertQuery = "INSERT INTO orders (orderCode, dateCreated, dateDelivery, dateFinish, userName, totalMoney, codePayments, codeTransport, status, note) 
                                         VALUES ('$orderCode', '$dateCreated', '$dateDelivery', '$dateFinish', '$userName', $totalMoney, '$codePayments', '$codeTransport', '$status', '$note')";

                            // Thực hiện truy vấn
                            return $this->actionSQL->query($insertQuery);
                     } else {
                            // Trả về false đơn hàng đã tồn tại
                            return false;
                     }
              } else {
                     // Trả về false nếu đối tượng rỗng
                     return false;
              }
       }

       // sửa một đối tượng
       function upadateObj($obj)
       {
              if ($obj != null) {
                     // Lấy các thuộc tính từ đối tượng
                     $orderCode = $obj->getOrderCode();
                     $dateCreated = $obj->getDateCreated();
                     $dateDelivery = $obj->getDateDelivery();
                     $dateFinish = $obj->getDateFinish();
                     $userName = $obj->getUserName();
                     $totalMoney = $obj->getTotalMoney();
                     $codePayments = $obj->getCodePayments();
                     $codeTransport = $obj->getCodeTransport();
                     $status = $obj->getStatus();
                     $note = $obj->getNote();

                     // Câu lệnh UPDATE
                     $query = "UPDATE orders 
                               SET dateCreated = '$dateCreated', 
                                   dateDelivery = '$dateDelivery', 
                                   dateFinish = '$dateFinish', 
                                   userName = '$userName', 
                                   totalMoney = $totalMoney, 
                                   codePayments = '$codePayments', 
                                   codeTransport = '$codeTransport', 
                                   status = '$status', 
                                   note = '$note' 
                               WHERE orderCode = '$orderCode'";

                     // Thực hiện truy vấn
                     return $this->actionSQL->query($query);
              } else {
                     // Trả về false nếu đối tượng rỗng
                     return false;
              }
       }
}

// check

// $check = new OrderDAL();
// $data = $check->getListobj();

// print_r($data);

// echo $check->getObj('ORD001')->getOrderCode();

// $order = new OrderDTO(
//        "ORD123",               // Mã đơn hàng
//        "2024-03-21",           // Ngày tạo đơn
//        "2024-03-28",           // Ngày giao hàng dự kiến
//        "2024-03-28",           // Ngày hoàn thành
//        "PhucApuTruong",             // Tên người dùng
//        250.50,                 // Tổng tiền
//        "PP001",               // Mã thanh toán
//        "EXP001",             // Mã vận chuyển
//        "Pending",              // Trạng thái
//        "Please deliver to the front."  // Ghi chú
// );
// echo $check->addobj($order);

// echo $check->upadateObj($order);

// echo $check->deleteByID('ORD123');
