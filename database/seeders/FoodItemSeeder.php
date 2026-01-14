<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodItem;

class FoodItemSeeder extends Seeder
{
    public function run(): void
    {

        $foods = [
            // --- Petit Déjeuner ---
            ['name' => 'Pain', 'category' => 'petitDejeuner', 'tags' => json_encode(['pain'])],
            ['name' => 'Confiture', 'category' => 'petitDejeuner', 'tags' => json_encode(['sucre'])],
            ['name' => 'Beurre', 'category' => 'petitDejeuner', 'tags' => json_encode(['gras'])],
            ['name' => 'Lait', 'category' => 'petitDejeuner', 'tags' => json_encode(['produitLaitier'])],
            ['name' => 'Café', 'category' => 'petitDejeuner', 'tags' => json_encode([])],
            ['name' => 'Thé', 'category' => 'petitDejeuner', 'tags' => json_encode([])],
            ['name' => 'Chocolat Chaud', 'category' => 'petitDejeuner', 'tags' => json_encode(['sucre'])],
            ['name' => 'Jus de Citron', 'category' => 'petitDejeuner', 'tags' => json_encode(['fruit'])],
            ['name' => 'Fruits', 'category' => 'petitDejeuner', 'tags' => json_encode(['fruit'])],
            ['name' => 'Jus de Fruits', 'category' => 'petitDejeuner', 'tags' => json_encode(['fruit', 'sucre'])],
            ['name' => 'Oeufs', 'category' => 'petitDejeuner', 'tags' => json_encode(['oeuf', 'proteine'])],
            ['name' => 'Fromage blanc', 'category' => 'petitDejeuner', 'tags' => json_encode(['produitLaitier', 'proteine'])],
            ['name' => 'Flocon d\'avoine', 'category' => 'petitDejeuner', 'tags' => json_encode(['cereale'])],
            ['name' => 'Beurre de cacahuète', 'category' => 'petitDejeuner', 'tags' => json_encode(['gras', 'proteine'])],
            ['name' => 'Banane', 'category' => 'petitDejeuner', 'tags' => json_encode(['fruit'])],

            // --- Plats Principaux ---
            ['name' => 'Filet de poulet', 'category' => 'platPrincipal', 'tags' => json_encode(['viande', 'proteine'])],
            ['name' => 'Steak haché', 'category' => 'platPrincipal', 'tags' => json_encode(['viande', 'proteine'])],
            ['name' => 'Saumon grillé', 'category' => 'platPrincipal', 'tags' => json_encode(['poisson', 'proteine'])],
            ['name' => 'Tofu', 'category' => 'platPrincipal', 'tags' => json_encode(['vegetarien', 'proteine'])],
            ['name' => 'Lentilles', 'category' => 'platPrincipal', 'tags' => json_encode(['vegetarien', 'proteine', 'feculent'])],

            // --- Accompagnements ---
            ['name' => 'Riz', 'category' => 'accompagnement', 'tags' => json_encode(['feculent'])],
            ['name' => 'Pâtes complètes', 'category' => 'accompagnement', 'tags' => json_encode(['feculent'])],
            ['name' => 'Haricots verts', 'category' => 'accompagnement', 'tags' => json_encode(['legume'])],
            ['name' => 'Poêlée de légumes', 'category' => 'accompagnement', 'tags' => json_encode(['legume'])],
            ['name' => 'Salade composée', 'category' => 'accompagnement', 'tags' => json_encode(['legume', 'crudite'])],
            ['name' => 'Pommes de terre', 'category' => 'accompagnement', 'tags' => json_encode(['feculent'])],

            // --- Desserts ---
            ['name' => 'Pomme', 'category' => 'dessert', 'tags' => json_encode(['fruit'])],
            ['name' => 'Yaourt nature', 'category' => 'dessert', 'tags' => json_encode(['produitLaitier'])],
            ['name' => 'Fromage', 'category' => 'dessert', 'tags' => json_encode(['produitLaitier'])],
            ['name' => 'Amandes', 'category' => 'dessert', 'tags' => json_encode(['gras', 'proteine'])],
        ];

        foreach ($foods as $food) {
            FoodItem::create($food);
        }
    }
}
