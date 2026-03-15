<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntensityZone;

class IntensityZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'color' => 'blue',
                'name_fr' => 'Recuperation & Oxygenation',
                'name_en' => 'Recovery & Oxygenation',
                'name_ar' => 'التعافي والأكسجة',
                'intensity_range' => '50-60%',
                'rpe_min' => 1,
                'rpe_max' => 3,
                'description_fr' => 'Travail leger pour favoriser la circulation sanguine et eliminer les dechets metaboliques',
                'description_en' => 'Light work to promote blood circulation and eliminate metabolic waste',
                'description_ar' => 'عمل خفيف لتعزيز الدورة الدموية والتخلص من النفايات الأيضية',
                'sort_order' => 1,
            ],
            [
                'color' => 'green',
                'name_fr' => 'Endurance Fondamentale & Zone 2',
                'name_en' => 'Fundamental Endurance & Zone 2',
                'name_ar' => 'التحمل الأساسي والمنطقة 2',
                'intensity_range' => '60-70%',
                'rpe_min' => 3,
                'rpe_max' => 5,
                'description_fr' => 'Base aerobie, developpement du reseau capillaire et de l\'efficacite cardiaque',
                'description_en' => 'Aerobic base, development of capillary network and cardiac efficiency',
                'description_ar' => 'القاعدة الهوائية، تطوير شبكة الشعيرات الدموية وكفاءة القلب',
                'sort_order' => 2,
            ],
            [
                'color' => 'yellow',
                'name_fr' => 'Rythme Match & Intermittence',
                'name_en' => 'Match Rhythm & Intermittence',
                'name_ar' => 'إيقاع المباراة والتناوب',
                'intensity_range' => '70-80%',
                'rpe_min' => 5,
                'rpe_max' => 7,
                'description_fr' => 'Intensite moderee a elevee simulant les exigences d\'un match',
                'description_en' => 'Moderate to high intensity simulating match demands',
                'description_ar' => 'كثافة معتدلة إلى عالية تحاكي متطلبات المباراة',
                'sort_order' => 3,
            ],
            [
                'color' => 'orange',
                'name_fr' => 'Haute Intensite & Resistance',
                'name_en' => 'High Intensity & Resistance',
                'name_ar' => 'كثافة عالية ومقاومة',
                'intensity_range' => '80-90%',
                'rpe_min' => 7,
                'rpe_max' => 9,
                'description_fr' => 'Efforts intenses developpant la puissance et la resistance a la fatigue',
                'description_en' => 'Intense efforts developing power and fatigue resistance',
                'description_ar' => 'جهود مكثفة تطور القوة ومقاومة التعب',
                'sort_order' => 4,
            ],
            [
                'color' => 'red',
                'name_fr' => 'Intensite Maximale & Explosion',
                'name_en' => 'Maximum Intensity & Explosion',
                'name_ar' => 'الكثافة القصوى والانفجار',
                'intensity_range' => '90-100%',
                'rpe_min' => 9,
                'rpe_max' => 10,
                'description_fr' => 'Efforts maximaux, explosivite et puissance maximale',
                'description_en' => 'Maximum efforts, explosiveness and maximum power',
                'description_ar' => 'جهود قصوى، انفجارية وقوة قصوى',
                'sort_order' => 5,
            ],
        ];

        foreach ($zones as $zone) {
            IntensityZone::updateOrCreate(
                ['color' => $zone['color']],
                $zone
            );
        }
    }
}
