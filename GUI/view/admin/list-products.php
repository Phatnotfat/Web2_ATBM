<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "website_sells_clothes_and_bags";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy danh sách sản phẩm từ bảng 'product'
$sqlpd = "SELECT productCode, nameProduct, SUBSTRING_INDEX(imgProduct, ' ', 1) AS imgProduct, quantity, price, status FROM product";
$result = mysqli_query($conn, $sqlpd);

// Tạo danh sách sản phẩm cho bảng
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Đóng kết nối
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>

    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/admin/product.css">

    <style>
        <?php
        require('../../css/admin/product.css');
        require('../../css/admin/sidebar.css');
        require('../../css/admin/header_admin.css');
        require('../../css/admin/footer_admin.css');
        ?>
    </style>
</head>
<body>
    <div class="container-sb">
        <div class="side-bar"><?php require('./sidebar.php'); ?></div>
        <div class="content">
            <div class="header">
            <?php require('./header_admin.php'); ?>
            </div>  
            <div class="content-page-sb ">
                <div class="container-product">
                    <div class="top-container mt-2">
                        <h2>Danh sách sản phẩm</h2>
                        <a href="./addproduct.php" class="btn btn-primary">Thêm sản phẩm</a>
                    </div>
                    <div class="mb-3 mt-5 ">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Nhập mã sản phẩm">
                            <select class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="In Stock">Còn hàng</option>
                            <option value="Out Of Stock">Hết hàng</option>
                            </select>
                            <select class="form-select">
                            <option value="">Tất cả danh mục</option>
                            <option value="Clothes">Quần áo</option>
                            <option value="Bag">Túi xách</option>
                            </select>
                            <select class="form-select">
                            <option value="">Tất cả nhà cung cấp</option>
                            <option value="Nike">NCC001</option>
                            <option value="Adidas">NCC002</option>
                            </select>
                            <button class="btn btn-primary">Tìm kiếm <i class="fa fa-search" style="font-size: 15px;"></i></button>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                            <th class="table-cell">Tên sản phẩm</th>
                            <th>Mã sản phẩm</th>
                            <th>Ảnh</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                            <td>Nike T-Shirt</td>
                            <td>001</td>
                            <td><img src="./logo-sgu.png" width="50px"></td>
                            <td>10</td>
                            <td>20$</td>
                            <td>Clothes</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i></a>
                                <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa fa-eye"></i></a>
                            </td>
                            </tr> -->
                            <?php foreach ($products as $product): ?>
                                <tr>
                                <td class="table-cell"><?php echo $product['nameProduct']; ?></td>
                                <td><?php echo $product['productCode']; ?></td>
                                <td style="width:120px;"><img src="<?php echo "../". $product['imgProduct']; ?>" alt="<?php echo $product['nameProduct']; ?>" width="100"></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td><?php echo $product['price']; ?></td>
                                <td><?php echo $product['status']; ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i></a>
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa fa-eye"></i></a>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal sửa sản phẩm -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Sửa sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm">
                                <div class="mb-3">
                                    <label for="productCode" class="form-label">Mã sản phẩm</label>
                                    <input type="text" class="form-control" id="productCode" name="productCode" disabled placeholder="P001">
                                </div>
                                <div class="mb-3">
                                    <label for="nameProduct" class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="nameProduct" name="nameProduct">
                                </div>
                                <div class="mb-3">
                                    <label for="inputFile" class="form-label">Ảnh(PNG,JPG)</label>
                                    <input type="file" class="form-control" id="inputFile" name="imgProduct" accept="image/jpeg, image/png" multiple>
                                    <div id="imagePreview" style="padding-top:2px;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Số lượng</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity">
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" class="form-control" id="price" name="price">
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Danh mục</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Chọn danh mục</option>
                                        <option value="Clothes">Quần áo</option>
                                        <option value="Bag">Túi xách</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                <label for="supplierCode" class="form-label">Nhà cung cấp</label>
                                <select class="form-select" id="supplierCode" name="supplierCode">
                                    <option value="">Chọn nhà cung cấp</option>
                                    <option value="NCC001">Tida</option>
                                    <option value="NCC002">Asura</option>
                                </select>
                                </div>
                                    <div class="mb-3">
                                    <label for="describe" class="form-label">Mô tả sản phẩm</label>
                                    <textarea class="form-control" id="describe" name="describe"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="targetGender" class="form-label">Đối tượng sử dụng</label>
                                    <select class="form-select" id="targetGender" name="targetGender">
                                    <option value="">Select Object</option>
                                    <option value="male">Nam</option>
                                    <option value="female">Nữ</option>
                                    <option value="both">Tất cả</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Chọn trạng thái</option>
                                        <option value="in">Còn hàng</option>
                                        <option value="out">Hết hàng</option>
                                    </select>
                                </div>

                                <!-- Field riêng quần áo -->
                                <div class="mb-3">
                                    <label for="shirtStyle" class="form-label">Phong cách</label>
                                    <input type="text" class="form-control" id="shirtStyle" name="shirtStyle">
                                </div>
                                <div class="mb-3">
                                    <label for="shirtMaterial" class="form-label">Chất liệu</label>
                                    <input type="text" class="form-control" id="shirtMaterial" name="shirtMaterial">
                                </div>
                                <div class="mb-3">
                                    <label for="descriptionMaterial" class="form-label">Mô tả</label>
                                    <input type="text" class="form-control" id="descriptionMaterial" name="descriptionMaterial">
                                </div>
                                <div class="mb-3">
                                        <div class="col-md-6">
                                            Số lượng
                                            <div class="form-check">
                                                <label for="sizeXL" class="form-check-label">S</label>
                                                <input type="number" id="inputSizeXL" class="form-control" value="" placeholder="Nhập số lượng">
                                            </div>
                                            <div class="form-check">
                                                <label for="sizeXL" class="form-check-label">M</label>
                                                <input type="number" id="inputSizeXL" class="form-control" value="" placeholder="Nhập số lượng">
                                            </div>
                                            <div class="form-check">
                                                <label for="sizeXL" class="form-check-label">L</label>
                                                <input type="number" id="inputSizeXL" class="form-control" value="" placeholder="Nhập số lượng">
                                            </div>
                                            <div class="form-check">
                                                <label for="sizeXL" class="form-check-label">XL</label>
                                                <input type="number" id="inputSizeXL" class="form-control" value="" placeholder="Nhập số lượng">
                                            </div>
                                        </div>
                                    </div>

                                <!-- Field riêng túi xách -->

                                <div style="text-align:right;">
                                    <button type="submit" class="btn btn-primary">Sửa sản phẩm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>

                <!--Modal xóa sản phẩm  -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Xóa sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn xóa sản phẩm này?
                            <br>
                            Mã sản phẩm: P...
                            <br>
                            Tên sản phẩm: ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-danger btn-confirm-delete">Xóa</button>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Modal xem chi tiết sản phẩm -->
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Chi tiết sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="../../image/product/product1/product-detail-1.png" id="imgProduct" width="210px">
                                <div class="img-category mt-2">
                                    <img src="../../image/product/product1/product-detail-1.png" id="imgProduct" width="50px">
                                    <img src="../../image/product/product1/product-detail-2.png" id="imgProduct" width="50px">
                                    <img src="../../image/product/product1/product-detail-3.png" id="imgProduct" width="50px">
                                    <img src="../../image/product/product1/product-detail-4.png" id="imgProduct" width="50px">
                                </div>
                            </div>
                            <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                <!-- Field chung -->
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <td id="nameProduct">Nike T-Shirt</td>
                                </tr>
                                <tr>
                                    <th>Mã sản phẩm</th>
                                    <td id="productCode">P001</td>
                                </tr>
                                <tr>
                                    <th>Giá</th>
                                    <td id="price">20$</td>
                                </tr>
                                <tr>
                                    <th>Danh mục</th>
                                    <td id="category">Quần áo</td>
                                </tr>
                                <tr>
                                    <th>Nhà cung cấp</th>
                                    <td id="codeSupplier">NCC001</td>
                                </tr>
                                <tr>
                                    <th>Mô tả</th>
                                    <td id="describe">Siuiuuuu</td>
                                </tr>
                                <tr>
                                    <th>Đối tượng</th>
                                    <td id="targetGender">Nam</th>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td id="status">Còn hàng</td>
                                </tr>

                                <!-- Field riêng quần áo -->
                                <tr>
                                    <th>Phong cách</th>
                                    <td id="shirtStyle">Sơ mi</td>
                                </tr>
                                <tr>
                                    <th>Chất liệu</th>
                                    <td id="shirtMaterial">Lụa</td>
                                </tr>
                                <tr>
                                    <th>Mô tả chất liệu</th>
                                    <td id="descriptionMaterial">Sơ mi nhung lụa cao cấp</td>
                                </tr>
                                <!-- Size số lượng -->
                                <tr style="border-top:2px solid black;">
                                    <th>Size</th>
                                    <th id="descriptionMaterial">Số lượng</th>

                                    <tr>
                                        <td>S</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>XL</td>
                                        <td>10</td>
                                    </tr>
                                </tr>
                                <tr>
                                    <th>Tổng</th>
                                    <td id="quantity">40</td>
                                </tr>
                                <!-- Field riêng túi sách -->
                                <!-- <tr>
                                    <th>Chất liệu</th>
                                    <td id="bagMaterial">Lụa</td>
                                </tr>
                                <tr>
                                    <th>Mô tả chất liệu</th>
                                    <td id="descriptionMaterial">Sơ mi nhung lụa cao cấp</td>
                                </tr>
                                <tr>
                                    <th>Số lượng</th>
                                    <td id="quantity">20</td>
                                </tr>  -->
                                </tbody>
                            </table>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="footer">
            <?php require('./footer_admin.php'); ?>
            </div>
        </div>
    </div>
    <script src="../../Js/sidebar.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
                $('#inputFile').change(function() {
                const files = $(this)[0].files;
                if (files.length > 0) {
                    const imagePreview = $('#imagePreview');
                    imagePreview.empty();

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                            imagePreview.append(img);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        });
    </script>
</body>
</body>
</html>