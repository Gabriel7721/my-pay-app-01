<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'slug' => 'caudat-arabica-250g',
                'name' => 'Cầu Đất Arabica 250g',
                'description' => 'Hạt Arabica Cầu Đất rang vừa, hương hoa nhẹ, hậu vị ngọt.',
                'price' => 4.40,
                'currency' => 'USD',
                'image_url' => 'https://product.hstatic.net/200000953892/product/bung_huong_cau_dat_85b55513b81343eab2723a639e1d96db_master.png',
                'stock' => 120,
            ],
            [
                'slug' => 'bmt-robusta-500g',
                'name' => 'Buôn Ma Thuột Robusta 500g',
                'description' => 'Robusta rang đậm, body dày, phù hợp pha phin và espresso đậm vị.',
                'price' => 5.37,
                'currency' => 'USD',
                'image_url' => 'https://tycoffee.vn/wp-content/uploads/2021/04/5326f3f08407765e69ad40a2c7fa89e0.jpg',
                'stock' => 200,
            ],
            [
                'slug' => 'phin-inox-1-2',
                'name' => 'Phin Inox 1–2 ly',
                'description' => 'Phin Inox bền, lỗ lọc đều, cho chiết xuất ổn định.',
                'price' => 2.55,
                'currency' => 'USD',
                'image_url' => 'https://bizweb.dktcdn.net/100/464/495/products/phin-pha-ca-phe-inox-cao-cap-blao-farm.jpg?v=1666966473207',
                'stock' => 150,
            ],
            [
                'slug' => 'ly-su-trang-350ml',
                'name' => 'Ly sứ trắng 350ml',
                'description' => 'Ly sứ tráng men trắng, giữ nhiệt tốt, dễ vệ sinh.',
                'price' => 2.18,
                'currency' => 'USD',
                'image_url' => 'https://linhkienlamphat.com/upload/images/C%E1%BB%90C%20S%E1%BB%A8%20TR%E1%BA%AENG%20350ML%20C%C3%93%20QUAI%20C%E1%BA%A6M(1).JPG',
                'stock' => 180,
            ],
        ];
        foreach ($products as $p) Product::create($p);
    }
}
