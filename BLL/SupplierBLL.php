<?php

require('../DAL/connectionDatabase.php');
require('../DAL/AbstractionDAL.php');
require('../DTO/SupplierDTO.php');
require('../DAL/SupplierDAL.php');

class SupplierBLL
{
    private $SupplierDAL;
    function __construct()
    {
        $this->SupplierDAL = new SupplierDAL();
    }
    // Xóa một đối tượng khi lấy được id của đối tượng
    function deleteByID($code)
    {

        //lấy thông tin 
        $result = $this->SupplierDAL->deleteByID($code);

        if ($result) {
            return array("mess" => "success");
        } else {
            return array("mess" => "Failed");
        }
    }

    //Lấy danh sách 
    function getList()
    {
        //mảng dữ liệu lấy được từ DAL
        $arr = $this->SupplierDAL->getListObj();
        //mảng chứa các obj lấy được từ DAL ở trên
        $result = array();
        if (count($arr) > 0) {
            foreach ($arr as $item) {
                $codeSupplier = $item->getCodeSupplier();
                $nameSupplier = $item->getNameSupplier();
                $address = $item->getAddress();
                $email = $item->getEmail();
                $brandSupplier = $item->getBrandSupplier();
                $phoneNumber = $item->getPhoneNumber();
                $obj = array(
                    "codeSupplier" => $codeSupplier,
                    "nameSupplier" => $nameSupplier,
                    "address" => $address,
                    "email" =>  $email,
                    "brandSupplier" =>  $brandSupplier,
                    "phoneNumber" => $phoneNumber,
                    "mess" => "success"
                );
                array_push($result, $obj);
            }
            return $result;
        } else {
            return array("mess" => "Failed");
        }
    }

    //Lấy một đối tượng bằng khóa chính của đối tượng đó
    function getObj($code)
    {
        //Lấy kết quả từ DAL
        $result = $this->SupplierDAL->getObj($code);
        if ($result != null) {
            $codeSupplier = $result->getCodeSupplier();
            $nameSupplier = $result->getNameSupplier();
            $address = $result->getAddress();
            $email = $result->getEmail();
            $brandSupplier = $result->getBrandSupplier();
            $phoneNumber = $result->getPhoneNumber();
            $obj = array(
                "codeSupplier" => $codeSupplier,
                "nameSupplier" => $nameSupplier,
                "address" => $address,
                "email" => $email,
                "brandSupplie" => $brandSupplier,
                "phoneNumber" => $phoneNumber,
                "mess" => "success"
            );
            return $obj;
        } else {
            return array("mess" => "Failed");
        }
    }

    //Thêm vào đối tượng obj 
    function addObj($obj)
    {
        if ($obj != null) {
            //Lấy thông tin từ đối tượng 
            $codeSupplier = $obj->getCodeSupplier();
            $nameSupplier = $obj->getNameSupplier();
            $address = $obj->getAddress();
            $email = $obj->getEmail();
            $brandSupplier = $obj->getBrandSupplier();
            $phoneNumber = $obj->getPhoneNumber();
            //Thực hiện thêm đối tượng vào Database
            $result = $this->SupplierDAL->addObj($obj);

            if ($result) {
                return array(
                    "codeSupplier" => $codeSupplier,
                    "nameSupplier" => $nameSupplier,
                    "address" => $address,
                    "email" => $email,
                    "brandSupplier" => $brandSupplier,
                    "phoneNumber" => $phoneNumber,
                    "mess" => "success"
                );
            } else {
                return array("mess" => "failed");
            }
        } else {
            return array("mess" => "failed");
        }
    }


    //Cập nhật đối tượng 
    function updateObj($obj)
    {
        $result = $this->SupplierDAL->upadateObj($obj);
        if ($result) {
            // Lấy thông tin từ đối tượng cập nhật
            $codeSupplier = $obj->getCodeSupplier();
            $nameSupplier = $obj->getNameSupplier();
            $address = $obj->getAddress();
            $email = $obj->getEmail();
            $brandSupplier = $obj->getBrandSupplier();
            $phoneNumber = $obj->getPhoneNumber();

            // Trả về mảng chứa thông báo thành công và thông tin cập nhật
            return array(
                "mess" => "success",
                "codeSupplier" => $codeSupplier,
                "nameSupplier" => $nameSupplier,
                "address" => $address,
                "email" => $email,
                "brandSupplier" => $brandSupplier,
                "phoneNumber" => $phoneNumber
            );
        } else {
            return array("mess" => "Failed");
        }
    }


    // Tìm kiếm
    function searchSupplier($str)
{
    // Gọi hàm getListObj($str) trong DAL để lấy danh sách nhà cung cấp thỏa mãn điều kiện tìm kiếm
    $arr = $this->SupplierDAL->getListResultSearch($str);
    
    
    $result = array();

    // Kiểm tra nếu DAL trả về null
    if ($arr === null || count($arr) === 0) {
        // Trả về thông báo lỗi hoặc trạng thái không có dữ liệu
        return array("mess" => "No data found");
    }

    if (count($arr) > 0) {
        foreach ($arr as $item) {
            $codeSupplier = $item->getCodeSupplier();
            $nameSupplier = $item->getNameSupplier();
            $address = $item->getAddress();
            $email = $item->getEmail();
            $brandSupplier = $item->getBrandSupplier();
            $phoneNumber = $item->getPhoneNumber();

            // Tạo đối tượng kết quả
            $obj = array(
                "codeSupplier" => $codeSupplier,
                "nameSupplier" => $nameSupplier,
                "address" => $address,
                "email" => $email,
                "brandSupplier" => $brandSupplier,
                "phoneNumber" => $phoneNumber,
                "mess" => "success",
            );
            array_push($result, $obj);
        }
    }
    return $result;
}


}


header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check = new SupplierBLL();

    $function = $_POST['function'];
    // checkLogin
    // menu
    switch ($function) {
        case 'getList':
            $temp = $check->getList();
            echo json_encode($temp);
            break;
        case 'getObj':
            $codeSupplier = $_POST['codeSupplier'];
            $temp = $check->getObj($codeSupplier);
            echo json_encode($temp);
            break;
        case 'deleteByID':
            $codeSupplier = $_POST['codeSupplier'];
            $temp = $check->deleteByID($codeSupplier);
            echo json_encode($temp);
            break;
        case 'addObj':
            // Lấy dữ liệu đối tượng từ POST request
            $codeSupplier = $_POST['codeSupplier'];
            $nameSupplier = $_POST['nameSupplier'];
            $address = $_POST['address'];
            $email = $_POST['email'];
            $brandSupplier = $_POST['brandSupplier'];
            $phoneNumber = $_POST['phoneNumber'];
            // Tạo một đối tượng PaymentDTO từ dữ liệu POST
            $obj = new SupplierDTO($codeSupplier, $nameSupplier, $address, $email, $brandSupplier, $phoneNumber);
            $temp = $check->addObj($obj);
            echo json_encode($temp);
            break;
        case 'updateObj':
            // Lấy dữ liệu đối tượng từ POST request
            $codeSupplier = $_POST['codeSupplier'];
            $nameSupplier = $_POST['nameSupplier'];
            $address = $_POST['address'];
            $email = $_POST['email'];
            $brandSupplier = $_POST['brandSupplier'];
            $phoneNumber = $_POST['phoneNumber'];
            // Tạo một đối tượng PaymentDTO từ dữ liệu POST
            $obj = new SupplierDTO($codeSupplier, $nameSupplier, $address, $email, $brandSupplier, $phoneNumber);
            $temp = $check->updateObj($obj);
            echo json_encode($temp);
            break;
        case 'searchSupplier':
            // Lấy dữ liệu đối tượng từ POST request
            $str = $_POST['str'];
            $temp = $check->searchSupplier($str);
            echo json_encode($temp);
            break;
    }
}
