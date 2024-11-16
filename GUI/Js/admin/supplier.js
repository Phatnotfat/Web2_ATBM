// ------------------------------------------- AJAX kiểm tra đăng nhập -----------------------------------------------
async function checkLogin_supplier() {
  try {
    const response = await fetch("../../../BLL/AccountBLL.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "function=" + encodeURIComponent("checkLogin"),
    });
    const data = await response.json();
    console.log(data);
    let result = data[0];
    if (result.result == "success" && result.codePermission != "user") {
      // getDataPermission_payment(result.codePermission);
      return result.codePermission;
    }
    // for (let i of data) {
    //        console.log(i);
    // }
    // showProductItem(data);
  } catch (error) {
    console.error("Error:", error);
  }
}
// checkLogin();

async function getDataPermission_supplier(codePermission) {
  try {
    const response = await fetch("../../../BLL/ManagerUserGroupBLL.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body:
        "function=" +
        encodeURIComponent("getArrPermissionDetail") +
        "&codePermission=" +
        encodeURIComponent(codePermission),
    });
    const data = await response.json();
    console.log(data);

    if (data != null) {
      // checkUpPermission_payment(data.permissionDetail,codePermission,"");
      return data;
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

// setup các chức năng được truy cập
function checkUpPermission_supplier(dataPermissionDetail, functionPoint) {
  if (functionPoint == "") {
    return false;
  }

  for (let item of dataPermissionDetail) {
    if (item.functionCode == "supplier") {
      console.log("Phan quyen");
      // thêm
      if (item.addPermission == "1" && functionPoint == "add") {
        console.log("Được phép thêm");
        return true;
      } else if (item.addPermission != "1" && functionPoint == "add") {
        console.log("Không Được phép thêm");
        return false;
      }
      // sửa
      if (item.fixPermission == "1" && functionPoint == "update") {
        console.log("Được phép sửa");
        return true;
      } else if (item.fixPermission != "1" && functionPoint == "update") {
        console.log("Không Được phép sửa");
        return false;
      }

      // xóa
      if (item.deletePermission == "1" && functionPoint == "delete") {
        console.log("Được phép xóa");
        return true;
      } else if (item.deletePermission != "1" && functionPoint == "delete") {
        console.log("Không Được phép xóa");
        return false;
      }
    }
  }
}

//Lấy danh sách đối tượng
async function getListObj() {
  try {
    // Gọi AJAX để xóa payment

    let response = await fetch("../../../BLL/SupplierBLL.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "function=" + encodeURIComponent("getList"),
    });

    let data = await response.json();
    console.log(data);
    await loadData(data);
    loadPage();
  } catch (error) {
    console.error(error);
  }
}

//lấy dữ liệu từ kết quả  rearch
function searchSupplier() {
  // Lấy tham số 'search' từ URL
  const urlParams = new URLSearchParams(window.location.search);
  let str = urlParams.get("search");

  // Kiểm tra nếu có tham số 'search' trong URL
  if (str) {
    // Cập nhật giá trị cho thẻ input nếu cần
    document.getElementById("input-search-supplier").value = str;
    console.log("str : "+str);
    

    // Gọi hàm xử lý tìm kiếm với giá trị str
    performSearch(str);
  }

  // Thêm sự kiện để thay đổi URL khi người dùng nhập vào input
  document.getElementById("input-search-supplier").oninput = function () {
    let inputStr = document
      .getElementById("input-search-supplier")
      .value.trim();

    console.log("change : "+inputStr);

    // Cập nhật URL với giá trị tìm kiếm mới
    const newUrl = new URL(window.location.href);
    if (inputStr) {
      newUrl.searchParams.set("search", inputStr); // Thêm hoặc cập nhật tham số 'search' trong URL
    } else {
      newUrl.searchParams.delete("search"); // Xóa tham số 'search' nếu chuỗi rỗng
    }

    // Cập nhật URL mà không tải lại trang
    window.history.replaceState({}, "", newUrl);

    // Gọi hàm xử lý tìm kiếm với giá trị mới
    performSearch(inputStr);
  };
}

// Hàm thực hiện tìm kiếm và gửi dữ liệu qua AJAX đến BLL
async function performSearch(str) {
  try {
    let response = await fetch("../../../BLL/SupplierBLL.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body:
        "function=" +
        encodeURIComponent("searchSupplier") +
        "&str=" +
        encodeURIComponent(str),
    });

    let data = await response.json();


    
    if (!data || data.length === 0 || data.mess === "No data found") {
      console.log("Không có dữ liệu");
      document.querySelector("#Pagination").style.display = "none";
      loadData([]);
    } else {
      console.log("Có dữ liệu");
      loadData(data);
      document.querySelector("#Pagination").style.display = "flex";
      loadItem(1, 4);
    }

    console.log("Dữ liệu đã tải:", data);
    loadPage();
  } catch (error) {
    console.error("Lỗi:", error);
  }
}

async function loadData(data) {
  let container = document.getElementById("table-supplier");
  let container1 = document.getElementById("delete-Supplier");
  let container2 = document.getElementById("edit-Supplier");
  let result = ``;
  let result1 = ``;
  let result2 = ``;

  for (let i of data) {
    let codeSupplier = i.codeSupplier;
    let nameSupplier = i.nameSupplier;
    let address = i.address;
    let email = i.email;
    let brandSupplier = i.brandSupplier;
    let phoneNumber = i.phoneNumber;
    let String = `
         <tr>
             <td>${codeSupplier}</td>
             <td>${nameSupplier}</td>
             <td>${address}</td>
             <td>${email}</td>
             <td>${brandSupplier}</td>
             <td>${phoneNumber}</td>
             <td>
                 <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSupplier-${i.codeSupplier}"><i class="fa fa-edit"></i></a>
                 <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSupplier-${i.codeSupplier}"><i class="fa fa-trash"></i></a>
             </td>
         </tr>
         
         `;

    let String1 = `
         <div class="modal fade" id="deleteSupplier-${i.codeSupplier}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="deleteModalLabel">Xóa nhà cung cấp</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                         Bạn có chắc muốn xóa nhà cung cấp này?
                         <br>
                         Mã nhà cung cấp: ${i.codeSupplier}
                         <br>
                         Tên nhà cung cấp: ${i.nameSupplier}
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                         <button type="button" data-bs-dismiss="modal" class="btn btn-danger btn-confirm-delete" onclick="deleteByID('${i.codeSupplier}')">Xóa</button>
                     </div>
                 </div>
             </div>
         </div>
         
         `;
    let String2 = `
         <div class="modal fade" id="editSupplier-${i.codeSupplier}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="editModalLabel">Sửa nhà cung cấp</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                         <form id="editForm">
                             <div class="mb-3">
                                 <label for="name" class="form-label">Mã nhà cung cấp</label>
                                 <input type="text" class="form-control" id="${i.codeSupplier}" value="${i.codeSupplier}" name="codeSupplier" placeholder="NCC001" disabled>
                             </div>
                             <div class="mb-3">
                                 <label for="name" class="form-label">Tên nhà cung cấp</label>
                                 <input type="text" class="form-control" id="${i.codeSupplier}-${i.nameSupplier}" value="${i.nameSupplier}" name="nameSupplier">
                             </div>
                             <div class="mb-3">
                                 <label for="address" class="form-label">Địa chỉ</label>
                                 <input type="text" class="form-control" id="${i.codeSupplier}-${i.address}" value="${i.address}" name="address">
                             </div>
                             <div class="mb-3">
                                 <label for="email" class="form-label">Email</label>
                                 <input type="text" class="form-control" id="${i.codeSupplier}-${i.email}" value="${i.email}" name="email">
                             </div>
                             <div class="mb-3">
                                 <label for="brandsuppliers" class="form-label">Thương hiệu cung cấp</label>
                                 <input type="text" class="form-control" id="${i.codeSupplier}-${i.brandSupplier}" value="${i.brandSupplier}" name="brandSupplier">
                             </div>
     
                             <div class="mb-3">
                                 <label for="phoneNumber" class="form-label">Số điện thoại</label>
                                 <input class="form-control" id="${i.codeSupplier}-${i.phoneNumber}" value="${i.phoneNumber}" name="phoneNumber">
                             </div>
                             <div style="text-align:right;">
                                 <button type="submit" data-bs-dismiss="modal" class="btn btn-primary" onclick="updateObj('${i.codeSupplier}', '${i.codeSupplier}-${i.nameSupplier}', '${i.codeSupplier}-${i.address}', '${i.codeSupplier}-${i.email}', '${i.codeSupplier}-${i.brandSupplier}','${i.codeSupplier}-${i.phoneNumber}', event)" >Sửa nhà cung cấp</button>
                                 
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
         
         `;
    // console.log(String);
    result += String;
    result1 += String1;
    result2 += String2;
  }
  // console.log(result);
  container.innerHTML = result;
  container1.innerHTML = result1;
  // console.log(result1);
  container2.innerHTML = result2;
  // console.log(result2);
}

// Lấy một đối tượng bằng id
async function getObj() {
  try {
    // Gọi AJAX để xóa payment

    let response = await fetch("../../../BLL/SupplierBLL.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body:
        "function=" +
        encodeURIComponent("getObj") +
        "&codeSupplier=" +
        encodeURIComponent(obj.codeSupplier),
    });

    let data = await response.json();
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}

// Them doi tuong
// thêm một đối tương
async function addObj(event) {
  // lấy thông tin mã  codePermission của người đăng nhập
  let codePermission = await checkLogin_supplier();
  // lấy mảng chi tiết phân quyền dựa theo mã phân quyền của người đăng nhập
  let data = await getDataPermission_supplier(codePermission);
  // lấy thông tin có được phép làm chức ăng đó không
  let check = checkUpPermission_supplier(data.permissionDetail, "add");

  if (check == true) {
    window.location.href = "../../../GUI/view/admin/addsupplier.php";
  } else {
    event.preventDefault();
    Swal.fire({
      icon: "error",
      title: "Thêm không thành công",
      text: "Không đủ quyền hàng",
    });
  }
}

//Xóa một đối tượng
async function deleteByID(code) {
  // lấy thông tin mã  codePermission của người đăng nhập
  let codePermission = await checkLogin_supplier();
  // lấy mảng chi tiết phân quyền dựa theo mã phân quyền của người đăng nhập
  let data = await getDataPermission_supplier(codePermission);
  // lấy thông tin có được phép làm chức ăng đó không
  let check = checkUpPermission_supplier(data.permissionDetail, "delete");

  if (check == true) {
    try {
      // Gọi AJAX để xóa payment

      let response = await fetch("../../../BLL/SupplierBLL.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body:
          "function=" +
          encodeURIComponent("deleteByID") +
          "&codeSupplier=" +
          encodeURIComponent(code),
      });

      let data = await response.json();
      console.log(data);

      if (data.mess === "success") {
        Swal.fire({
          position: "center",
          icon: "success",
          title: "Đã xóa nhà cung cấp thành công",
          showConfirmButton: false,
          timer: 1500,
        });
        await getListObj();
      } else {
        Swal.fire({
          icon: "error",
          title: "Xóa không thành công",
          text: "Bị ràng buộc dữ liệu",
        });
      }
    } catch (error) {
      console.error(error);
    }
  } else {
    Swal.fire({
      icon: "error",
      title: "Xóa không thành công",
      text: "Không đủ quyền hàng",
    });
  }
}

//Sửa một đối tượng
async function updateObj(
  codeSupplier,
  nameSupplier,
  address,
  email,
  brandSupplier,
  phoneNumber,
  event
) {
  event.preventDefault();
  // lấy thông tin mã  codePermission của người đăng nhập
  let codePermission = await checkLogin_supplier();
  // lấy mảng chi tiết phân quyền dựa theo mã phân quyền của người đăng nhập
  let data = await getDataPermission_supplier(codePermission);
  // lấy thông tin có được phép làm chức ăng đó không
  let check = checkUpPermission_supplier(data.permissionDetail, "update");

  if (check == true) {
    let codeSupplierValue = document.getElementById(codeSupplier).value.trim();
    let nameSupplierValue = document.getElementById(nameSupplier).value.trim();
    let addressValue = document.getElementById(address).value.trim();
    let emailValue = document.getElementById(email).value.trim();
    let brandSupplierValue = document
      .getElementById(brandSupplier)
      .value.trim();
    let phoneNumberValue = document.getElementById(phoneNumber).value.trim();
    console.log(nameSupplierValue);
    console.log(codeSupplierValue);
    // Kiểm tra xem có bất kỳ trường nào để trống không
    if (
      !codeSupplierValue ||
      !nameSupplierValue ||
      !addressValue ||
      !emailValue ||
      !brandSupplierValue ||
      !phoneNumberValue
    ) {
      Swal.fire({
        icon: "error",
        title: "Lỗi",
        text: "Vui lòng điền đầy đủ thông tin",
      });
      await getListObj();
      return;
    }

    // Kiểm tra định dạng email
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailValue)) {
      Swal.fire({
        icon: "error",
        title: "Lỗi",
        text: "Email không hợp lệ",
      });
      await getListObj();
      return;
    }

    // Kiểm tra số điện thoại
    let phoneRegex = /^\d{10}$/;
    if (!phoneRegex.test(phoneNumberValue)) {
      Swal.fire({
        icon: "error",
        title: "Lỗi",
        text: "Số điện thoại phải có 10 chữ số",
      });
      await getListObj();
      return;
    }
    try {
      // Gọi AJAX để sửa đối tượng
      let response = await fetch("../../../BLL/SupplierBLL.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body:
          "function=" +
          encodeURIComponent("updateObj") +
          "&codeSupplier=" +
          encodeURIComponent(codeSupplierValue) +
          "&nameSupplier=" +
          encodeURIComponent(nameSupplierValue) +
          "&address=" +
          encodeURIComponent(addressValue) +
          "&email=" +
          encodeURIComponent(emailValue) +
          "&brandSupplier=" +
          encodeURIComponent(brandSupplierValue) +
          "&phoneNumber=" +
          encodeURIComponent(phoneNumberValue),
      });
      let data = await response.json();
      console.log(data);
      if (data.mess === "success") {
        Swal.fire({
          position: "center",
          icon: "success",
          title: "Sửa nhà cung cấp thành công",
          showConfirmButton: false,
          timer: 1500,
        });
        await getListObj();
      }
    } catch (error) {
      console.error(error);
    }
  } else {
    Swal.fire({
      icon: "error",
      title: "Sửa không thành công",
      text: "Không đủ quyền hàng",
    });
  }
}

//Phần phân trang

function loadItem(thisPage, limit) {
  // tính vị trí bắt đầu và kêt thúc
  let beginGet = limit * (thisPage - 1);
  let endGet = limit * thisPage - 1;

  // lấy tất cả các dòng dữ liệu có trong bảng
  let all_data_rows = document.querySelectorAll("#table-supplier > tr");

  all_data_rows.forEach((item, index) => {
    if (index >= beginGet && index <= endGet) {
      item.style.display = "table-row";
    } else {
      item.style.display = "none";
    }
  });

  // hàm tính có bao nhieu nút chuyển trang
  listPage(thisPage, limit, all_data_rows);
  // loadPage();
}

function listPage(thisPage, limit, all_data_rows) {
  let result = "";
  let count = Math.ceil(all_data_rows.length / limit);
  // thêm nút prev

  if (thisPage != 1) {
    let string = `<li class="page-item" onclick="loadItem(${
      Number(thisPage) - 1
    },${limit})"><a class="page-link">Previous</a></li>`;
    result += string;
  } else if (thisPage == 1) {
    let string = `<li class="page-item disabled" style="cursor: default;"><a class="page-link">Previous</a></li>`;
    result += string;
  }

  // tính xem có bao nhieu nút

  // lấy container chứa nút phân trang
  let container = document.getElementById("Pagination");

  for (let i = 1; i <= count; i++) {
    let string = `<li class="page-item" onclick="loadItem(${i},${limit})"><a class="page-link">${i}</a></li>`;
    if (i == thisPage) {
      string = `<li class="page-item active" onclick="loadItem(${i},${limit})"><a class="page-link">${i}</a></li>`;
    }
    result += string;
  }

  // thêm nút next

  if (thisPage != count) {
    let string1 = `<li class="page-item" onclick="loadItem(${
      Number(thisPage) + 1
    },${limit})"><a class="page-link">Next</a></li>`;
    result += string1;
  } else if (thisPage == count) {
    let string1 = `<li class="page-item disabled" style="cursor: default;"><a class="page-link">Next</a></li>`;
    result += string1;
  }

  container.innerHTML = result;
}

function loadPage() {
  // Lấy danh sách tất cả các thẻ <li> trong <ul> có id là "Panigation"
  var listItems = document.querySelectorAll("#Pagination li");

  // Duyệt qua từng phần tử trong danh sách
  listItems.forEach(function (item) {
    // Kiểm tra xem phần tử hiện tại có class là "active" hay không
    if (item.classList.contains("active")) {
      // Nếu có, lấy nội dung trong thẻ <a> bên trong và chuyển thành số
      var activePageText = item.querySelector("a").textContent.trim();
      var activePageNumber = parseInt(activePageText);
      console.log("Trang đang active: " + activePageNumber);
      loadItem(activePageNumber, 4);
    }
  });
}

window.addEventListener("load", async function () {
  // Thực hiện các hàm bạn muốn sau khi trang web đã tải hoàn toàn, bao gồm tất cả các tài nguyên như hình ảnh, stylesheet, v.v.
  console.log("Trang quản lý nhà cung cấp đã load hoàn toàn");
  await getListObj();
  loadItem(1, 4);
  searchSupplier();
});
