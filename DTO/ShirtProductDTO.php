<?php

class ShirtProductDTO extends ProductDTO {
       // Thuộc tính cho chất liệu của áo sơ mi
       private $shirtMaterial;
   
       // Thuộc tính cho mã kích thước của áo sơ mi
       private $sizeCode;
   
       // Thuộc tính cho mã màu sắc của áo sơ mi
       private $colorCode;
   
       // Thuộc tính cho kiểu dáng của áo sơ mi
       private $shirtStyle;
   
       // Constructor
       public function __construct($productCode, $imgProduct, $nameProduct, $supplierCode, $quantity, $describe, $status, $targetGender, $price, $shirtMaterial, $sizeCode, $colorCode, $shirtStyle) {
           // Gọi constructor của lớp cha (Product)
           parent::__construct($productCode, $imgProduct, $nameProduct, $supplierCode, $quantity, $describe, $status, $targetGender, $price);
   
           // Thiết lập các thuộc tính mới của ShirtProduct
           $this->shirtMaterial = $shirtMaterial;
           $this->sizeCode = $sizeCode;
           $this->colorCode = $colorCode;
           $this->shirtStyle = $shirtStyle;
       }
   
       // Getter và Setter cho $shirtMaterial
       public function getShirtMaterial() {
           return $this->shirtMaterial;
       }
   
       public function setShirtMaterial($shirtMaterial) {
           $this->shirtMaterial = $shirtMaterial;
       }
   
       // Getter và Setter cho $sizeCode
       public function getSizeCode() {
           return $this->sizeCode;
       }
   
       public function setSizeCode($sizeCode) {
           $this->sizeCode = $sizeCode;
       }
   
       // Getter và Setter cho $colorCode
       public function getColorCode() {
           return $this->colorCode;
       }
   
       public function setColorCode($colorCode) {
           $this->colorCode = $colorCode;
       }
   
       // Getter và Setter cho $shirtStyle
       public function getShirtStyle() {
           return $this->shirtStyle;
       }
   
       public function setShirtStyle($shirtStyle) {
           $this->shirtStyle = $shirtStyle;
       }
   }
   