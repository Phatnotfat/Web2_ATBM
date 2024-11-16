# Hàm loại bỏ dấu cách và chuyển chuỗi về dạng in hoa
def preprocess_text(text):
    return text.replace(" ", "").upper()

# Hàm xác định ký tự khóa giữa một ký tự của bản rõ và bản mã
def calculate_key_char(plain_char, cipher_char):
    return chr(((ord(cipher_char) - ord(plain_char) + 26) % 26) + ord('A'))

# Hàm tạo khóa kết hợp từ bản rõ và bản mã
def generate_combined_key(plaintext, ciphertext):
    return "".join(calculate_key_char(p, c) for p, c in zip(plaintext, ciphertext))

# Hàm tìm khóa tối thiểu từ khóa kết hợp bằng cách xác định chu kỳ ngắn nhất
def find_minimal_key(key_combined):
    for length in range(1, len(key_combined) + 1):
        if key_combined[:length] * (len(key_combined) // length) == key_combined:
            return key_combined[:length]
    return key_combined

# Hàm chính để tìm khóa tối thiểu
def vigenere_key_minimal(plaintext, ciphertext):
    # Tiền xử lý bản rõ và bản mã
    plaintext = preprocess_text(plaintext)
    ciphertext = preprocess_text(ciphertext)
    
    # Tạo khóa kết hợp và tìm khóa tối thiểu
    key_combined = generate_combined_key(plaintext, ciphertext)
    return find_minimal_key(key_combined)

# Ví dụ
plaintext = "ANH AN COM"
ciphertext = "EZL MR OSY"
minimal_key = vigenere_key_minimal(plaintext, ciphertext)
print("Khóa gốc tối thiểu là:", minimal_key)
