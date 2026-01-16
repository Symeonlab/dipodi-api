<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NutritionAdvice;

class NutritionAdviceSeeder extends Seeder
{
    public function run(): void
    {
        // Sport Nutrition - Football
        $footballAdvice = [
            [
                'condition_name' => 'FATIGUE / BAISSE D\'ÉNERGIE (FOOTBALL)',
                'foods_to_eat' => [
                    'Banane, dattes, orange',
                    'Épinards, betterave',
                    'Lentilles',
                    'Avoine, riz',
                    'Bœuf, poulet',
                    'Sardine'
                ],
                'prophetic_advice_fr' => 'Vitamines B1, B6, B12, C | Oligo-éléments: Fer, Mg | Tisanes: Ginseng | Huiles: Olive | Miel: Fleurs | Autres: Sel non raffiné'
            ],
            [
                'condition_name' => 'CRAMPES MUSCULAIRES / SPASMES (FOOTBALL)',
                'foods_to_eat' => [
                    'Banane, abricots secs',
                    'Brocoli, épinards',
                    'Haricots rouges',
                    'Riz complet',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg, Na, K | Tisanes: Ortie | Huiles: Amande | Miel: Tilleul | Autres: Eau riche en Mg'
            ],
            [
                'condition_name' => 'BLESSURES MUSCULAIRES / ÉLONGATIONS (FOOTBALL)',
                'foods_to_eat' => [
                    'Ananas, fruits rouges',
                    'Curcuma, ail',
                    'Quinoa',
                    'Dinde, œufs',
                    'Thon, saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines C, E | Oligo-éléments: Zinc | Tisanes: Gingembre | Huiles: Olive | Autres: Curcuma + poivre'
            ],
            [
                'condition_name' => 'ENTORSES / INFLAMMATION ARTICULAIRE (FOOTBALL)',
                'foods_to_eat' => [
                    'Cerise, grenade',
                    'Chou, brocoli',
                    'Maquereau'
                ],
                'prophetic_advice_fr' => 'Vitamines D, K, C | Oligo-éléments: Cuivre, Zn | Tisanes: Harpagophytum | Huiles: Colza | Autres: Gelée royale'
            ],
            [
                'condition_name' => 'TENDINITES (FOOTBALL)',
                'foods_to_eat' => [
                    'Ananas',
                    'Épinards',
                    'Sardine'
                ],
                'prophetic_advice_fr' => 'Vitamine C | Oligo-éléments: Silicium | Tisanes: Prêle | Huiles: Noix | Autres: Collagène'
            ],
            [
                'condition_name' => 'RÉCUPÉRATION POST-MATCH (FOOTBALL)',
                'foods_to_eat' => [
                    'Banane, raisin',
                    'Betterave',
                    'Riz blanc',
                    'Poulet, œufs',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines B, C | Oligo-éléments: Mg | Tisanes: Rooibos | Huiles: Olive | Miel: Acacia | Autres: Lait fermenté'
            ],
            [
                'condition_name' => 'PERTE MUSCULAIRE / CATABOLISME (FOOTBALL)',
                'foods_to_eat' => [
                    'Lentilles',
                    'Quinoa',
                    'Bœuf, œufs',
                    'Thon'
                ],
                'prophetic_advice_fr' => 'Vitamines B6, D | Oligo-éléments: Zinc | Huiles: Olive | Autres: Collagène'
            ],
            [
                'condition_name' => 'DÉSHYDRATATION AIGUË (FOOTBALL)',
                'foods_to_eat' => [
                    'Pastèque',
                    'Concombre'
                ],
                'prophetic_advice_fr' => 'Oligo-éléments: Na, K | Miel: Fleurs | Autres: Eau bicarbonatée'
            ],
            [
                'condition_name' => 'DÉSHYDRATATION CHRONIQUE / CRAMPES (FOOTBALL)',
                'foods_to_eat' => [
                    'Banane',
                    'Épinards'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg, Na | Tisanes: Ortie | Autres: Sel non raffiné'
            ],
            [
                'condition_name' => 'TROUBLES DIGESTIFS VOYAGES (FOOTBALL)',
                'foods_to_eat' => [
                    'Banane',
                    'Carotte',
                    'Lentilles corail',
                    'Riz blanc',
                    'Poulet',
                    'Cabillaud'
                ],
                'prophetic_advice_fr' => 'Vitamine B1 | Oligo-éléments: Zinc | Tisanes: Fenouil | Autres: Gingembre'
            ],
            [
                'condition_name' => 'INFLAMMATION INTESTINALE / DIARRHÉE (FOOTBALL)',
                'foods_to_eat' => [
                    'Pomme cuite',
                    'Courgette',
                    'Riz blanc',
                    'Dinde'
                ],
                'prophetic_advice_fr' => 'Vitamines A, C | Oligo-éléments: Zn | Tisanes: Camomille | Autres: Bouillon'
            ],
            [
                'condition_name' => 'STRESS / TROUBLES DU SOMMEIL (FOOTBALL)',
                'foods_to_eat' => [
                    'Cerise, banane',
                    'Avoine'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg | Tisanes: Tilleul | Huiles: Noix | Miel: Tilleul | Autres: Chocolat noir'
            ],
            [
                'condition_name' => 'BLESSURES OSSEUSES / FRACTURES (FOOTBALL)',
                'foods_to_eat' => [
                    'Kiwi',
                    'Brocoli',
                    'Œufs',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines D, K, C | Oligo-éléments: Ca, Zn | Tisanes: Ortie | Huiles: Colza | Autres: Bouillon d\'os'
            ],
            [
                'condition_name' => 'BLESSURES LIGAMENTAIRES / MÉNISQUE (FOOTBALL)',
                'foods_to_eat' => [
                    'Agrumes',
                    'Chou',
                    'Bœuf',
                    'Sardine'
                ],
                'prophetic_advice_fr' => 'Vitamine C | Oligo-éléments: Cuivre, Zn | Tisanes: Prêle | Huiles: Noix | Autres: Collagène'
            ],
            [
                'condition_name' => 'ANÉMIE / DÉFICIT EN FER (FOOTBALL)',
                'foods_to_eat' => [
                    'Orange',
                    'Épinards',
                    'Lentilles',
                    'Quinoa',
                    'Foie, boudin'
                ],
                'prophetic_advice_fr' => 'Vitamines C, B12 | Oligo-éléments: Fer | Autres: Associer vitamine C'
            ],
            [
                'condition_name' => 'SURCHARGE ARTICULAIRE CHRONIQUE (FOOTBALL)',
                'foods_to_eat' => [
                    'Fruits rouges',
                    'Curcuma',
                    'Maquereau'
                ],
                'prophetic_advice_fr' => 'Vitamine D | Oligo-éléments: Zn | Tisanes: Harpagophytum | Huiles: Olive | Autres: Curcuma'
            ],
            [
                'condition_name' => 'SURENTRAÎNEMENT / FATIGUE CHRONIQUE (FOOTBALL)',
                'foods_to_eat' => [
                    'Banane',
                    'Betterave',
                    'Pois chiches',
                    'Avoine',
                    'Œufs',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines B, C | Oligo-éléments: Mg | Tisanes: Ashwagandha | Huiles: Colza | Autres: Gelée royale'
            ],
            [
                'condition_name' => 'OBÉSITÉ / PRISE DE POIDS EXCESSIVE (FOOTBALL)',
                'foods_to_eat' => [
                    'Pomme',
                    'Légumes verts',
                    'Lentilles',
                    'Quinoa',
                    'Viande maigre',
                    'Poisson blanc'
                ],
                'prophetic_advice_fr' => 'Tisanes: Thé vert | Huiles: Olive | Autres: Épices'
            ],
        ];

        // Sport Nutrition - Fitness
        $fitnessAdvice = [
            [
                'condition_name' => 'MANQUE D\'ÉNERGIE / FATIGUE (FITNESS)',
                'foods_to_eat' => [
                    'Banane, dattes',
                    'Épinards, betterave',
                    'Lentilles',
                    'Avoine, riz',
                    'Poulet, œufs',
                    'Sardine'
                ],
                'prophetic_advice_fr' => 'Vitamines B, C | Oligo-éléments: Fer, Mg | Tisanes: Ginseng | Huiles: Olive | Miel: Fleurs | Autres: Sel non raffiné'
            ],
            [
                'condition_name' => 'PERTE DE POIDS / MINCEUR (FITNESS)',
                'foods_to_eat' => [
                    'Pomme, fruits rouges',
                    'Courgette, brocoli',
                    'Lentilles',
                    'Quinoa',
                    'Viande maigre',
                    'Poisson blanc'
                ],
                'prophetic_advice_fr' => 'Tisanes: Thé vert | Huiles: Olive | Autres: Épices'
            ],
            [
                'condition_name' => 'SÈCHE / DÉFINITION MUSCULAIRE (FITNESS)',
                'foods_to_eat' => [
                    'Fruits rouges',
                    'Légumes verts',
                    'Pois chiches',
                    'Riz basmati',
                    'Dinde',
                    'Thon'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Zinc | Tisanes: Thé vert | Huiles: Colza | Autres: Curcuma'
            ],
            [
                'condition_name' => 'PRISE DE MUSCLE (FITNESS)',
                'foods_to_eat' => [
                    'Banane',
                    'Patate douce',
                    'Lentilles',
                    'Riz, avoine',
                    'Bœuf, œufs',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines B6, D | Oligo-éléments: Zinc | Huiles: Olive | Autres: Fromage blanc'
            ],
            [
                'condition_name' => 'PERTE DE TONICITÉ / RELÂCHEMENT (FITNESS)',
                'foods_to_eat' => [
                    'Agrumes',
                    'Chou, brocoli',
                    'Quinoa',
                    'Œufs',
                    'Sardine'
                ],
                'prophetic_advice_fr' => 'Vitamines C, E | Oligo-éléments: Zinc | Tisanes: Ortie | Huiles: Noix | Autres: Collagène'
            ],
            [
                'condition_name' => 'COURBATURES / RÉCUPÉRATION (FITNESS)',
                'foods_to_eat' => [
                    'Cerise, banane',
                    'Betterave',
                    'Riz blanc',
                    'Poulet',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines B, C | Oligo-éléments: Mg | Tisanes: Rooibos | Huiles: Olive | Miel: Acacia | Autres: Lait fermenté'
            ],
            [
                'condition_name' => 'CRAMPES / SPASMES (FITNESS)',
                'foods_to_eat' => [
                    'Banane',
                    'Épinards',
                    'Haricots rouges',
                    'Riz complet',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg, K | Tisanes: Ortie | Huiles: Amande | Autres: Eau riche en Mg'
            ],
            [
                'condition_name' => 'STRESS / FATIGUE MENTALE (FITNESS)',
                'foods_to_eat' => [
                    'Banane',
                    'Avoine'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg | Tisanes: Tilleul | Huiles: Noix | Miel: Tilleul | Autres: Chocolat noir'
            ],
            [
                'condition_name' => 'TROUBLES DU SOMMEIL (FITNESS)',
                'foods_to_eat' => [
                    'Cerise',
                    'Flocons d\'avoine'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg | Tisanes: Camomille | Huiles: Noix | Miel: Tilleul | Autres: Lait chaud'
            ],
            [
                'condition_name' => 'BALLONNEMENTS / DIGESTION DIFFICILE (FITNESS)',
                'foods_to_eat' => [
                    'Banane',
                    'Courgette',
                    'Lentilles corail',
                    'Riz blanc',
                    'Poulet',
                    'Cabillaud'
                ],
                'prophetic_advice_fr' => 'Vitamine B1 | Oligo-éléments: Zinc | Tisanes: Fenouil | Autres: Gingembre'
            ],
            [
                'condition_name' => 'INFLAMMATION CHRONIQUE (FITNESS)',
                'foods_to_eat' => [
                    'Fruits rouges',
                    'Curcuma',
                    'Maquereau'
                ],
                'prophetic_advice_fr' => 'Vitamines D, C | Oligo-éléments: Zinc | Tisanes: Harpagophytum | Huiles: Olive | Autres: Curcuma + poivre'
            ],
            [
                'condition_name' => 'ARTICULATIONS SENSIBLES (FITNESS)',
                'foods_to_eat' => [
                    'Kiwi',
                    'Brocoli',
                    'Œufs',
                    'Sardine'
                ],
                'prophetic_advice_fr' => 'Vitamines D, K | Oligo-éléments: Cuivre | Tisanes: Prêle | Huiles: Colza | Autres: Bouillon'
            ],
            [
                'condition_name' => 'DÉSHYDRATATION / RÉTENTION (FITNESS)',
                'foods_to_eat' => [
                    'Pastèque',
                    'Concombre'
                ],
                'prophetic_advice_fr' => 'Oligo-éléments: Na, K | Autres: Eau minérale'
            ],
        ];

        // Sport Nutrition - Padel
        $padelAdvice = [
            [
                'condition_name' => 'MANQUE D\'ÉNERGIE EN MATCH (PADEL)',
                'foods_to_eat' => [
                    'Banane, dattes',
                    'Betterave',
                    'Lentilles',
                    'Riz, avoine',
                    'Poulet',
                    'Sardine'
                ],
                'prophetic_advice_fr' => 'Vitamines B1, B6, C | Oligo-éléments: Fer, Mg | Tisanes: Maté léger | Huiles: Olive | Miel: Fleurs | Autres: Sel non raffiné'
            ],
            [
                'condition_name' => 'BAISSE D\'INTENSITÉ FIN DE MATCH (PADEL)',
                'foods_to_eat' => [
                    'Raisin, orange',
                    'Épinards',
                    'Pois chiches',
                    'Semoule',
                    'Œufs',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines B, C | Oligo-éléments: Mg | Tisanes: Rooibos | Huiles: Colza | Miel: Acacia | Autres: Eau bicarbonatée'
            ],
            [
                'condition_name' => 'CRAMPES MUSCULAIRES (PADEL)',
                'foods_to_eat' => [
                    'Banane, abricots secs',
                    'Brocoli',
                    'Haricots rouges',
                    'Riz complet',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg, K, Na | Tisanes: Ortie | Huiles: Amande | Autres: Eau riche en Mg'
            ],
            [
                'condition_name' => 'DÉSHYDRATATION CHALEUR INDOOR (PADEL)',
                'foods_to_eat' => [
                    'Pastèque',
                    'Concombre'
                ],
                'prophetic_advice_fr' => 'Oligo-éléments: Na, K | Miel: Fleurs | Autres: Boisson électrolyte'
            ],
            [
                'condition_name' => 'FATIGUE NERVEUSE / STRESS MATCH (PADEL)',
                'foods_to_eat' => [
                    'Banane',
                    'Avoine'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg | Tisanes: Tilleul | Huiles: Noix | Miel: Tilleul | Autres: Chocolat noir'
            ],
            [
                'condition_name' => 'TROUBLES DU SOMMEIL TOURNOIS (PADEL)',
                'foods_to_eat' => [
                    'Cerise',
                    'Flocons avoine'
                ],
                'prophetic_advice_fr' => 'Vitamine B6 | Oligo-éléments: Mg | Tisanes: Camomille | Huiles: Noix | Miel: Tilleul | Autres: Lait chaud'
            ],
            [
                'condition_name' => 'DOULEURS ÉPAULES / COUDES TENDINITES (PADEL)',
                'foods_to_eat' => [
                    'Ananas',
                    'Épinards',
                    'Sardine'
                ],
                'prophetic_advice_fr' => 'Vitamine C | Oligo-éléments: Silicium, Zn | Tisanes: Prêle | Huiles: Noix | Autres: Collagène'
            ],
            [
                'condition_name' => 'GENOUX / CHEVILLES SOLLICITÉS (PADEL)',
                'foods_to_eat' => [
                    'Kiwi',
                    'Brocoli',
                    'Œufs',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines D, K, C | Oligo-éléments: Ca, Zn | Tisanes: Ortie | Huiles: Colza | Autres: Bouillon d\'os'
            ],
            [
                'condition_name' => 'INFLAMMATION ARTICULAIRE CHRONIQUE (PADEL)',
                'foods_to_eat' => [
                    'Fruits rouges',
                    'Curcuma',
                    'Maquereau'
                ],
                'prophetic_advice_fr' => 'Vitamines D, C | Oligo-éléments: Zn | Tisanes: Harpagophytum | Huiles: Olive | Autres: Curcuma + poivre'
            ],
            [
                'condition_name' => 'RÉCUPÉRATION POST-MATCH (PADEL)',
                'foods_to_eat' => [
                    'Banane, raisin',
                    'Betterave',
                    'Riz blanc',
                    'Poulet, œufs',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines B, C | Oligo-éléments: Mg | Tisanes: Rooibos | Huiles: Olive | Miel: Acacia | Autres: Lait fermenté'
            ],
            [
                'condition_name' => 'SURENTRAÎNEMENT / FATIGUE CHRONIQUE (PADEL)',
                'foods_to_eat' => [
                    'Banane',
                    'Betterave',
                    'Pois chiches',
                    'Avoine',
                    'Œufs',
                    'Saumon'
                ],
                'prophetic_advice_fr' => 'Vitamines B, C | Oligo-éléments: Mg | Tisanes: Ashwagandha | Huiles: Colza | Autres: Gelée royale'
            ],
            [
                'condition_name' => 'PERTE MUSCULAIRE / CATABOLISME (PADEL)',
                'foods_to_eat' => [
                    'Lentilles',
                    'Quinoa',
                    'Bœuf, œufs',
                    'Thon'
                ],
                'prophetic_advice_fr' => 'Vitamines B6, D | Oligo-éléments: Zn | Huiles: Olive | Autres: Collagène'
            ],
            [
                'condition_name' => 'PRISE DE POIDS HORS SAISON (PADEL)',
                'foods_to_eat' => [
                    'Pomme',
                    'Légumes verts',
                    'Lentilles',
                    'Quinoa',
                    'Viande maigre',
                    'Poisson blanc'
                ],
                'prophetic_advice_fr' => 'Tisanes: Thé vert | Huiles: Olive | Autres: Épices'
            ],
            [
                'condition_name' => 'DIGESTION DIFFICILE MATCHS RAPPROCHÉS (PADEL)',
                'foods_to_eat' => [
                    'Banane',
                    'Courgette',
                    'Lentilles corail',
                    'Riz blanc',
                    'Poulet',
                    'Cabillaud'
                ],
                'prophetic_advice_fr' => 'Vitamine B1 | Oligo-éléments: Zn | Tisanes: Fenouil | Autres: Gingembre'
            ],
        ];

        // Prophetic Medicine Advice
        $propheticAdvice = [
            [
                'condition_name' => 'TOUX',
                'foods_to_eat' => [
                    'Nigelle (Habba Sauda)',
                    'Costus Indien (Qist)',
                    'Dattes (Ajwa)',
                    'Orge (Talbina)',
                    'Avoine',
                    'Miel de Thym / Eucalyptus',
                    'Oignon & Ail',
                    'Radis Noir',
                    'Figue Sèche',
                    'Citron / Coing'
                ],
                'prophetic_advice_fr' => 'Nigelle: bronchodilatateur anti-inflammatoire (1 càc huile + miel). Costus Indien: antiseptique bronches (inhalation vapeur ou avec miel). Dattes Ajwa: 7 le matin pour immunité. Talbina: mucilages calmants. Miel Thym/Eucalyptus: 1 càs 3x/jour. Sirop d\'oignon au miel. Tisanes: Thym/Serpolet, Mauve/Guimauve. Vitamines C, D3. Zinc 15-30mg/jour.'
            ],
            [
                'condition_name' => 'PHARYNGITE',
                'foods_to_eat' => [
                    'Costus Indien & Miel',
                    'Eau de Zamzam',
                    'Orge (Talbina)',
                    'Miel de Lavande',
                    'Miel de Manuka',
                    'Mûre / Myrtille',
                    'Sauge (Salvia)',
                    'Clou de Girofle',
                    'Réglisse'
                ],
                'prophetic_advice_fr' => 'Costus Indien: gargarisme poudre + eau tiède mielée. Eau de Zamzam: petites gorgées au fond de gorge. Talbina: bouillie très liquide et tiède. Miel Lavande: 1 càc fondre au fond de gorge 4x/jour. Miel Manuka: pur pour contact direct. Sauge: infusion forte, gargariser 30s puis avaler. Échinacée en spray buccal. Vitamine A (jus de carotte).'
            ],
            [
                'condition_name' => 'ACOUPHÈNES',
                'foods_to_eat' => [
                    'Hijama (Ventouses)',
                    'Huile de Nigelle',
                    'Sarrasin',
                    'Miel de Sarrasin',
                    'Épinards / Bettes',
                    'Cassis / Raisin Noir',
                    'Ginkgo Biloba',
                    'Aubépine',
                    'Petite Pervenche',
                    'Ashwagandha'
                ],
                'prophetic_advice_fr' => 'Hijama: nuque (Al-Kahil) et derrière oreilles. Huile Nigelle: massage tiède autour oreille et mâchoire. Sarrasin: source de Rutine pour micro-vaisseaux. Ginkgo Biloba: 3 tasses/jour cure 3 mois. Ashwagandha: le soir pour habituation. Vitamine B12 essentielle. Magnésium Bisglycinate 300mg/jour. Zinc 15mg/jour.'
            ],
            [
                'condition_name' => 'SINUSITE',
                'foods_to_eat' => [
                    'Costus Indien (Qist)',
                    'Nigelle (Habba Sauda)',
                    'Thym & Eucalyptus',
                    'Fleur de Sureau',
                    'Miel de Thym / Sapin',
                    'Radis Noir & Oignon',
                    'Citron & Pamplemousse',
                    'Orge (Talbina)',
                    'Astragale'
                ],
                'prophetic_advice_fr' => 'Costus Indien: 1 goutte huile olive + poudre dans chaque narine. Nigelle: bouillir graines et inhaler vapeur. Thym/Eucalyptus: inhalation. Miel Thym/Sapin: 1 càs le matin. Radis Noir/Oignon: sirop macéré au miel. Astragale: cure 21 jours. Vitamines C, A (jus de carotte). Argent colloïdal spray nasal.'
            ],
            [
                'condition_name' => 'MIGRAINE',
                'foods_to_eat' => [
                    'Hijama (Ventouses)',
                    'Huile de Nigelle',
                    'Grande Camomille',
                    'Menthe Poivrée',
                    'Miel de Lavande',
                    'Épinards & Avocat',
                    'Banane & Melon',
                    'Sarrasin',
                    'Rhodiola'
                ],
                'prophetic_advice_fr' => 'Hijama: points sommet tête (Al-Hâmah) et nuque. Huile Nigelle: massage tempes et front dès premiers signes. Grande Camomille: infusion (parthénolide prévient constriction). Menthe Poivrée: 1 goutte HE sur tempes. Miel Lavande: 1 càc apaisante. Rhodiola: 1 gélule le matin. Vitamines B2, B6. Magnésium Bisglycinate 300-400mg/jour cure 3 mois.'
            ],
            [
                'condition_name' => 'DÉPRESSION',
                'foods_to_eat' => [
                    'Talbina (Orge)',
                    'Dattes Ajwa',
                    'Safran',
                    'Millepertuis',
                    'Miel d\'Oranger',
                    'Épinards & Brocolis',
                    'Banane & Noix',
                    'Avoine',
                    'Rhodiola'
                ],
                'prophetic_advice_fr' => 'Talbina: bouillie orge + lait + miel le soir (apaise le cœur, tryptophane précurseur sérotonine). Dattes Ajwa: 7 le matin (magnésium, sucres naturels). Safran: 3-5 filaments eau chaude (inhibiteur recapture sérotonine). Millepertuis: cure 3 semaines. Rhodiola: 1 gélule le matin. Vitamine D3: 2000-4000 UI/jour. Lithium dose nutritionnelle: 1 ampoule sublinguale matin.'
            ],
            [
                'condition_name' => 'INSOMNIES',
                'foods_to_eat' => [
                    'Miel & Lait tiède',
                    'Huile d\'Olive (Massage)',
                    'Verveine & Mélisse',
                    'Valériane / Passiflore',
                    'Miel de Lavande',
                    'Laitue & Céleri',
                    'Cerise (Montmorency)',
                    'Riz Complet',
                    'Ashwagandha'
                ],
                'prophetic_advice_fr' => 'Miel + Lait: 1 càs miel dans lait chèvre/amande (tryptophane + transport cerveau). Huile Olive: massage pieds et sommet tête. Verveine/Mélisse: infusion 10 min avant coucher. Valériane/Passiflore: "Valium naturel". Miel Lavande: 1 càc à fondre au lit. Cerise Montmorency: source directe mélatonine. Ashwagandha: 1 càc poudre lait tiède le soir. Magnésium 300mg + B6 au dîner.'
            ],
            [
                'condition_name' => 'ANGOISSE ET STRESS',
                'foods_to_eat' => [
                    'Eau de Zamzam',
                    'Miel & Eau froide',
                    'Aubépine',
                    'Mélisse',
                    'Miel d\'Oranger / Citronnier',
                    'Asperge & Épinards',
                    'Amandes & Abricots secs',
                    'Orge (Talbina)',
                    'Ashwagandha'
                ],
                'prophetic_advice_fr' => 'Eau Zamzam: alcalinité + magnésium détente neuronale. Miel + Eau froide: matin (nerf vague calmant). Aubépine: 3 tasses/jour (cœur léger, palpitations). Mélisse: relaxant système nerveux. Talbina: stabilise glycémie (évite hypoglycémie anxieuse). Ashwagandha: 1/2 càc poudre lait tiède (régule cortisol). Vitamines B (levure de bière). Magnésium Bisglycinate 300mg/jour. Zinc.'
            ],
            [
                'condition_name' => 'HÉMORROÏDES',
                'foods_to_eat' => [
                    'Huile de Nigelle',
                    'Figues sèches & Huile d\'Olive',
                    'Marron d\'Inde',
                    'Vigne Rouge',
                    'Miel de Châtaignier',
                    'Betterave & Légumes verts',
                    'Cassis & Myrtille',
                    'Son d\'Avoine',
                    'Gotu Kola'
                ],
                'prophetic_advice_fr' => 'Huile Nigelle: application locale après chaque toilette. Figues + Huile Olive: macérer figues dans huile, manger 1 à jeun. Marron d\'Inde: protecteur veineux n°1. Vigne Rouge: 3 tasses/jour (polyphénols anti-stase). Miel Châtaignier: 1 càs/jour (circulation). Betterave/Légumes verts: fibres douces anti-constipation. Son Avoine: 1-2 càs/jour (selles molles). Vitamine P (peau blanche agrumes). Soufre en ampoules.'
            ],
            [
                'condition_name' => 'ULCÈRE',
                'foods_to_eat' => [
                    'Miel pur à jeun',
                    'Gingembre (Zanjabil)',
                    'Réglisse (DGL)',
                    'Guimauve (Racine)',
                    'Miel de Manuka / Euphorbe',
                    'Jus de Chou Blanc',
                    'Banane mûre',
                    'Orge (Talbina)',
                    'Aloe Vera (Gel)'
                ],
                'prophetic_advice_fr' => 'Miel pur: 1 càs eau tiède le matin (cicatrisant, régénère paroi gastrique). Gingembre: infusion légère ou pincée poudre (propriétés anti-ulcéreuses). Réglisse DGL: mâcher avant repas (stimule mucus protecteur). Guimauve: macération à froid 2h (pansement naturel). Miel Manuka/Euphorbe: 1 càc 30 min avant repas (tue H. Pylori). Jus Chou Blanc: 100ml frais (vitamine U guérit ulcère). Aloe Vera: 2 càs gel pur le matin. Vitamines A, E. Zinc-Carnosine.'
            ],
            [
                'condition_name' => 'CONSTIPATION',
                'foods_to_eat' => [
                    'Séné (Sana)',
                    'Huile d\'Olive',
                    'Psyllium Blond',
                    'Mauve & Guimauve',
                    'Miel de Bourdaine / Acacia',
                    'Betterave & Épinards',
                    'Figues & Pruneaux',
                    'Son d\'Avoine',
                    'Triphala'
                ],
                'prophetic_advice_fr' => 'Séné: infusion feuilles usage occasionnel (le Prophète ﷺ l\'a recommandé pour purifier le ventre). Huile Olive: 1 càs extra-vierge à jeun. Psyllium: 1 càc grand verre d\'eau (mucilage). Miel Bourdaine/Acacia: eau tiède matin (laxatif prébiotique). Figues/Pruneaux: tremper 3 figues la nuit, boire eau + manger au réveil (sorbitol). Son Avoine: 1-2 càs/jour avec beaucoup d\'eau. Triphala: poudre ou gélules le soir. Vitamine C haute dose 1-2g/jour. Magnésium Citrate 300-400mg le soir.'
            ],
            [
                'condition_name' => 'DIARRHÉE',
                'foods_to_eat' => [
                    'Miel dilué',
                    'Gingembre (Zanjabil)',
                    'Thé noir / Salicaire',
                    'Feuilles de Framboisier',
                    'Miel de Thym / Lavande',
                    'Carottes cuites',
                    'Coing & Myrtille',
                    'Riz Blanc & Eau de riz',
                    'Champignon Reishi'
                ],
                'prophetic_advice_fr' => 'Miel dilué: eau tiède (antibiotique naturel, régule eau - Sahih Bukhari). Gingembre: infusion légère (calme spasmes). Thé noir: infusé longtemps (tanins astringents). Miel Thym/Lavande: 1 càs dans eau de riz. Carottes cuites: purée (pectines gel protecteur). Coing: gelée (anti-diarrhéique, tanins). Riz Blanc + eau de cuisson avec sel (amidon + sodium réhydratation). Reishi: régule réponse immunitaire intestinale. Vitamine A. Zinc 15-20mg/jour.'
            ],
            [
                'condition_name' => 'OBÉSITÉ',
                'foods_to_eat' => [
                    'Jeûne (Lundi/Jeudi)',
                    'Graine de Nigelle',
                    'Vinaigre de Cidre',
                    'Thé Vert / Maté',
                    'Reine des prés / Orthosiphon',
                    'Miel de Forêt',
                    'Brocoli, Chou, Poireau',
                    'Pomme & Pamplemousse',
                    'Orge (Talbina)',
                    'Rhodiola'
                ],
                'prophetic_advice_fr' => 'Jeûne 16/8 ou Sunna (lipolyse, repose pancréas). Nigelle: 1 càc graines broyées matin (sensibilité insuline). Vinaigre Cidre: 1 càs verre eau 20 min avant repas (bloque glucides, brûle graisses). Thé Vert/Maté: 3 tasses avant 16h (thermogenèse). Miel Forêt: petite dose remplace sucre (IG bas). Pomme + peau avant repas (pectine satiété). Talbina: petit-déjeuner (régule appétit). Rhodiola: 1 gélule matin (anti-grignotage émotionnel). Vitamines B, D3. Chrome 200µg/jour (envies sucre).'
            ],
            [
                'condition_name' => 'CHOLESTÉROL',
                'foods_to_eat' => [
                    'Graine de Nigelle',
                    'Huile d\'Olive',
                    'Artichaut / Radis Noir',
                    'Thé Vert',
                    'Miel de Forêt / Montagne',
                    'Ail & Oignon',
                    'Pomme (avec la peau)',
                    'Avoine & Orge',
                    'Reishi'
                ],
                'prophetic_advice_fr' => 'Nigelle: 1 càc graines broyées + miel matin (thymoquinone réduit synthèse cholestérol). Huile Olive: 1-2 càs extra-vierge crue/jour (acide oléique protège artères). Artichaut/Radis Noir: infusion ou ampoules (bile évacue cholestérol). Thé Vert: 3 tasses/jour (catéchines réduisent absorption). Ail: 1 gousse crue écrasée/jour (allicine action type statine douce). Pomme: 1-2/jour avec peau (pectine piège cholestérol). Avoine/Orge: bêta-glucanes capturent acides biliaires. Vitamines E, C. Chrome 200µg/jour.'
            ],
            [
                'condition_name' => 'TRIGLYCÉRIDES',
                'foods_to_eat' => [
                    'Vinaigre de Cidre',
                    'Jeûne (Sunna)',
                    'Romarin / Pissenlit',
                    'Gingembre',
                    'Miel de Romarin',
                    'Poireau & Échalote',
                    'Avocat & Olives',
                    'Sarrasin',
                    'Ginseng Coréen'
                ],
                'prophetic_advice_fr' => 'Vinaigre Cidre: 1 càs verre eau avant repas (acide acétique combustion graisses). Jeûne Lundi/Jeudi (vide réserves triglycérides sang et foie). Romarin/Pissenlit: infusion après repas (foie transforme sucres en énergie pas gras). Gingembre: frais (active enzymes dégradation). Miel Romarin: petite dose (décongestion hépatique). Avocat/Olives: remplacer graisses saturées (mono-insaturées font chuter TG). Sarrasin: fibres + rutine circulation. Ginseng: cure 3 semaines matin. Vitamine B3 Niacine. Omega-3 EPA/DHA (huile petits poissons).'
            ],
            [
                'condition_name' => 'DIABÈTE',
                'foods_to_eat' => [
                    'Graine de Nigelle & Costus',
                    'Jeûne (Sunna)',
                    'Cannelle (Ceylan)',
                    'Fénugrec (Hulba)',
                    'Miel de Jujubier',
                    'Oignon & Poireau',
                    'Myrtille & Citron',
                    'Orge (Talbina)',
                    'Gymnema Sylvestre'
                ],
                'prophetic_advice_fr' => 'Nigelle + Costus: 1/2 càc chaque dans eau ou yaourt (Nigelle stimule pancréas, Costus métabolisme). Jeûne Lundi/Jeudi ou 16/8 (repose pancréas, sensibilité insuline). Cannelle Ceylan: 1 càc/jour (mime insuline, réduit sucre 20% post-repas). Fénugrec: tremper graines 12h, boire eau + graines (fibres ralentissent glucides). Miel Jujubier: faible IG. Myrtille: baies (améliorent réponse insuline). Talbina: très bas IG. Gymnema: 400mg avant repas (réduit absorption sucre). Chrome 200µg/jour. Magnésium.'
            ],
        ];

        // Merge all advice
        $allAdvice = array_merge($footballAdvice, $fitnessAdvice, $padelAdvice, $propheticAdvice);

        foreach ($allAdvice as $advice) {
            NutritionAdvice::create($advice);
        }
    }
}
