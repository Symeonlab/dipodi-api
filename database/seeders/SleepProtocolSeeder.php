<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SleepProtocol;
use App\Models\Chronotype;

class SleepProtocolSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSleepProtocols();
        $this->seedChronotypes();
    }

    private function seedSleepProtocols(): void
    {
        $protocols = [
            [
                'condition_key' => 'fatigue',
                'condition_name_fr' => 'Fatigue / Baisse d\'energie',
                'condition_name_en' => 'Fatigue / Low Energy',
                'condition_name_ar' => 'تعب / انخفاض الطاقة',
                'cycles_min' => 6,
                'cycles_max' => 7,
                'total_sleep' => '9-10h30',
                'objective_fr' => 'Restaurer l\'energie globale',
                'objective_en' => 'Restore global energy',
                'objective_ar' => 'استعادة الطاقة الشاملة',
                'category' => 'recovery',
                'sort_order' => 1,
            ],
            [
                'condition_key' => 'muscle_injury',
                'condition_name_fr' => 'Blessure musculaire',
                'condition_name_en' => 'Muscle Injury',
                'condition_name_ar' => 'إصابة عضلية',
                'cycles_min' => 7,
                'cycles_max' => 7,
                'total_sleep' => '10h30',
                'objective_fr' => 'Reparation tissulaire',
                'objective_en' => 'Tissue repair',
                'objective_ar' => 'إصلاح الأنسجة',
                'category' => 'injury',
                'sort_order' => 2,
            ],
            [
                'condition_key' => 'sprain',
                'condition_name_fr' => 'Entorse / Inflammation',
                'condition_name_en' => 'Sprain / Inflammation',
                'condition_name_ar' => 'التواء / التهاب',
                'cycles_min' => 7,
                'cycles_max' => 7,
                'total_sleep' => '10h30',
                'objective_fr' => 'Reduction du stress articulaire',
                'objective_en' => 'Joint stress reduction',
                'objective_ar' => 'تقليل إجهاد المفاصل',
                'category' => 'injury',
                'sort_order' => 3,
            ],
            [
                'condition_key' => 'tendinitis',
                'condition_name_fr' => 'Tendinite',
                'condition_name_en' => 'Tendinitis',
                'condition_name_ar' => 'التهاب الأوتار',
                'cycles_min' => 7,
                'cycles_max' => 7,
                'total_sleep' => '10h30',
                'objective_fr' => 'Adaptation tendineuse',
                'objective_en' => 'Tendon adaptation',
                'objective_ar' => 'تكيف الأوتار',
                'category' => 'injury',
                'sort_order' => 4,
            ],
            [
                'condition_key' => 'post_match',
                'condition_name_fr' => 'Recuperation post-match',
                'condition_name_en' => 'Post-match Recovery',
                'condition_name_ar' => 'التعافي بعد المباراة',
                'cycles_min' => 6,
                'cycles_max' => 7,
                'total_sleep' => '9-10h30',
                'objective_fr' => 'Reconstitution complete',
                'objective_en' => 'Complete reconstitution',
                'objective_ar' => 'إعادة بناء كاملة',
                'category' => 'recovery',
                'sort_order' => 5,
            ],
            [
                'condition_key' => 'overtraining',
                'condition_name_fr' => 'Surentrainement',
                'condition_name_en' => 'Overtraining',
                'condition_name_ar' => 'الإفراط في التدريب',
                'cycles_min' => 7,
                'cycles_max' => 7,
                'total_sleep' => '10h30',
                'objective_fr' => 'Reset complet',
                'objective_en' => 'Full reset',
                'objective_ar' => 'إعادة ضبط كاملة',
                'category' => 'recovery',
                'sort_order' => 6,
            ],
            [
                'condition_key' => 'fracture',
                'condition_name_fr' => 'Fracture osseuse',
                'condition_name_en' => 'Bone Fracture',
                'condition_name_ar' => 'كسر عظمي',
                'cycles_min' => 7,
                'cycles_max' => 7,
                'total_sleep' => '10h30',
                'objective_fr' => 'Consolidation osseuse',
                'objective_en' => 'Bone consolidation',
                'objective_ar' => 'تثبيت العظام',
                'category' => 'injury',
                'sort_order' => 7,
            ],
            [
                'condition_key' => 'dehydration',
                'condition_name_fr' => 'Deshydratation',
                'condition_name_en' => 'Dehydration',
                'condition_name_ar' => 'الجفاف',
                'cycles_min' => 6,
                'cycles_max' => 6,
                'total_sleep' => '9h',
                'objective_fr' => 'Reequilibrage hydrique',
                'objective_en' => 'Hydric rebalancing',
                'objective_ar' => 'إعادة التوازن المائي',
                'category' => 'medical',
                'sort_order' => 8,
            ],
            [
                'condition_key' => 'stress_sleep',
                'condition_name_fr' => 'Stress / Troubles du sommeil',
                'condition_name_en' => 'Stress / Sleep Issues',
                'condition_name_ar' => 'التوتر / اضطرابات النوم',
                'cycles_min' => 7,
                'cycles_max' => 7,
                'total_sleep' => '10h30',
                'objective_fr' => 'Normalisation des cycles',
                'objective_en' => 'Cycle normalization',
                'objective_ar' => 'تطبيع الدورات',
                'category' => 'medical',
                'sort_order' => 9,
            ],
        ];

        foreach ($protocols as $protocol) {
            SleepProtocol::updateOrCreate(
                ['condition_key' => $protocol['condition_key']],
                $protocol
            );
        }
    }

    private function seedChronotypes(): void
    {
        $chronotypes = [
            [
                'key' => 'lion',
                'name_fr' => 'Le Lion',
                'name_en' => 'The Lion',
                'name_ar' => 'الأسد',
                'wake_time' => '05h00-06h00',
                'peak_start' => '06h00',
                'peak_end' => '12h00',
                'bedtime' => '22h00',
                'description_fr' => 'Matinal, energie immediate au reveil, pic de productivite avant midi',
                'description_en' => 'Morning person, immediate energy at wake, peak productivity before noon',
                'description_ar' => 'شخص صباحي، طاقة فورية عند الاستيقاظ، ذروة الإنتاجية قبل الظهر',
                'character_fr' => 'Leader, organise, discipline',
                'character_en' => 'Leader, organized, disciplined',
                'character_ar' => 'قائد، منظم، منضبط',
                'icon' => "\u{1F981}",
                'sort_order' => 1,
            ],
            [
                'key' => 'bear',
                'name_fr' => 'L\'Ours',
                'name_en' => 'The Bear',
                'name_ar' => 'الدب',
                'wake_time' => '07h00-08h00',
                'peak_start' => '10h00',
                'peak_end' => '14h00',
                'bedtime' => '23h00',
                'description_fr' => 'Solaire, besoin de temps pour emerger, pic entre 10h et 14h',
                'description_en' => 'Solar type, needs time to emerge, peak between 10am-2pm',
                'description_ar' => 'نوع شمسي، يحتاج وقتاً للاستيقاظ، ذروة بين 10-14',
                'character_fr' => 'Sociable, equipe, regulier',
                'character_en' => 'Sociable, team-oriented, regular',
                'character_ar' => 'اجتماعي، موجه نحو الفريق، منتظم',
                'icon' => "\u{1F43B}",
                'sort_order' => 2,
            ],
            [
                'key' => 'wolf',
                'name_fr' => 'Le Loup',
                'name_en' => 'The Wolf',
                'name_ar' => 'الذئب',
                'wake_time' => '09h00+',
                'peak_start' => '20h00',
                'peak_end' => '02h00',
                'bedtime' => '01h00',
                'description_fr' => 'Nocturne, difficulte a se lever tot, pic creatif apres 20h',
                'description_en' => 'Night owl, difficulty waking early, creative peak after 8pm',
                'description_ar' => 'بومة ليلية، صعوبة في الاستيقاظ مبكراً، ذروة إبداعية بعد الثامنة مساءً',
                'character_fr' => 'Creatif, intuitif, solitaire',
                'character_en' => 'Creative, intuitive, solitary',
                'character_ar' => 'مبدع، حدسي، انفرادي',
                'icon' => "\u{1F43A}",
                'sort_order' => 3,
            ],
            [
                'key' => 'dolphin',
                'name_fr' => 'Le Dauphin',
                'name_en' => 'The Dolphin',
                'name_ar' => 'الدلفين',
                'wake_time' => 'Variable',
                'peak_start' => 'Variable',
                'peak_end' => 'Variable',
                'bedtime' => '23h30',
                'description_fr' => 'Sommeil leger, micro-reveils frequents, cerveau hyperactif au coucher',
                'description_en' => 'Light sleeper, frequent micro-awakenings, hyperactive brain at bedtime',
                'description_ar' => 'نوم خفيف، استيقاظ جزئي متكرر، دماغ نشط جداً عند النوم',
                'character_fr' => 'Intelligent, perfectionniste, anxieux',
                'character_en' => 'Intelligent, perfectionist, anxious',
                'character_ar' => 'ذكي، مثالي، قلق',
                'icon' => "\u{1F42C}",
                'sort_order' => 4,
            ],
        ];

        foreach ($chronotypes as $chronotype) {
            Chronotype::updateOrCreate(
                ['key' => $chronotype['key']],
                $chronotype
            );
        }
    }
}
