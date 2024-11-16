<?php
// import
// require('../DAL/AbstractionDAL.php');
// require('../DTO/ShirtProductDTO.php');

class ShirtProductDAL extends AbstractionDAL
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
              // Do bảng orderDetail, ballotDetail có khóa ngọai tham chiếu đến thuộc tính khóa productCode của bảng product. Nên phải kiểm tra trước xem có khóa ngoại nào tham chiếu đến không, nếu không mới cho xóa.

              // xóa dữ liệu trong bảng shirtproduct, shirtsize trước rồi đến product. Tại do sản phẩm áo có size, nên khi ta xóa sản phẩm áo thì size liên quan đến áo đó cũng mất.

              $check_data_orderDetail = "SELECT * FROM orderdetail WHERE productCode = '$code'";


              $result_check1 = $this->actionSQL->query($check_data_orderDetail);


              if ($result_check1->num_rows < 1) {

                     // xóa dữ liệu trong bảng shirtsize
                     $string1 = "DELETE FROM shirtsize WHERE productCode = '$code'";

                     // xóa dữ liệu trong bảng shirtproduct trước
                     $string2 = "DELETE FROM shirtproduct WHERE productCode = '$code'";

                     // xóa dữ liệu trong bảng prodcut sau
                     $string3 = "DELETE FROM product WHERE productCode = '$code'";

                     $result1 = $this->actionSQL->query($string1);
                     $result2 = $this->actionSQL->query($string2);
                     $result3 = $this->actionSQL->query($string3);

                     return $result1 === $result2 && $result2 === $result3;
              } else {
                     return false;
              }
       }

       // xóa một đối tượng bằng cách truyền đối tượng vào
       function delete($obj)
       {
              if ($obj != null) {
                     $code = $obj->getProductCode();
                     // Do bảng orderDetail, ballotDetail có khóa ngọai tham chiếu đến thuộc tính khóa productCode của bảng product. Nên phải kiểm tra trước xem có khóa ngoại nào tham chiếu đến không, nếu không mới cho xóa.

                     // xóa dữ liệu trong bảng shirtproduct, shirtsize trước rồi đến product. Tại do sản phẩm áo có size, nên khi ta xóa sản phẩm áo thì size liên quan đến áo đó cũng mất.

                     $check_data_orderDetail = "SELECT * FROM orderdetail WHERE productCode = '$code'";


                     $result_check1 = $this->actionSQL->query($check_data_orderDetail);


                     if ($result_check1->num_rows < 1) {

                            // xóa dữ liệu trong bảng shirtsize
                            $string1 = "DELETE FROM shirtsize WHERE productCode = '$code'";

                            // xóa dữ liệu trong bảng shirtproduct trước
                            $string2 = "DELETE FROM shirtproduct WHERE productCode = '$code'";

                            // xóa dữ liệu trong bảng prodcut sau
                            $string3 = "DELETE FROM product WHERE productCode = '$code'";

                            $result1 = $this->actionSQL->query($string1);
                            $result2 = $this->actionSQL->query($string2);
                            $result3 = $this->actionSQL->query($string3);

                            return $result1 === $result2 && $result2 === $result3;
                     } else {
                            return false;
                     }
              }
       }

       // lấy ra mảng các đối tượng
       function getListObj()
       {
              // Khởi tạo mảng để lưu danh sách đối tượng
              $product_list = array();

              // Câu lệnh truy vấn
              $query = 'SELECT * FROM product INNER JOIN shirtproduct ON product.productCode = shirtproduct.productCode';

              // Thực hiện truy vấn
              $result = $this->actionSQL->query($query);

              // Kiểm tra số hàng được trả về
              if ($result->num_rows > 0) {
                     // Lặp qua từng hàng kết quả
                     while ($data = $result->fetch_assoc()) {
                            // Lấy dữ liệu từ hàng kết quả
                            $productCode = $data["productCode"];
                            $imgProduct = $data["imgProduct"];
                            $nameProduct = $data["nameProduct"];
                            $supplierCode = $data["supplierCode"];
                            $quantity = $data["quantity"];
                            $describeProduct = $data["describeProduct"];
                            $status = $data["status"];
                            $color = $data["color"];
                            $targetGender = $data["targetGender"];
                            $price = $data["price"];
                            $promotion = $data["promotion"];

                            $shirtMaterial = $data["shirtMaterial"];
                            $shirtStyle = $data["shirtStyle"];
                            $descriptionMaterial = $data["descriptionMaterial"];

                            // Tạo đối tượng HandbagProductDTO từ dữ liệu lấy được và thêm vào mảng
                            $shirtProduct = new ShirtProductDTO($productCode, $imgProduct, $nameProduct, $supplierCode, $quantity, $describeProduct, $status, $color, $targetGender, $price, $promotion, $shirtMaterial, $shirtStyle, $descriptionMaterial);
                            array_push($product_list, $shirtProduct);
                     }
                     // Trả về danh sách đối tượng
                     return $product_list;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     return null;
              }
       }

       // lấy ra một đối tượng dựa theo mã đối tượng
       function getObj($code)
       {
              // Câu lệnh truy vấn
              $query = "SELECT * FROM product,shirtproduct WHERE product.productCode = shirtproduct.productCode AND product.productCode = '$code' ";

              // Thực hiện truy vấn
              $result = $this->actionSQL->query($query);

              // Kiểm tra số hàng được trả về
              if ($result->num_rows > 0) {
                     
                     // Lấy dữ liệu từ hàng kết quả
                     $data = $result->fetch_assoc();
                     $productCode = $data["productCode"];
                     $imgProduct = $data["imgProduct"];
                     $nameProduct = $data["nameProduct"];
                     $supplierCode = $data["supplierCode"];
                     $quantity = $data["quantity"];
                     $describeProduct = $data["describeProduct"];
                     $status = $data["status"];
                     $color = $data["color"];
                     $targetGender = $data["targetGender"];
                     $price = $data["price"];
                     $promotion = $data["promotion"];
                     $shirtMaterial = $data["shirtMaterial"];
                     $shirtStyle = $data["shirtStyle"];
                     $descriptionMaterial = $data["descriptionMaterial"];

                     // Tạo đối tượng HandbagProductDTO và trả về
                     $shirtProduct = new ShirtProductDTO($productCode, $imgProduct, $nameProduct, $supplierCode, $quantity, $describeProduct, $status, $color, $targetGender, $price, $promotion, $shirtMaterial, $shirtStyle, $descriptionMaterial);
                     return $shirtProduct;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     return null;
              }
       }

       // thêm một đối tượng 
       function addObj($obj)
       {
              // thêm dữ liệu vào bảng product trước rồi mới đến shirtproduct
              // Câu lệnh truy vấn kiểm tra khóa
              if ($obj != null) {
                     $productCode = $obj->getProductCode();
                     $query = "SELECT * FROM product,shirtproduct WHERE product.productCode = shirtproduct.productCode AND product.productCode = '$productCode' ";
                     $resultCheck = $this->actionSQL->query($query);
                     if ($resultCheck->num_rows < 1) {
                            $imgProduct = $obj->getImgProduct();
                            $nameProduct = $obj->getNameProduct();
                            $supplierCode = $obj->getSupplierCode();
                            $quantity = $obj->getQuantity();
                            $describeProduct = $obj->getDescribe();
                            $status = $obj->getStatus();
                            $color = $obj->getColor();
                            $targetGender = $obj->getTargetGender();
                            $price = $obj->getPrice();
                            $promotion = $obj->getPromotion();
                            $shirtMaterial = $obj->getShirtMaterial();
                            $shirtStyle = $obj->getShirtStyle();
                            $descriptionMaterial = $obj->getDescriptionMaterial();

                            $string1 = "INSERT INTO Product (productCode, imgProduct, nameProduct, supplierCode, quantity, describeProduct, status, color, targetGender, price, promotion)
                            VALUES ('$productCode', '$imgProduct', '$nameProduct', '$supplierCode', $quantity, '$describeProduct', '$status', '$color', '$targetGender', $price, $promotion)";

                            $string2 = "INSERT INTO shirtproduct (productCode,shirtMaterial,shirtStyle,descriptionMaterial)
                            VALUES 
                                   ('$productCode','$shirtMaterial','$shirtStyle','$descriptionMaterial')";

                            $result1 = $this->actionSQL->query($string1);
                            $result2 = $this->actionSQL->query($string2);

                            return $result1 === $result2;
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
                     // Lấy thông tin từ đối tượng
                     $productCode = $obj->getProductCode();
                     $imgProduct = $obj->getImgProduct();
                     $nameProduct = $obj->getNameProduct();
                     $supplierCode = $obj->getSupplierCode();
                     $quantity = $obj->getQuantity();
                     $describeProduct = $obj->getDescribe();
                     $status = $obj->getStatus();
                     $color = $obj->getColor();
                     $targetGender = $obj->getTargetGender();
                     $price = $obj->getPrice();
                     $promotion = $obj->getPromotion();
                     $shirtMaterial = $obj->getShirtMaterial();
                     $shirtStyle = $obj->getShirtStyle();
                     $descriptionMaterial = $obj->getDescriptionMaterial();

                     // Câu lệnh SQL để cập nhật dữ liệu trong bảng Product
                     $queryProduct = "UPDATE Product 
                         SET imgProduct = ?, 
                             nameProduct = ?, 
                             supplierCode = ?, 
                             quantity = ?, 
                             describeProduct = ?, 
                             status = ?, 
                             color = ?, 
                             targetGender = ?, 
                             price = ?, 
                             promotion = ?
                         WHERE productCode = ?";

                     // Chuẩn bị câu lệnh SQL
                     $stmtProduct = $this->actionSQL->prepare($queryProduct);
                     // Bind các tham số
                     $stmtProduct->bind_param("sssissssdds", $imgProduct, $nameProduct, $supplierCode, $quantity, $describeProduct, $status, $color, $targetGender, $price, $promotion, $productCode);
                     // Thực thi câu lệnh
                     $resultProduct = $stmtProduct->execute();
                     // Đóng câu lệnh
                     $stmtProduct->close();

                     // Câu lệnh SQL để cập nhật dữ liệu trong bảng ShirtProduct
                     $queryShirtProduct = "UPDATE ShirtProduct 
                              SET shirtMaterial = ?, 
                                  shirtStyle = ?,
                                  descriptionMaterial = ?
                              WHERE productCode = ?";

                     // Chuẩn bị câu lệnh SQL
                     $stmtShirtProduct = $this->actionSQL->prepare($queryShirtProduct);
                     // Bind các tham số
                     $stmtShirtProduct->bind_param("ssss", $shirtMaterial, $shirtStyle, $descriptionMaterial, $productCode);
                     // Thực thi câu lệnh
                     $resultShirtProduct = $stmtShirtProduct->execute();
                     // Đóng câu lệnh
                     $stmtShirtProduct->close();

                     // Kiểm tra và trả về kết quả của cả hai câu lệnh UPDATE
                     return ($resultProduct && $resultShirtProduct);
              } else {
                     // Nếu đối tượng truyền vào là null
                     return false;
              }
       }

       // function upadateObj($obj)
       // {
       //        if ($obj != null) {
       //               $productCode = $obj->getProductCode();
       //               $imgProduct = $obj->getImgProduct();
       //               $nameProduct = $obj->getNameProduct();
       //               $supplierCode = $obj->getSupplierCode();
       //               $quantity = $obj->getQuantity();
       //               $describeProduct = $obj->getDescribe();
       //               $status = $obj->getStatus();
       //               $color = $obj->getColor();
       //               $targetGender = $obj->getTargetGender();
       //               $price = $obj->getPrice();
       //               $promotion = $obj->getPromotion();
       //               $shirtMaterial = $obj->getShirtMaterial();
       //               $shirtStyle = $obj->getShirtStyle();
       //               $descriptionMaterial = $obj->getDescriptionMaterial();

       //               // Câu lệnh SQL để cập nhật dữ liệu trong bảng Product nếu mã sản phẩm đã tồn tại
       //               $queryProduct = "UPDATE Product 
       //                                SET imgProduct = '$imgProduct', 
       //                                    nameProduct = '$nameProduct', 
       //                                    supplierCode = '$supplierCode', 
       //                                    quantity = $quantity, 
       //                                    describeProduct = '$describeProduct', 
       //                                    status = '$status', 
       //                                    color = '$color', 
       //                                    targetGender = '$targetGender', 
       //                                    price = $price, 
       //                                    promotion = $promotion
       //                                WHERE productCode = '$productCode'";

       //               // Thực hiện truy vấn
       //               $resultProduct = $this->actionSQL->query($queryProduct);

       //               // Câu lệnh SQL để cập nhật dữ liệu trong bảng HandbagProduct nếu mã sản phẩm đã tồn tại
       //               $queryHandbagProduct = "UPDATE shirtproduct 
       //                                       SET shirtMaterial = '$shirtMaterial',shirtStyle = '$shirtStyle',descriptionMaterial = '$descriptionMaterial'
       //                                       WHERE productCode = '$productCode'";

       //               // Thực hiện truy vấn
       //               $resultHandbagProduct = $this->actionSQL->query($queryHandbagProduct);

       //               // Kiểm tra và trả về kết quả của cả hai câu lệnh UPDATE
       //               return ($resultProduct !== false && $resultHandbagProduct !== false);
       //        } else {
       //               // Nếu đối tượng truyền vào là null
       //               return false;
       //        }
       // }

       // lay data product
       function getProductDATA()
       {
              // Khởi tạo mảng để lưu danh sách đối tượng
              $product_list = array();

              // Câu lệnh truy vấn
              $query = 'SELECT * FROM product INNER JOIN shirtproduct ON product.productCode = shirtproduct.productCode';

              // Thực hiện truy vấn
              $result = $this->actionSQL->query($query);

              // Kiểm tra số hàng được trả về
              if ($result->num_rows > 0) {
                     // Lặp qua từng hàng kết quả
                     while ($data = $result->fetch_assoc()) {
                            // Lấy dữ liệu từ hàng kết quả
                            $productCode = $data["productCode"];
                            $imgProduct = $data["imgProduct"];
                            $nameProduct = $data["nameProduct"];
                            $supplierCode = $data["supplierCode"];
                            $quantity = $data["quantity"];
                            $describeProduct = $data["describeProduct"];
                            $status = $data["status"];
                            $color = $data["color"];
                            $targetGender = $data["targetGender"];
                            $price = $data["price"];
                            $promotion = $data["promotion"];

                            // $shirtMaterial = $data["shirtMaterial"];
                            // $shirtStyle = $data["shirtStyle"];
                            // $descriptionMaterial = $data["descriptionMaterial"];

                            // Tạo đối tượng HandbagProductDTO từ dữ liệu lấy được và thêm vào mảng
                            $Product = new ProductDTO($productCode, $imgProduct, $nameProduct, $supplierCode, $quantity, $describeProduct, $status, $color, $targetGender, $price, $promotion);
                            array_push($product_list, $Product);
                     }
                     // Trả về danh sách đối tượng
                     return $product_list;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     return null;
              }
       }

       function getListTargetGenderShirtProducts($sex)
       {
              // Khởi tạo mảng để lưu danh sách sản phẩm áo cho nam
              $male_shirt_products = array();

              // Câu lệnh truy vấn
              $query = "SELECT * FROM product 
              INNER JOIN shirtproduct ON product.productCode = shirtproduct.productCode
              WHERE targetGender = $sex";

              // Thực hiện truy vấn
              $result = $this->actionSQL->query($query);

              // Kiểm tra số hàng được trả về
              if ($result->num_rows > 0) {
                     // Lặp qua từng hàng kết quả
                     while ($data = $result->fetch_assoc()) {
                            // Lấy dữ liệu từ hàng kết quả
                            $productCode = $data["productCode"];
                            $imgProduct = $data["imgProduct"];
                            $nameProduct = $data["nameProduct"];
                            $supplierCode = $data["supplierCode"];
                            $quantity = $data["quantity"];
                            $describeProduct = $data["describeProduct"];
                            $status = $data["status"];
                            $color = $data["color"];
                            $targetGender = $data["targetGender"];
                            $price = $data["price"];
                            $promotion = $data["promotion"];
                            $shirtMaterial = $data["shirtMaterial"];
                            $shirtStyle = $data["shirtStyle"];
                            $descriptionMaterial = $data["descriptionMaterial"];

                            // Tạo đối tượng ShirtProductDTO từ dữ liệu lấy được và thêm vào mảng
                            $shirtProduct = new ShirtProductDTO($productCode, $imgProduct, $nameProduct, $supplierCode, $quantity, $describeProduct, $status, $color, $targetGender, $price, $promotion, $shirtMaterial, $shirtStyle, $descriptionMaterial);
                            array_push($male_shirt_products, $shirtProduct);
                     }
                     // Trả về danh sách sản phẩm áo cho nam
                     return $male_shirt_products;
              } else {
                     // Trường hợp không có dữ liệu trả về
                     return null;
              }
       }
}


// check

// $check = new ShirtProductDAL();

// print_r($check->getListObj());

// echo $check->getObj('P001')->getNameProduct();

// $product = new ShirtProductDTO(
//        'P111', // productCode
//        'image_url', // imgProduct
//        'Product Name', // nameProduct
//        'NCC001', // supplierCode
//        50, // quantity
//        'Description of the product', // describeProduct
//        'Available', // status
//        'Blue', // color
//        'Male', // targetGender
//        50.99, // price
//        10, // promotion
//        'test',
//        'null' // bagMaterial
// );

// echo $check->addObj($product);

// echo $check->upadateObj($product);

// echo $check->delete($product);