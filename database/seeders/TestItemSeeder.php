<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\Item;

class TestItemSeeder extends Seeder
{
    public function run(): void
    {
        $emptyTypes = Type::doesntHave('items')->get();

        $blueprints = [
            'Exercise Books' => [
                ['name' => 'Campus Exercise Book 80 Pages', 'price' => 1500, 'desc' => 'Standard exercise book for daily school use.'],
                ['name' => 'Premium Ruled Exercise Book 120 Pages', 'price' => 2500, 'desc' => 'High quality paper, smooth writing experience.'],
                ['name' => 'Maths Square Exercise Book', 'price' => 1800, 'desc' => 'Perfect for math and graph drawings.'],
                ['name' => 'Exercise Book Dozen Pack (12 Pcs)', 'price' => 16500, 'desc' => 'Bulk pack for back to school season.'],
            ],
            'Notebooks & Diaries' => [
                ['name' => 'A4 Spiral Notebook', 'price' => 4500, 'desc' => 'Durable spiral notebook with 200 pages.'],
                ['name' => 'Leather Cover Personal Diary', 'price' => 8500, 'desc' => 'Elegant diary for personal notes and planning.'],
                ['name' => 'B5 Subject Notebook', 'price' => 3000, 'desc' => 'Divided sections for multiple subjects.'],
                ['name' => 'Pocket Note Pad', 'price' => 1200, 'desc' => 'Easy to carry pocket sized notepad.'],
            ],
            'Drawing Books' => [
                ['name' => 'A3 Professional Drawing Book', 'price' => 5000, 'desc' => 'Thick paper suitable for watercolors and sketches.'],
                ['name' => 'A4 Standard Sketch Book', 'price' => 3500, 'desc' => 'Great for pencil and charcoal sketching.'],
                ['name' => 'Kids Coloring Book', 'price' => 2000, 'desc' => 'Fun patterns for kids to color.'],
                ['name' => 'Watercolor Pad 300gsm', 'price' => 7500, 'desc' => 'Premium watercolor paper pad.'],
            ],
            'A4 & Copy Papers' => [
                ['name' => 'Double A Copy Paper 80gsm (1 Ream)', 'price' => 12500, 'desc' => 'High quality A4 printer paper.'],
                ['name' => 'PaperOne A4 Paper 70gsm', 'price' => 11000, 'desc' => 'Standard A4 paper for daily printing.'],
                ['name' => 'Colored A4 Paper (Mixed Colors)', 'price' => 14000, 'desc' => 'Bright colored papers for arts and crafts.'],
                ['name' => 'Cardstock Paper A4 120gsm', 'price' => 16000, 'desc' => 'Thick paper for covers and certificates.'],
            ],
            'Sticky Notes' => [
                ['name' => 'Post-it Notes 3x3 Yellow', 'price' => 2500, 'desc' => 'Classic yellow sticky notes.'],
                ['name' => 'Neon Color Page Markers', 'price' => 1500, 'desc' => 'Small colored flags for marking pages.'],
                ['name' => 'Transparent Sticky Notes', 'price' => 3000, 'desc' => 'Clear notes for tracing and annotating books.'],
                ['name' => 'Sticky Note Cube (500 sheets)', 'price' => 5500, 'desc' => 'Long lasting desk sticky note cube.'],
            ],
            'School Shirts/Blouses' => [
                ['name' => 'Student White Shirt (Short Sleeve)', 'price' => 12000, 'desc' => 'Comfortable cotton blend school shirt.'],
                ['name' => 'Student White Shirt (Long Sleeve)', 'price' => 14000, 'desc' => 'Formal long sleeve school shirt.'],
                ['name' => 'Girls School Blouse', 'price' => 12500, 'desc' => 'Classic white blouse for school girls.'],
                ['name' => 'Premium Tetron White Shirt', 'price' => 18000, 'desc' => 'Wrinkle-resistant high quality shirt.'],
            ],
            'School Longyis/Trousers' => [
                ['name' => 'Green School Longyi (Standard)', 'price' => 9000, 'desc' => 'Official school green longyi.'],
                ['name' => 'Premium Green School Longyi', 'price' => 15000, 'desc' => 'High quality fabric, color won\'t fade.'],
                ['name' => 'Boys School Trousers (Green)', 'price' => 16000, 'desc' => 'Tailored fit green trousers.'],
                ['name' => 'Girls School Skirt (Green)', 'price' => 14000, 'desc' => 'Pleated green school skirt.'],
            ],
            'Belts & Socks' => [
                ['name' => 'Black Leather School Belt', 'price' => 6000, 'desc' => 'Durable belt with plain buckle.'],
                ['name' => 'White School Socks (3 Pairs)', 'price' => 4500, 'desc' => 'Breathable cotton white socks.'],
                ['name' => 'Black School Socks (3 Pairs)', 'price' => 4500, 'desc' => 'Comfortable everyday black socks.'],
                ['name' => 'Nylon School Belt', 'price' => 3500, 'desc' => 'Adjustable nylon web belt.'],
            ],
            'Backpacks' => [
                ['name' => 'Campus Waterproof Backpack', 'price' => 35000, 'desc' => 'Spacious backpack with laptop compartment.'],
                ['name' => 'Kids Cartoon Backpack', 'price' => 22000, 'desc' => 'Cute and lightweight bag for primary students.'],
                ['name' => 'Ergonomic Student Bag', 'price' => 45000, 'desc' => 'Provides excellent back support for heavy books.'],
                ['name' => 'Canvas Tote Bag', 'price' => 15000, 'desc' => 'Stylish and simple tote for tuition classes.'],
            ],
            'Water Bottles' => [
                ['name' => 'Stainless Steel Thermos Bottle', 'price' => 18000, 'desc' => 'Keeps water cold or hot for 12 hours.'],
                ['name' => 'BPA-Free Plastic Water Bottle 1L', 'price' => 8000, 'desc' => 'Durable and safe daily water bottle.'],
                ['name' => 'Kids Water Bottle with Straw', 'price' => 12000, 'desc' => 'Leak-proof bottle for young children.'],
                ['name' => 'Glass Water Bottle with Sleeve', 'price' => 14000, 'desc' => 'Eco-friendly and easy to clean.'],
            ],
            'Lunch Boxes' => [
                ['name' => 'Stainless Steel 3-Tier Lunch Box', 'price' => 25000, 'desc' => 'Keep your meals warm and separated.'],
                ['name' => 'Bento Box with Compartments', 'price' => 15000, 'desc' => 'Microwave-safe bento box.'],
                ['name' => 'Thermal Lunch Bag', 'price' => 9000, 'desc' => 'Insulated bag to carry your lunch box.'],
                ['name' => 'Kids Cute Lunch Box Set', 'price' => 18000, 'desc' => 'Includes fork and spoon.'],
            ],
            'Umbrellas & Raincoats' => [
                ['name' => 'Foldable Automatic Umbrella', 'price' => 12000, 'desc' => 'Easy to carry in school bags.'],
                ['name' => 'Kids Raincoat with Hood', 'price' => 16000, 'desc' => 'Waterproof and bright colored for safety.'],
                ['name' => 'Heavy Duty Raincoat', 'price' => 25000, 'desc' => 'Full body protection for rainy days.'],
                ['name' => 'Transparent Umbrella', 'price' => 8500, 'desc' => 'Stylish and sturdy clear umbrella.'],
            ],
            'Color Pencils & Crayons' => [
                ['name' => 'Faber-Castell 24 Color Pencils', 'price' => 12000, 'desc' => 'Vibrant colors, break-resistant.'],
                ['name' => 'Master Art 48 Watercolor Pencils', 'price' => 22000, 'desc' => 'Premium quality watercolor effects.'],
                ['name' => 'Crayola Wax Crayons 24 Colors', 'price' => 8000, 'desc' => 'Non-toxic crayons for kids.'],
                ['name' => 'Oil Pastels 36 Colors', 'price' => 15000, 'desc' => 'Smooth and blendable oil pastels.'],
            ],
            'Glues & Tapes' => [
                ['name' => 'UHU Glue Stick 21g', 'price' => 2500, 'desc' => 'Strong and fast-drying glue stick.'],
                ['name' => 'Clear Tape (Pack of 6)', 'price' => 3000, 'desc' => 'Standard clear adhesive tape.'],
                ['name' => 'Double Sided Tape 1 inch', 'price' => 1800, 'desc' => 'Strong double-sided mounting tape.'],
                ['name' => 'Liquid White Glue 100ml', 'price' => 2000, 'desc' => 'Perfect for school craft projects.'],
            ],
            'Drawing Papers & Boards' => [
                ['name' => 'A3 Drawing Board', 'price' => 15000, 'desc' => 'Sturdy wooden drawing board.'],
                ['name' => 'Watercolor Paper Pack (20 Sheets)', 'price' => 8500, 'desc' => 'Cold-pressed thick watercolor paper.'],
                ['name' => 'Canvas Panel 8x10 inch', 'price' => 4500, 'desc' => 'Pre-primed canvas for acrylic painting.'],
                ['name' => 'Tracing Paper Roll', 'price' => 6000, 'desc' => 'High transparency tracing paper.'],
            ],
            'Scissors & Craft Tools' => [
                ['name' => 'Safety Scissors for Kids', 'price' => 2000, 'desc' => 'Blunt tip for maximum safety.'],
                ['name' => 'Stainless Steel Craft Scissors', 'price' => 4500, 'desc' => 'Sharp and precise cutting.'],
                ['name' => 'Paper Cutter / Utility Knife', 'price' => 3000, 'desc' => 'Retractable blade for clean cuts.'],
                ['name' => 'Cutting Mat A4 Size', 'price' => 8000, 'desc' => 'Self-healing craft cutting mat.'],
            ],
            'School Textbooks' => [
                ['name' => 'Grade 10 Mathematics Textbook', 'price' => 4500, 'desc' => 'Official government curriculum textbook.'],
                ['name' => 'Grade 10 English Textbook', 'price' => 4500, 'desc' => 'Official government curriculum textbook.'],
                ['name' => 'Primary Level Science Book', 'price' => 3500, 'desc' => 'Basic science for primary students.'],
                ['name' => 'Grade 11 Physics Textbook', 'price' => 5000, 'desc' => 'Official government curriculum textbook.'],
            ],
            'Guide Books' => [
                ['name' => 'Grade 10 Math Guide Book', 'price' => 8500, 'desc' => 'Comprehensive guide with solutions.'],
                ['name' => 'Grade 11 Chemistry Guide', 'price' => 9000, 'desc' => 'Detailed explanations and practice tests.'],
                ['name' => 'English Grammar Practice Guide', 'price' => 7500, 'desc' => 'Essential grammar rules and exercises.'],
                ['name' => 'Matriculation Exam Past Papers', 'price' => 12000, 'desc' => '10 years of past exam papers.'],
            ],
            'Language Books' => [
                ['name' => 'English-Myanmar Dictionary', 'price' => 15000, 'desc' => 'Comprehensive bilingual dictionary.'],
                ['name' => 'Basic Japanese for Beginners', 'price' => 12000, 'desc' => 'Start learning Japanese today.'],
                ['name' => 'Korean Language Workbook', 'price' => 10500, 'desc' => 'Practice writing and reading Hangul.'],
                ['name' => 'IELTS Preparation Book', 'price' => 25000, 'desc' => 'Complete guide for IELTS exam.'],
            ],
            'Story & Knowledge Books' => [
                ['name' => 'Myanmar Folktales Collection', 'price' => 6000, 'desc' => 'Classic folktales for children.'],
                ['name' => 'World History Encyclopedia', 'price' => 18000, 'desc' => 'Discover the history of the world.'],
                ['name' => 'Aesop\'s Fables (Illustrated)', 'price' => 8500, 'desc' => 'Beautifully illustrated moral stories.'],
                ['name' => 'Space & Science Facts Book', 'price' => 12000, 'desc' => 'Fascinating facts about the universe.'],
            ]
        ];

        foreach ($emptyTypes as $type) {
            $itemsToCreate = $blueprints[$type->name] ?? [];
            
            // If we don't have a blueprint, generate generic names
            if (empty($itemsToCreate)) {
                for ($i = 1; $i <= 4; $i++) {
                    $itemsToCreate[] = [
                        'name' => "Generic {$type->name} Product $i",
                        'price' => rand(2000, 20000),
                        'desc' => "A quality product from the {$type->name} category."
                    ];
                }
            }

            foreach ($itemsToCreate as $itemData) {
                Item::create([
                    'type_id' => $type->id,
                    'name' => '[Test] ' . $itemData['name'],
                    'price' => $itemData['price'],
                    'description' => $itemData['desc'],
                    'status' => 'active'
                ]);
            }
        }
    }
}
