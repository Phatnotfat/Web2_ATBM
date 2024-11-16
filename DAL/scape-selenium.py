from selenium import webdriver
from selenium.webdriver.common.by import By
from bs4 import BeautifulSoup
import time
import os
import requests

# Tạo trình duyệt Chrome
driver = webdriver.Chrome()

# URL của trang web
url = "https://www.thegioididong.com/phu-kien/apple"

# Mở trang web
driver.get(url)

# Đợi vài giây để trang tải và JavaScript thực thi
time.sleep(5)

# Nhấn vào nút "Xem thêm"
try:
    xem_them_button = driver.find_element(By.XPATH, '//*[@id="categoryPage"]/div[5]/div[3]/a')
    driver.execute_script("arguments[0].click();", xem_them_button)
    print("Đã nhấn vào nút 'Xem thêm'")
    time.sleep(5)  # Đợi trang mới tải xong
except Exception as e:
    print(f"Lỗi khi nhấn nút 'Xem thêm': {e}")

# Lấy nội dung trang đã tải sau khi nhấn nút
html = driver.page_source

# Đóng trình duyệt sau khi đã lấy được nội dung
driver.quit()

# Phân tích HTML bằng BeautifulSoup
soup = BeautifulSoup(html, 'html.parser')

# Tìm tất cả các sản phẩm có class bắt đầu bằng 'item'
products = soup.select('li[class^="item"]')

# Tạo thư mục lưu ảnh
folder_name = 'tienphat5'
if not os.path.exists(folder_name):
    os.makedirs(folder_name)

# Lưu thông tin sản phẩm
product_list = []

# Duyệt qua từng sản phẩm và lấy các thông tin cần thiết
for index, product in enumerate(products, start=1):
    # Tên sản phẩm
    name = product.find('h3').get_text() if product.find('h3') else 'Không có tên'

    # Giá sản phẩm
    price = product.find('strong', class_='price').get_text() if product.find('strong') else 'Không có giá'

    # Đường dẫn sản phẩm
    link = product.find('a')['href'] if product.find('a') else 'Không có đường dẫn'
    
    # Lấy URL hình ảnh
    image_tag = product.find('img')
    image_url = image_tag['data-src'] if image_tag and 'data-src' in image_tag.attrs else None

    if image_url:
        # Tải và lưu hình ảnh
        image_response = requests.get(image_url)
        image_name = os.path.join(folder_name, f'product_{index}.jpg')
        
        # Lưu file hình ảnh
        with open(image_name, 'wb') as img_file:
            img_file.write(image_response.content)

        print(f"Đã tải hình ảnh của sản phẩm: {name}")

    # Lưu thông tin sản phẩm vào danh sách
    product_list.append([index, name, price, 'https://www.thegioididong.com' + link])

# Thông báo hoàn thành
print(f"Đã tải {len(product_list)} sản phẩm và hình ảnh.")
