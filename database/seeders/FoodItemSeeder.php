<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodItem;

class FoodItemSeeder extends Seeder
{
    public function run(): void
    {
        $foods = [
            // =============================================
            // PETIT DÉJEUNER - FRUITS (Permanents)
            // =============================================
            ['name' => 'Pomme', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '52kcal']],
            ['name' => 'Banane', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '89kcal', 'energie']],
            ['name' => 'Citron', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '29kcal', 'vitamine_c']],
            ['name' => 'Orange', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '47kcal', 'vitamine_c']],
            ['name' => 'Avocat', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '160kcal', 'gras_sain']],
            ['name' => 'Kiwi', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '61kcal', 'vitamine_c']],
            ['name' => 'Poire', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '57kcal']],
            ['name' => 'Ananas', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '50kcal', 'digestion']],
            ['name' => 'Mangue', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'permanent', '60kcal']],

            // PETIT DÉJEUNER - FRUITS (Semi-permanents)
            ['name' => 'Raisin', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'semi_permanent', '67kcal', 'aout_mars']],
            ['name' => 'Fraise', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'semi_permanent', '33kcal', 'mars_septembre']],
            ['name' => 'Melon', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'semi_permanent', '34kcal', 'mai_octobre']],
            ['name' => 'Pastèque', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'semi_permanent', '30kcal', 'juin_septembre', 'hydratation']],
            ['name' => 'Pamplemousse', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'semi_permanent', '42kcal', 'octobre_juin']],
            ['name' => 'Clémentine', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'semi_permanent', '53kcal', 'octobre_mars']],

            // PETIT DÉJEUNER - FRUITS (Saisonniers)
            ['name' => 'Cerise', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '63kcal', 'juin_juillet', 'sommeil']],
            ['name' => 'Pêche', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '40kcal', 'juin_septembre']],
            ['name' => 'Nectarine', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '40kcal', 'juin_septembre']],
            ['name' => 'Abricot', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '48kcal', 'juin_aout']],
            ['name' => 'Prune', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '46kcal', 'aout_septembre']],
            ['name' => 'Mirabelle', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '46kcal', 'aout_septembre']],
            ['name' => 'Figue Fraîche', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '74kcal', 'aout_octobre']],
            ['name' => 'Kaki', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '70kcal', 'octobre_janvier']],
            ['name' => 'Grenade', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '83kcal', 'septembre_janvier', 'antioxydant']],
            ['name' => 'Litchi', 'category' => 'petitDejeuner', 'tags' => ['fruit', 'saisonnier', '66kcal', 'decembre_janvier']],

            // PETIT DÉJEUNER - JUS DE FRUITS
            ['name' => 'Jus de Pomme', 'category' => 'petitDejeuner', 'tags' => ['jus', 'permanent', '45kcal']],
            ['name' => 'Jus d\'Orange', 'category' => 'petitDejeuner', 'tags' => ['jus', 'permanent', '45kcal', 'vitamine_c']],
            ['name' => 'Jus d\'Ananas', 'category' => 'petitDejeuner', 'tags' => ['jus', 'permanent', '50kcal']],
            ['name' => 'Jus Multi-fruits', 'category' => 'petitDejeuner', 'tags' => ['jus', 'permanent', '48kcal']],
            ['name' => 'Jus de Raisin', 'category' => 'petitDejeuner', 'tags' => ['jus', 'permanent', '65kcal']],
            ['name' => 'Jus de Citron', 'category' => 'petitDejeuner', 'tags' => ['jus', 'permanent', '25kcal']],
            ['name' => 'Jus de Tomate', 'category' => 'petitDejeuner', 'tags' => ['jus', 'permanent', '18kcal']],
            ['name' => 'Eau de Coco', 'category' => 'petitDejeuner', 'tags' => ['jus', 'permanent', '19kcal', 'isotonique', 'hydratation']],

            // PETIT DÉJEUNER - PAINS
            ['name' => 'Baguette Tradition', 'category' => 'petitDejeuner', 'tags' => ['pain', 'permanent', '250kcal']],
            ['name' => 'Pain de Mie', 'category' => 'petitDejeuner', 'tags' => ['pain', 'permanent', '270kcal']],
            ['name' => 'Pain de Campagne', 'category' => 'petitDejeuner', 'tags' => ['pain', 'permanent', '245kcal']],
            ['name' => 'Pain aux Céréales', 'category' => 'petitDejeuner', 'tags' => ['pain', 'permanent', '280kcal', 'fibres']],
            ['name' => 'Pain Complet', 'category' => 'petitDejeuner', 'tags' => ['pain', 'permanent', '235kcal', 'fibres']],
            ['name' => 'Pain Pita', 'category' => 'petitDejeuner', 'tags' => ['pain', 'permanent', '260kcal']],
            ['name' => 'Biscottes', 'category' => 'petitDejeuner', 'tags' => ['pain', 'permanent', '390kcal']],
            ['name' => 'Pain de Seigle', 'category' => 'petitDejeuner', 'tags' => ['pain', 'semi_permanent', '230kcal', 'fibres']],
            ['name' => 'Pain d\'Épeautre', 'category' => 'petitDejeuner', 'tags' => ['pain', 'semi_permanent', '260kcal']],
            ['name' => 'Ciabatta', 'category' => 'petitDejeuner', 'tags' => ['pain', 'semi_permanent', '265kcal']],

            // PETIT DÉJEUNER - BEURRES & TARTINABLES
            ['name' => 'Beurre Doux', 'category' => 'petitDejeuner', 'tags' => ['beurre', 'permanent', 'gras']],
            ['name' => 'Beurre Demi-Sel', 'category' => 'petitDejeuner', 'tags' => ['beurre', 'permanent', 'gras']],
            ['name' => 'Beurre de Baratte', 'category' => 'petitDejeuner', 'tags' => ['beurre', 'artisanal', 'gras']],
            ['name' => 'Confiture', 'category' => 'petitDejeuner', 'tags' => ['sucre', 'permanent']],
            ['name' => 'Gelée de Pomme', 'category' => 'petitDejeuner', 'tags' => ['sucre', 'permanent']],
            ['name' => 'Beurre de Cacahuète', 'category' => 'petitDejeuner', 'tags' => ['gras', 'proteine', 'permanent']],

            // PETIT DÉJEUNER - MIELS
            ['name' => 'Miel de Fleurs', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'energie']],
            ['name' => 'Miel de Thym', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'antiseptique', 'respiratoire']],
            ['name' => 'Miel d\'Eucalyptus', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'antiseptique', 'respiratoire']],
            ['name' => 'Miel de Lavande', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'calmant', 'sommeil']],
            ['name' => 'Miel d\'Acacia', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'digestion']],
            ['name' => 'Miel de Manuka', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'antibacterien']],
            ['name' => 'Miel de Châtaignier', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'circulation']],
            ['name' => 'Miel de Sarrasin', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'vasculaire']],
            ['name' => 'Miel de Forêt', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'antioxydant']],
            ['name' => 'Gelée Royale', 'category' => 'petitDejeuner', 'tags' => ['miel', 'permanent', 'energie', 'immunite']],

            // PETIT DÉJEUNER - PRODUITS LAITIERS & BOISSONS
            ['name' => 'Lait', 'category' => 'petitDejeuner', 'tags' => ['produitLaitier', 'permanent']],
            ['name' => 'Lait d\'Amande', 'category' => 'petitDejeuner', 'tags' => ['vegetal', 'permanent', 'sans_lactose']],
            ['name' => 'Lait de Coco', 'category' => 'petitDejeuner', 'tags' => ['vegetal', 'permanent', 'sans_lactose']],
            ['name' => 'Yaourt Nature', 'category' => 'petitDejeuner', 'tags' => ['produitLaitier', 'permanent', 'proteine']],
            ['name' => 'Yaourt Grec', 'category' => 'petitDejeuner', 'tags' => ['produitLaitier', 'permanent', 'proteine']],
            ['name' => 'Kéfir', 'category' => 'petitDejeuner', 'tags' => ['produitLaitier', 'fermente', 'probiotique']],
            ['name' => 'Fromage Blanc', 'category' => 'petitDejeuner', 'tags' => ['produitLaitier', 'permanent', 'proteine']],
            ['name' => 'Café', 'category' => 'petitDejeuner', 'tags' => ['boisson', 'permanent']],
            ['name' => 'Thé', 'category' => 'petitDejeuner', 'tags' => ['boisson', 'permanent']],
            ['name' => 'Thé Vert', 'category' => 'petitDejeuner', 'tags' => ['boisson', 'permanent', 'antioxydant']],
            ['name' => 'Chocolat Chaud', 'category' => 'petitDejeuner', 'tags' => ['boisson', 'sucre', 'permanent']],

            // PETIT DÉJEUNER - CÉRÉALES & OEUFS
            ['name' => 'Flocons d\'Avoine', 'category' => 'petitDejeuner', 'tags' => ['cereale', 'permanent', 'fibres', 'energie']],
            ['name' => 'Muesli', 'category' => 'petitDejeuner', 'tags' => ['cereale', 'permanent', 'fibres']],
            ['name' => 'Granola', 'category' => 'petitDejeuner', 'tags' => ['cereale', 'permanent', 'energie']],
            ['name' => 'Oeufs', 'category' => 'petitDejeuner', 'tags' => ['oeuf', 'proteine', 'permanent']],
            ['name' => 'Oeufs Brouillés', 'category' => 'petitDejeuner', 'tags' => ['oeuf', 'proteine', 'permanent']],
            ['name' => 'Omelette', 'category' => 'petitDejeuner', 'tags' => ['oeuf', 'proteine', 'permanent']],

            // =============================================
            // PLATS PRINCIPAUX - LÉGUMINEUSES
            // =============================================
            ['name' => 'Lentilles Vertes', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'permanent', 'proteine', '115kcal']],
            ['name' => 'Lentilles Corail', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'permanent', 'proteine', '110kcal', 'digestion_facile']],
            ['name' => 'Lentilles Beluga', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'semi_permanent', 'proteine', '115kcal']],
            ['name' => 'Pois Chiches', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'permanent', 'proteine', '160kcal']],
            ['name' => 'Haricots Blancs', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'permanent', 'proteine', '110kcal']],
            ['name' => 'Haricots Rouges', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'permanent', 'proteine', '125kcal']],
            ['name' => 'Haricots Noirs', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'permanent', 'proteine', '130kcal']],
            ['name' => 'Pois Cassés', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'permanent', 'proteine', '120kcal']],
            ['name' => 'Fèves', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'semi_permanent', 'proteine', '90kcal', 'avril_septembre']],
            ['name' => 'Petits Pois', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'semi_permanent', 'proteine', '80kcal', 'mai_juillet']],
            ['name' => 'Flageolets', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'semi_permanent', 'proteine', '105kcal']],
            ['name' => 'Edamame', 'category' => 'platPrincipal', 'tags' => ['legumineuse', 'saisonnier', 'proteine', '120kcal']],

            // PLATS PRINCIPAUX - VIANDES
            ['name' => 'Filet de Poulet', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'permanent', 'maigre']],
            ['name' => 'Cuisses de Poulet', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'permanent']],
            ['name' => 'Escalope de Dinde', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'permanent', 'maigre']],
            ['name' => 'Steak de Boeuf', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'permanent', 'fer']],
            ['name' => 'Steak Haché', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'permanent']],
            ['name' => 'Escalope de Veau', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'permanent']],
            ['name' => 'Côtelette d\'Agneau', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'semi_permanent']],
            ['name' => 'Magret de Canard', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'semi_permanent']],
            ['name' => 'Lapin', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'semi_permanent', 'maigre']],
            ['name' => 'Saucisses', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'semi_permanent']],
            ['name' => 'Foie', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'permanent', 'fer', 'vitamine_a']],
            ['name' => 'Boudin Noir', 'category' => 'platPrincipal', 'tags' => ['viande', 'proteine', 'permanent', 'fer']],

            // PLATS PRINCIPAUX - POISSONS
            ['name' => 'Saumon', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'permanent', 'omega3']],
            ['name' => 'Truite', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'permanent', 'omega3']],
            ['name' => 'Cabillaud', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'permanent', 'maigre']],
            ['name' => 'Bar (Loup)', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'permanent']],
            ['name' => 'Daurade', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'permanent']],
            ['name' => 'Thon', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'saisonnier', 'omega3']],
            ['name' => 'Sardine', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'saisonnier', 'omega3', 'calcium']],
            ['name' => 'Maquereau', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'saisonnier', 'omega3']],
            ['name' => 'Sole', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'semi_permanent', 'maigre']],
            ['name' => 'Lieu Noir', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'semi_permanent']],
            ['name' => 'Merlan', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'semi_permanent', 'maigre']],
            ['name' => 'Lotte', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'semi_permanent']],
            ['name' => 'Raie', 'category' => 'platPrincipal', 'tags' => ['poisson', 'proteine', 'semi_permanent']],
            ['name' => 'Crevettes', 'category' => 'platPrincipal', 'tags' => ['crustace', 'proteine', 'permanent', '95kcal']],
            ['name' => 'Moules', 'category' => 'platPrincipal', 'tags' => ['crustace', 'proteine', 'saisonnier', 'fer']],
            ['name' => 'Coquilles St-Jacques', 'category' => 'platPrincipal', 'tags' => ['crustace', 'proteine', 'saisonnier']],

            // PLATS PRINCIPAUX - VÉGÉTARIEN
            ['name' => 'Tofu', 'category' => 'platPrincipal', 'tags' => ['vegetarien', 'proteine', 'permanent']],
            ['name' => 'Tempeh', 'category' => 'platPrincipal', 'tags' => ['vegetarien', 'proteine', 'permanent', 'fermente']],
            ['name' => 'Seitan', 'category' => 'platPrincipal', 'tags' => ['vegetarien', 'proteine', 'permanent']],

            // =============================================
            // ACCOMPAGNEMENTS - LÉGUMES (Permanents)
            // =============================================
            ['name' => 'Pomme de Terre', 'category' => 'accompagnement', 'tags' => ['legume', 'feculent', 'permanent', '77kcal']],
            ['name' => 'Carotte', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', '41kcal', 'vitamine_a']],
            ['name' => 'Oignon', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', '40kcal']],
            ['name' => 'Ail', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', 'antiseptique']],
            ['name' => 'Champignons de Paris', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', '22kcal']],
            ['name' => 'Salade Verte', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', '15kcal', 'crudite']],
            ['name' => 'Concombre', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', '15kcal', 'crudite', 'hydratation']],
            ['name' => 'Tomate', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', '18kcal', 'antioxydant']],
            ['name' => 'Poireau', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', '61kcal']],

            // ACCOMPAGNEMENTS - LÉGUMES (Semi-permanents)
            ['name' => 'Aubergine', 'category' => 'accompagnement', 'tags' => ['legume', 'semi_permanent', '25kcal', 'mars_octobre']],
            ['name' => 'Poivron', 'category' => 'accompagnement', 'tags' => ['legume', 'semi_permanent', '30kcal', 'avril_novembre', 'vitamine_c']],
            ['name' => 'Courgette', 'category' => 'accompagnement', 'tags' => ['legume', 'semi_permanent', '17kcal', 'avril_octobre']],
            ['name' => 'Brocoli', 'category' => 'accompagnement', 'tags' => ['legume', 'semi_permanent', '34kcal', 'mai_novembre', 'vitamine_c']],
            ['name' => 'Chou-fleur', 'category' => 'accompagnement', 'tags' => ['legume', 'semi_permanent', '25kcal', 'septembre_mai']],
            ['name' => 'Patate Douce', 'category' => 'accompagnement', 'tags' => ['legume', 'feculent', 'semi_permanent', '86kcal', 'septembre_mars']],
            ['name' => 'Endive', 'category' => 'accompagnement', 'tags' => ['legume', 'semi_permanent', '17kcal', 'octobre_mai']],
            ['name' => 'Haricots Verts', 'category' => 'accompagnement', 'tags' => ['legume', 'semi_permanent', '31kcal', 'mai_octobre']],

            // ACCOMPAGNEMENTS - LÉGUMES (Saisonniers)
            ['name' => 'Asperges', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '20kcal', 'avril_juin']],
            ['name' => 'Courge', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '26kcal', 'septembre_decembre']],
            ['name' => 'Potiron', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '26kcal', 'septembre_decembre']],
            ['name' => 'Potimarron', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '26kcal', 'septembre_decembre']],
            ['name' => 'Artichaut', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '47kcal', 'mai_juillet']],
            ['name' => 'Épinards Frais', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '23kcal', 'mars_mai', 'fer', 'magnesium']],
            ['name' => 'Betterave', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '43kcal', 'juin_aout', 'antioxydant']],
            ['name' => 'Topinambour', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '73kcal', 'novembre_fevrier']],
            ['name' => 'Radis', 'category' => 'accompagnement', 'tags' => ['legume', 'saisonnier', '16kcal', 'mars_juin', 'crudite']],
            ['name' => 'Chou', 'category' => 'accompagnement', 'tags' => ['legume', 'permanent', '25kcal']],

            // ACCOMPAGNEMENTS - CÉRÉALES
            ['name' => 'Riz Blanc', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'permanent', '350kcal']],
            ['name' => 'Riz Complet', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'semi_permanent', '345kcal', 'fibres']],
            ['name' => 'Riz Basmati', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'permanent', '350kcal']],
            ['name' => 'Pâtes Blanches', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'permanent', '355kcal']],
            ['name' => 'Pâtes Complètes', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'semi_permanent', '335kcal', 'fibres']],
            ['name' => 'Semoule', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'permanent', '350kcal']],
            ['name' => 'Quinoa', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'semi_permanent', '365kcal', 'proteine']],
            ['name' => 'Boulgour', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'semi_permanent', '345kcal', 'fibres']],
            ['name' => 'Épeautre', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'semi_permanent', '340kcal']],
            ['name' => 'Sarrasin', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'saisonnier', '340kcal', 'sans_gluten']],
            ['name' => 'Millet', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'saisonnier', '355kcal']],
            ['name' => 'Blé Ebly', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'permanent', '340kcal']],
            ['name' => 'Polenta', 'category' => 'accompagnement', 'tags' => ['cereale', 'feculent', 'permanent', '330kcal']],

            // =============================================
            // DESSERTS
            // =============================================
            ['name' => 'Yaourt aux Fruits', 'category' => 'dessert', 'tags' => ['produitLaitier', 'permanent']],
            ['name' => 'Compote de Pommes', 'category' => 'dessert', 'tags' => ['fruit', 'permanent']],
            ['name' => 'Fromage', 'category' => 'dessert', 'tags' => ['produitLaitier', 'permanent']],
            ['name' => 'Chèvre Frais', 'category' => 'dessert', 'tags' => ['produitLaitier', 'permanent']],
            ['name' => 'Feta', 'category' => 'dessert', 'tags' => ['produitLaitier', 'permanent', '260kcal']],
            ['name' => 'Amandes', 'category' => 'dessert', 'tags' => ['oleagineux', 'permanent', 'proteine', 'magnesium']],
            ['name' => 'Noix', 'category' => 'dessert', 'tags' => ['oleagineux', 'permanent', 'omega3']],
            ['name' => 'Noisettes', 'category' => 'dessert', 'tags' => ['oleagineux', 'permanent']],
            ['name' => 'Dattes', 'category' => 'dessert', 'tags' => ['fruit_sec', 'permanent', 'energie']],
            ['name' => 'Figues Sèches', 'category' => 'dessert', 'tags' => ['fruit_sec', 'permanent', 'fibres']],
            ['name' => 'Abricots Secs', 'category' => 'dessert', 'tags' => ['fruit_sec', 'permanent', 'potassium']],
            ['name' => 'Pruneaux', 'category' => 'dessert', 'tags' => ['fruit_sec', 'permanent', 'digestion']],
            ['name' => 'Chocolat Noir', 'category' => 'dessert', 'tags' => ['sucre', 'permanent', 'antioxydant', 'magnesium']],

            // =============================================
            // HUILES
            // =============================================
            ['name' => 'Huile d\'Olive', 'category' => 'accompagnement', 'tags' => ['huile', 'permanent', 'omega9']],
            ['name' => 'Huile de Colza', 'category' => 'accompagnement', 'tags' => ['huile', 'permanent', 'omega3']],
            ['name' => 'Huile de Noix', 'category' => 'accompagnement', 'tags' => ['huile', 'permanent', 'omega3']],
            ['name' => 'Huile de Sésame', 'category' => 'accompagnement', 'tags' => ['huile', 'permanent', 'mineraux']],
            ['name' => 'Huile de Coco', 'category' => 'accompagnement', 'tags' => ['huile', 'permanent']],
            ['name' => 'Huile de Nigelle', 'category' => 'accompagnement', 'tags' => ['huile', 'permanent', 'prophetic', 'immunite']],

            // =============================================
            // SOUPES (pour dîner)
            // =============================================
            ['name' => 'Soupe de Légumes', 'category' => 'platPrincipal', 'tags' => ['soupe', 'permanent', 'legume']],
            ['name' => 'Velouté de Tomate', 'category' => 'platPrincipal', 'tags' => ['soupe', 'permanent', 'legume']],
            ['name' => 'Velouté de Potiron', 'category' => 'platPrincipal', 'tags' => ['soupe', 'permanent', 'legume']],
            ['name' => 'Soupe de Poisson', 'category' => 'platPrincipal', 'tags' => ['soupe', 'permanent', 'poisson']],
            ['name' => 'Velouté de Champignons', 'category' => 'platPrincipal', 'tags' => ['soupe', 'semi_permanent', 'legume']],
            ['name' => 'Velouté de Châtaigne', 'category' => 'platPrincipal', 'tags' => ['soupe', 'saisonnier', 'novembre_janvier']],
        ];

        foreach ($foods as $food) {
            FoodItem::create($food);
        }
    }
}
