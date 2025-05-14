<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [

                'name' => 'عقارات',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'عقارات سكنية'],
                    ['name' => 'عقارات تجارية'],
                    ['name' => 'اراضي'],
                    ['name' => 'عقارت قيد الإنشاء'],
                    ['name' => 'اراضي عالمخطط'],
                ]
            ],
            [

                'name' => 'عربات',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'سيارات'],
                    ['name' => 'دراجات'],
                    ['name' => 'اليات ثقيلة'],
                ]
            ],
            [

                'name' => 'فرص عمل',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'إدارية وتنظيمية'],
                    ['name' => 'محاسبة ومالية'],
                    ['name' => 'تسويق ومبيعات'],
                    ['name' => 'تكنولوجيا ومعلومات'],
                    ['name' => 'تعليم وتدريب'],
                    ['name' => 'خدمات صيانة'],
                    ['name' => 'طب ورعاية صحية'],
                    ['name' => 'تصميم وإبداع'],
                    ['name' => 'نقل وخدمات لوجستية'],
                    ['name' => 'أخرى (عمالة حرة)'],
                ]
            ],
            [

                'name' => 'ازياء و موضة',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'البسة رجالية'],
                    ['name' => 'البسة نسائية'],
                    ['name' => 'البسة أطفال'],
                    ['name' => 'مكياج'],
                    ['name' => 'منتجات عناية بالبشرة'],
                    ['name' => 'ساعات و اكسسوارات'],
                ]
            ],
            [

                'name' => 'الأثاث و الديكور',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'غرف معيشة'],
                    ['name' => 'مكاتب'],
                    ['name' => 'مطابخ'],
                    ['name' => 'ادوات مطبخ'],
                    ['name' => 'اضاءة و ديكور'],
                    ['name' => 'اثاث خارجي'],
                ]
            ],
            [

                'name' => 'الكترونيات',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'هواتف ذكية'],
                    ['name' => 'حواسيب'],
                    ['name' => 'كاميرات'],
                    ['name' => 'العاب الكترونية'],
                    ['name' => 'اجهزة منزلية'],
                    ['name' => 'اجهزة صوت و صورة'],
                    ['name' => 'معدات تقنية'],
                ]
            ],
            [

                'name' => 'تسلية',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'ادوات موسيقية'],
                    ['name' => 'كتب و مجلات'],
                    ['name' => 'العاب الطاولة و الفيديو'],
                    ['name' => 'معدات رياضية'],
                    ['name' => 'تذاكر فعاليات و حفلات'],
                ]
            ],
            [

                'name' => 'اعلانات تجارية و متفرقات',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'تصفية محلات'],
                    ['name' => 'بيع بالجملة'],
                    ['name' => 'معدات المتاجر و المطاعم'],
                    ['name' => 'فرص استثمارية'],
                    ['name' => 'اغراض متنوعة'],
                ]
            ],
            [

                'name' => 'الحيوانات و الزراعة',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'حيوانات أليفة'],
                    ['name' => 'مستلزمات حيوانات'],
                    ['name' => 'معدات زراعية'],
                    ['name' => 'منتجات غذائية'],
                    ['name' => 'خدمات رعاية حيوان'],
                ]
            ],
            [

                'name' => 'خدمات',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'صيانة وإصلاح'],
                    ['name' => 'نقل وتوصيل'],
                    ['name' => 'تنظيف'],
                    ['name' => 'تصميم وإبداع'],
                    ['name' => 'تعليمية وتربوية'],
                    ['name' => 'كمبيوتر وبرمجة'],
                    ['name' => 'صحية وطبية'],
                    ['name' => 'تجميل وعناية شخصية'],
                    ['name' => 'قانونية واستشارات'],
                    ['name' => 'فعاليات ومناسبات'],
                    ['name' => 'زراعة وحدائق'],
                    ['name' => 'حيوانات أليفة'],
                ]
            ],
            [

                'name' => 'الصناعة و البناء',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'معدات البناء و المقاولات'],
                    ['name' => 'ادوات الحرفيين'],
                    ['name' => 'تجهيزات صناعية'],
                    ['name' => 'مولدات الطاقة و ادوات كهربائية'],
                ]
            ],
            [

                'name' => 'المانيا',
                'parent_id' => null,
                'subcategories' => [
                    ['name' => 'خدمات'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            // Create parent category
            $parent = Category::updateOrCreate(
                ['id' => $categoryData['id']],
                [
                    'name' => $categoryData['name'],
                    'parent_id' => $categoryData['parent_id'],
                ]
            );

            // Create subcategories
            foreach ($categoryData['subcategories'] as $subcategory) {
                Category::updateOrCreate(
                    ['id' => $subcategory['id']],
                    [
                        'name' => $subcategory['name'],
                        'parent_id' => $parent->id,
                    ]
                );
            }
        }
    }
}
