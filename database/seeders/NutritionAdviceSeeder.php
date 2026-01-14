<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NutritionAdvice;

class NutritionAdviceSeeder extends Seeder
{
    public function run(): void
    {

        $adviceList = [
            [
                'condition_name' => 'SURPOIDS/OBÉSITÉ',
                'foods_to_avoid' => json_encode(['Viande', 'Desserts', 'Fromage', 'Yaourts', 'Lait', 'Viennoises', 'Riz', 'Pâtes']),
                'prophetic_advice_fr' => "jus de fruits / les jus de dattes / l'eau au miel / la poudre des graines de Nigelle Sativa /"
            ],
            [
                'condition_name' => 'DIABÈTE',
                'foods_to_eat' => json_encode(['3 portions de légumes et 2 fruits', '1 produit céréalier complet par jour', 'légumineuses 2 fois par semaine', '2 produits laitiers par jour']),
                'prophetic_advice_fr' => 'Fenugrec / les feuilles d\'Olivier / Le chromonium / al-ithmid / cossus indien /'
            ],
            [
                'condition_name' => 'MALADIE DE CROHN',
                'foods_to_avoid' => json_encode(['Lait entier', 'Yaourt au citron ou céréales', 'Viandes grasses', 'Pain aux céréales', 'Choux', 'Riz', 'Oignons', 'Jus de pruneaux', 'Fruits secs', 'Café', 'thé']),
                'prophetic_advice_fr' => 'la graine de nigelle / le champignon chinois (le chitaké le maitaké le reishi) / le gattilie / la sauge'
            ],
            [
                'condition_name' => 'MALADIE COELIAQUE',
                'foods_to_avoid' => json_encode(['Coucous', 'Pâtes', 'Pain', 'Pizza']),
                'prophetic_advice_fr' => '????'
            ],
            [
                'condition_name' => 'RHUMATISME INFLAMMATOIRE',
                'foods_to_avoid' => json_encode(['Viande rouges', 'Crustacés (crevettes, bulot)', 'Pain blanc', 'Pas de dessert']),
                'prophetic_advice_fr' => 'des Omégas 3 / le collagène / le collagène marin / le glucosamine.'
            ],
            [
                'condition_name' => 'PSORIASIS',
                'foods_to_avoid' => json_encode(['Pas de dessert', 'Pain blanc et viennoiseries', 'Viande rouge']),
                'prophetic_advice_fr' => 'la Camomille (sous forme d\'huile mélangé avec de la vaseline) / la réglisse sous forme de pommade / huile de nigelle /'
            ],
            [
                'condition_name' => 'TROUBLES RENAUX',
                'foods_to_avoid' => json_encode(['Viande', 'Fromages', 'Œufs', 'Céréales (riz, pâtes)']),
                'prophetic_advice_fr' => 'champignon chinois (le riche / cordyceps) / vitamine B 12/ Le ginseng /'
            ],
            [
                'condition_name' => 'INTOLERANCE AU LACTOSE',
                'foods_to_avoid' => json_encode(['Fromage', 'Lait', 'Yaourts']),
                'prophetic_advice_fr' => 'graines de nigelle / Les tisanes de réglisse / du curcuma'
            ],
            [
                'condition_name' => 'ECZÉMA',
                'foods_to_avoid' => json_encode(['Les desserts', 'Viennoiseries', 'Pain', 'Jus de fruits']),
                'foods_to_eat' => json_encode(['Privilégier les légumes et les fruits']),
                'prophetic_advice_fr' => 'L\'aloe Véra / la mucopolysaccharide / La camomille'
            ],
            [
                'condition_name' => 'URTICAIRE',
                'foods_to_avoid' => json_encode(['Le café', 'Fromage', 'Poissons', 'Œufs', 'Noix', 'Fruits de mer', 'Le lait', 'Le blé']),
                'prophetic_advice_fr' => 'la graine de nigelle mélangée au citron et au gingembre sera très efficace / la matricaire, l\'allemande ou encore la partenelle, en tisane / a bromélaïne ou la pectine /la vitamine b5 / la glutamine / en cas de démangeaisons les huiles essentielles d\'eucalyptus et de menthe poivrée, avec un support de crème comme la crème d\'aloe Véra'
            ],
            [
                'condition_name' => 'ASTHME',
                'foods_to_avoid' => json_encode(['Les desserts', 'Jus de fruits', 'Viennoiserie']),
                'prophetic_advice_fr' => 'le miel le pollen ou la propolis / la réglisse'
            ],
            [
                'condition_name' => 'INFECTIONS DIGESTIVES',
                'foods_to_avoid' => json_encode(['Mais', 'Viande rouge', 'Crustacés', 'Poissons']),
                'prophetic_advice_fr' => 'le gingembre, la camomille et les graines de fenouil / charbon végétal / les compliments alimentaires à base d\'artichaut, de pissenlit ou de boldo /'
            ],
            [
                'condition_name' => 'TROUBLES DE LA DIGESTION',
                'foods_to_avoid' => json_encode(['Café', 'Thé', 'Fromages', 'Crudités', 'Choux', 'Tomates', 'Jus de fruits', 'Pain']),
                'prophetic_advice_fr' => 'Miel / le pollen / la taliban / la nigelle / la banane'
            ],
            [
                'condition_name' => 'FATIGUE',
                'foods_to_avoid' => json_encode(['Desserts', 'Viennoiseries', 'Jus de fruits (sauf jus d\'orange)']),
                'foods_to_eat' => json_encode(['Poireaux', 'Asperges', 'Artichauts', 'Bananes', 'Les baies', 'Haricots', 'Légumes', 'Quinoa', 'Pâtes', 'Poissons']),
                'prophetic_advice_fr' => 'les champignons chinois comme le chitaké, le maitaké et le reishi, ou encore le ginseng, le maca, ou l\'ashwaganda'
            ],
            [
                'condition_name' => 'TROUBLES DE L\'HUMEUR',
                'foods_to_avoid' => json_encode(['Desserts', 'Viennoiseries']),
            ],
            [
                'condition_name' => 'INFECTIONS À RÉPÉTITION',
                'foods_to_avoid' => json_encode(['Viandes rouges', 'Viennoiseries', 'Desserts', 'Crevettes', 'Homards', 'Moules', 'Hareng', 'Sardines', 'Maquereau']),
                'prophetic_advice_fr' => 'le Chrysanthellum / infusions de romarin / la prise de cynarine / les feuilles d\'artichaut / gingembre/boire un demi litre de jus de betterave et carotte par jour ajouter de l\'orange et une grande cuillère de miel / L\'huile de foie de morue / la propolis /'
            ],
            [
                'condition_name' => 'TROUBLES CUTANÉS',
                'foods_to_avoid' => json_encode(['Desserts', 'Viennoiseries', 'Lait', 'Yaourts']),
                'prophetic_advice_fr' => 'L\'huile de cade /'
            ],
            [
                'condition_name' => 'DOULEURS ARTICULAIRES',
                'foods_to_avoid' => json_encode(['Blé', 'Orge', 'Seigle', 'Desserts', 'Viennoiseries', 'Viande']),
                'prophetic_advice_fr' => 'l\'harpagophytum / la capsaïcine / la vitamine B6 et le magnésium / de camphre, de gaulthérie ou de thym'
            ],
            [
                'condition_name' => 'MIGRAINES',
                'foods_to_avoid' => json_encode(['Fromage', 'Desserts', 'Viennoiseries']),
                'prophetic_advice_fr' => 'la Camomille / la Camomille Romaine / la Camomille allemande / la Paternelle / costus indien /'
            ],
            [
                'condition_name' => 'HYPERTENSION ARTERIELLE',
                'foods_to_avoid' => json_encode(['Viande rouge', 'Yaourts', 'Lait', 'Desserts', 'Fromages', 'Riz blanc', 'Jus de fruit', 'Banane']),
                'foods_to_eat' => json_encode(['Riz / pâtes sont toujours accompagnés de légumes']),
                'prophetic_advice_fr' => 'le Safran / le fenouil'
            ],
            [
                'condition_name' => 'HYPER CHOLESTÉROLÉMIE',
                'foods_to_avoid' => json_encode(['Pommes de terre', 'Pain', 'Viande', 'Crevettes', 'Langoustines', 'Maquereau']),
                'prophetic_advice_fr' => 'thé ver / la pomme / Les plantes pourront être des alliées, notamment / curcuma, au pissenlit, ou encore au radis noir sous forme de jus ou d\'ampoule / la levure de riz rouge /'
            ],
            [
                'condition_name' => 'HYPER TRIGLYCÉRIDÉMIE',
                'foods_to_avoid' => json_encode(['Viande', 'Pain']),
                'foods_to_eat' => json_encode(['Thé', 'Lait', 'Légumes et fruits', 'Pommes de terre', 'Pâtes', 'Riz', 'Légumes secs']),
            ],
            [
                'condition_name' => 'CARENCES EN VITAMINES/MINÉRAUX',
                'foods_to_avoid' => json_encode(['Pain', 'Fruits secs', 'Le blé', 'Légumineuses', 'Le thé', 'Haricots', 'Les noix', 'Betteraves']),
                'prophetic_advice_fr' => 'vitamine B / en vitamine C, zinc et sélénium / vitamine A / magnésium / vitamine D'
            ],
            [
                'condition_name' => 'TROUBLES DU SOMMEIL',
                'foods_to_avoid' => json_encode(['Café', 'Le thé', 'Trop manger le soir']),
                'prophetic_advice_fr' => '15 graines de nigelles, dans un verre de lait chaud avec une grande cuillère de miel, avant de dormir / la Valerienne /'
            ],
            [
                'condition_name' => 'TROUBLES DU TRANSIT',
                'foods_to_avoid' => json_encode(['Fromage', 'Crudités', 'Tomates', 'Desserts', 'Lait', 'Yaourts', 'Pâtes', 'Pain', 'Semoule', 'Asperges', 'Choux', 'Brocolis', 'Poireaux', 'Artichaut', 'Légumineuse', 'Viande']),
                'foods_to_eat' => json_encode(['Soupe']),
                'prophetic_advice_fr' => 'la passiflore, les graines de fenouil, la camomille ou la mélisse / graines de lin le soir avec un yaourt nature / Les ampoules d\'artichaut ainsi que les gélules de curcuma et de camomille seront des alliés pour favoriser la digestion'
            ],
        ];

        foreach ($adviceList as $advice) {
            NutritionAdvice::create($advice);
        }
    }
}
