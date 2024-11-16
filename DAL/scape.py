import requests
from bs4 import BeautifulSoup
import csv
import os
from urllib.parse import urljoin

# URL của trang web
url = "https://www.thegioididong.com/"

# Thêm User-Agent vào headers
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
}

# Gửi yêu cầu GET đến trang web với headers
response = requests.get(url, headers=headers)

# Kiểm tra nếu kết nối thành công
if response.status_code == 200:
    # Sử dụng BeautifulSoup để phân tích HTML
    soup = BeautifulSoup(response.text, 'html.parser')

    # Tìm tất cả các thẻ sản phẩm (dựa trên cấu trúc HTML thực tế)
    products = soup.find_all('div', class_='item')

    # Lưu thông tin sản phẩm
    product_list = []

    # Tạo thư mục 'tienphat' nếu chưa tồn tại
    if not os.path.exists('tienphat'):
        os.makedirs('tienphat')

    # Duyệt qua từng sản phẩm và lấy các thông tin cần thiết
    for idx, product in enumerate(products, start=1):
        # Tên sản phẩm
        name = product.find('h3').get_text() if product.find('h3') else 'Không có tên'

        # Giá sản phẩm
        price = product.find('strong', class_='price').get_text() if product.find('strong') else 'Không có giá'

        # Đường dẫn sản phẩm
        link = product.find('a', class_='remain_quantity main-contain')['href'] if product.find('a') else 'Không có đường dẫn'

        # URL của hình ảnh sản phẩm (giả sử thẻ <img> chứa ảnh trong <li>)
        image_tag = product.find('img')
        image_url = image_tag['data-src'] if image_tag and 'data-src' in image_tag.attrs else None

        # Tải ảnh về nếu có
        if image_url:
            # Tạo đường dẫn tuyệt đối cho hình ảnh
            image_url = urljoin(url, image_url)
            image_name = f'tienphat/product_{idx}.jpg'
            try:
                img_data = requests.get(image_url).content
                with open(image_name, 'wb') as img_file:
                    img_file.write(img_data)
                print(f"Đã tải hình ảnh sản phẩm {idx} ({name}) về {image_name}")
            except Exception as e:
                print(f"Lỗi khi tải hình ảnh sản phẩm {idx}: {e}")

        # Lưu thông tin sản phẩm vào danh sách
        product_list.append([idx, name, price, 'https://www.thegioididong.com' + link])

    # Đường dẫn tới file CSV
    file_name = 'thong_tin_san_pham.csv'

    # Kiểm tra nếu file đã tồn tại
    file_exists = os.path.exists(file_name)

    # Mở file CSV để ghi dữ liệu (hoặc cập nhật nếu file đã tồn tại)
    with open(file_name, mode='a', newline='', encoding='utf-8') as file:
        writer = csv.writer(file)

        # Nếu file chưa tồn tại, ghi tiêu đề cột vào file CSV
        if not file_exists:
            writer.writerow(['STT', 'Tên sản phẩm', 'Giá', 'Đường dẫn'])  # Ghi tiêu đề vào file

        # Ghi từng sản phẩm vào file CSV với cột STT (số thứ tự)
        for product in product_list:
            writer.writerow(product)  # Ghi số thứ tự và thông tin sản phẩm vào file

    print(f"Thông tin sản phẩm đã được cập nhật vào file {file_name}")
else:
    print("Không thể kết nối đến trang web.")
