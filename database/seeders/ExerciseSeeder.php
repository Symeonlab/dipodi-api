<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        // No truncate needed, migrate:fresh handles it.

        $met_kine_renforcement = 4.0;
        $met_kine_mobilite = 3.0;
        $met_maison_cardio = 8.0;
        $met_maison_renforcement = 8.0;
        $met_bonus = 5.0;
        $met_cardio_salle = 12.0;
        $met_muscu = 8.0;

        $exerciseGroups = [
            'KINE RENFORCEMENT' => [
                'met' => $met_kine_renforcement,
                'sub_categories' => [
                    'QUADRICEPS' => [
                        'https://youtube.com/shorts/05HR5YAvGDc', 'https://youtube.com/shorts/IKmo4cBFEk4',
                        'https://youtube.com/shorts/bziCwpR4n8w', 'https://youtube.com/shorts/GzrMAtMgvFQ',
                        'https://youtube.com/shorts/zdrJ4GKFQ6c', 'https://youtube.com/shorts/TCdMMQmJOK8',
                    ],
                    'PSOAS FLÉCHISSEURS HANCHES' => [
                        'https://youtube.com/shorts/kdokuhYUhvI', 'https://youtube.com/shorts/5vlR3j5ULog',
                        'https://youtube.com/shorts/uvgma7UsCKM', 'https://youtube.com/shorts/RmMbh-yXGUQ',
                        'https://youtube.com/shorts/nznd4lI3KEA', 'https://youtube.com/shorts/KrKj07QJbx0',
                    ],
                    'PIEDS' => [
                        'https://youtube.com/shorts/kQjdeoz7fSY', 'https://youtube.com/shorts/zIm7uiXVcdo',
                        'https://youtube.com/shorts/SY--0iWYaC8', 'https://youtube.com/shorts/_onHMkRo5kc',
                        'https://youtube.com/shorts/lY1YjXsd5s4', 'https://youtube.com/shorts/rgu8MNT89ME',
                    ],
                    'MOYEN FESSIERS' => [
                        'https://youtube.com/shorts/z_tqjn-LV24', 'https://youtube.com/shorts/WWp79ePdI4g',
                        'https://youtube.com/shorts/Ecwp7jL_8u4', 'https://youtube.com/shorts/_8JfY3ofVnE',
                    ],
                    'MOLLETS' => [
                        'https://youtube.com/shorts/3EB7ZAB5AWo', 'https://youtube.com/shorts/L0_QOx5h7CY',
                        'https://youtube.com/shorts/JRd9zoOL_dY', 'https://youtube.com/shorts/h3csfW9JrdA',
                        'https://youtube.com/shorts/ZM6c015fDl0',
                    ],
                    'ISHIOS JAMBIERS' => [
                        'https://youtube.com/shorts/ydpzy7IIFOM', 'https://youtube.com/shorts/Pdy2NVmSfko',
                        'https://youtube.com/shorts/AVluREI0nkU', 'https://youtube.com/shorts/yWUxQQP_UVI',
                        'https://youtube.com/shorts/Hg9bUiEmnUc',
                    ],
                    'FESSIERS' => [
                        'https://youtube.com/shorts/SdZKcrOCiJ4', 'https://youtube.com/shorts/_YutftKthMc',
                        'https://youtube.com/shorts/eprrreviA-Y', 'https://youtube.com/shorts/wGrw4sHYzqo',
                        'https://youtube.com/shorts/VxSiapVUcvQ', 'https://youtube.com/shorts/3nzCdHztpqM',
                        'https://youtube.com/shorts/ejXv68SlgJ0',
                    ],
                    'CHEVILLES' => [
                        'https://youtube.com/shorts/mma9QnLC0kc', 'https://youtube.com/shorts/_ONChb1TqNE',
                        'https://youtube.com/shorts/taAgzIx25pA', 'https://youtube.com/shorts/tPFFfJ093ns',
                        'https://youtube.com/shorts/vaTGg6ByiPg',
                    ],
                    'ADDUCTEURS' => [
                        'https://youtube.com/shorts/JUlDFRTNA6w', 'https://youtube.com/shorts/HuS1WuH7M7s',
                        'https://youtube.com/shorts/0Y1d_xR0HKY', 'https://youtube.com/shorts/8JuNUeqNg7w',
                        'https://youtube.com/shorts/pHusO6546sc',
                    ],
                ]
            ],
            'KINE MOBILITÉ' => [
                'met' => $met_kine_mobilite,
                'sub_categories' => [
                    'PIED VOUTE PLANTAIRE' => [
                        'https://youtube.com/shorts/2xsX7cNsMyk', 'https://youtube.com/shorts/MFgpE3FBsuo',
                        'https://youtube.com/shorts/ALnYLAPsD1w', 'https://youtube.com/shorts/kMO8D8eb7Xo',
                    ],
                    'HANCHE' => [
                        'https://youtube.com/shorts/Hem1BU0Hxiw', 'https://youtube.com/shorts/LrKRE0t6uuo',
                        'https://youtube.com/shorts/lzgAXKHsT-A', 'https://youtube.com/shorts/p6Em-gZgvuU',
                    ],
                    'GENOUX' => [
                        'https://youtube.com/shorts/dAOZ1DIwS_k', 'https://youtube.com/shorts/Z9fhJGCC5yo',
                        'https://youtube.com/shorts/P9q9s73dhcY',
                    ],
                    'CHEVILLES' => [
                        'https://youtube.com/shorts/avXpC0-grZ8', 'https://youtube.com/shorts/vvIH4O4BltY',
                        'https://youtube.com/shorts/kGDXD0mt6RU', 'https://youtube.com/shorts/AXj80wM2dnY',
                        'https://youtube.com/shorts/IbxH9o3TqUA',
                    ],
                ]
            ],
            'PROGRAMME GRATUIT MAISON' => [
                'met' => $met_maison_cardio, // Use one MET for both sub-cats
                'sub_categories' => [
                    'CARDIO' => [
                        'https://youtube.com/shorts/0agTTLqEBDo', 'https://youtube.com/shorts/1-_dvwTVd6g', 'https://youtube.com/shorts/8ige7jhJuOc',
                        'https://youtube.com/shorts/94lMAmmpuFM', 'https://youtube.com/shorts/9P2h17GJqlg', 'https://youtube.com/shorts/A-zAPau66ac',
                        'https://youtube.com/shorts/D231oAExvbo', 'https://youtube.com/shorts/DAj3ReIVqj4', 'https://youtube.com/shorts/H2oDhb5h6FY',
                        'https://youtube.com/shorts/HyNQX-mZOgM', 'https://youtube.com/shorts/Hz4QAamSytE', 'https://youtube.com/shorts/I_mpQjwhm10',
                        'https://youtube.com/shorts/In16LZOKH64', 'https://youtube.com/shorts/K86XtCL2DsI', 'https://youtube.com/shorts/KxcOh0eDv4Y',
                        'https://youtube.com/shorts/LWi75HPvz3Y', 'https://youtube.com/shorts/LWyOnYcY-b4', 'https://youtube.com/shorts/ObkAeR5jviY',
                        'https://youtube.com/shorts/Q0gHHIOF5qs', 'https://youtube.com/shorts/QvWDu-caqZ0', 'https://youtube.com/shorts/S4CkPRvMuSQ',
                        'https://youtube.com/shorts/TY1jbTBewpw', 'https://youtube.com/shorts/VGUQouIknj4', 'https://youtube.com/shorts/XX94L3sMPfc',
                        'https://youtube.com/shorts/YWpOhfveyOA', 'https://youtube.com/shorts/_iPXls5g968', 'https://youtube.com/shorts/a4m2ly2z0AM',
                        'https://youtube.com/shorts/aqqGoG8AQ6U', 'https://youtube.com/shorts/bRMBSum63Cg', 'https://youtube.com/shorts/cvdLWUv2TAU',
                        'https://youtube.com/shorts/efcAyXO_ruE', 'https://youtube.com/shorts/hckp15sC3fo', 'https://youtube.com/shorts/ibiPv340Bc8',
                        'https://youtube.com/shorts/j-XaHL3MZp4', 'https://youtube.com/shorts/j90R2w2S_Ic', 'https://youtube.com/shorts/jyYi_SzP868',
                        'https://youtube.com/shorts/m1-yQOy87Dg', 'https://youtube.com/shorts/mTbuFt8JUf4', 'https://youtube.com/shorts/moCoaOAnXm8',
                        'https://youtube.com/shorts/pFhk9fCburg', 'https://youtube.com/shorts/pVdimwL-Q-E', 'https://youtube.com/shorts/q4OaYt9paYE',
                        'https://youtube.com/shorts/rL6YSzeJLpA', 'https://youtube.com/shorts/r_E7F2CxskI', 'https://youtube.com/shorts/sRdUS6P0Xq8',
                        'https://youtube.com/shorts/syUylhYZ3Ow', 'https://youtube.com/shorts/ts64BaBgL18', 'https://youtube.com/shorts/wgb_eFMivR0',
                        'https://youtube.com/shorts/xmiJbgFUFX0', 'https://youtube.com/shorts/yfYFlqHZ1i8', 'https://youtube.com/shorts/1bOtpKjmYFA',
                        'https://youtube.com/shorts/AQffsv8l0fw', 'https://youtube.com/shorts/Doa0MuXwz1Y', 'https://youtube.com/shorts/HNwUQUMTtCI',
                        'https://youtube.com/shorts/MRFEC7P72yc', 'https://youtube.com/shorts/NEc2KFrEons', 'https://youtube.com/shorts/YWZ48FX5pbY',
                        'https://youtube.com/shorts/g-scz7j6qeQ', 'https://youtube.com/shorts/j8Q1FytUvaM',
                    ],
                    'RENFORCEMENT' => [
                        'https://youtube.com/shorts/4mZiR6juYvQ', 'https://youtube.com/shorts/7iOwd-vcM1Y', 'https://youtube.com/shorts/9lD41hGN33U',
                        'https://youtube.com/shorts/A2QgyQccmkc', 'https://youtube.com/shorts/EXJhlEGDOtM', 'https://youtube.com/shorts/FQunMsCY2S4',
                        'https://youtube.com/shorts/Lt9IM1Btxs8', 'https://youtube.com/shorts/VTCVcS0ZYPU', 'https://youtube.com/shorts/Wz_XlGy2r8I',
                        'https://youtube.com/shorts/jPH87b3jEH4', 'https://youtube.com/shorts/kEIZymTabJo', 'https://youtube.com/shorts/sAYigNyptcE',
                        'https://youtube.com/shorts/xfmRurcAx-s', 'https://youtube.com/shorts/y9q5Lg-Tsvw', 'https://youtube.com/shorts/GULC7LkCYN0',
                        'https://youtube.com/shorts/IfUVcGOb4yg', 'https://youtube.com/shorts/SKJy-sRZzZw', 'https://youtube.com/shorts/XwppXLYfLTo',
                        'https://youtube.com/shorts/dL5L6TOA6Z8', 'https://youtube.com/shorts/esHt_ozPaes', 'https://youtube.com/shorts/jQsuxabTsSg',
                        'https://youtube.com/shorts/oSQ-SzXXXJw', 'https://youtube.com/shorts/HWHVw1jKPQA', 'https://youtube.com/shorts/Mjj8g1pqmwY',
                        'https://youtube.com/shorts/3AtNuJcOtgA', 'https://youtube.com/shorts/3LBmCMbReps', 'https://youtube.com/shorts/5ANwnOhZNXU',
                        'https://youtube.com/shorts/DHmtBGQKUEI', 'https://youtube.com/shorts/Y09Q93d0Ddk', 'https://youtube.com/shorts/cZxg5ii2tPI',
                        'https://youtube.com/shorts/hUwO4QDv7tY', 'https://youtube.com/shorts/kw3uUWC1aaU', 'https://youtube.com/shorts/onRM9ys0FHI',
                        'https://youtube.com/shorts/u3R0fmHQkvs', 'https://youtube.com/shorts/u5B6ntasNtk', 'https://youtube.com/shorts/4b73iXRSr8s',
                        'https://youtube.com/shorts/dFriMN_O7ns', 'https://youtube.com/shorts/-2OVfN5SWI0', 'https://youtube.com/shorts/2PeZgd-dDQA',
                        'https://youtube.com/shorts/7jhoYRjW4W8', 'https://youtube.com/shorts/9dPReYSPDEo', 'https://youtube.com/shorts/FhNhP56iF_s',
                        'https://youtube.com/shorts/MoD4WPEUmLQ', 'https://youtube.com/shorts/NCII6SGPMQo', 'https://youtube.com/shorts/RxBivpr678U',
                        'https://youtube.com/shorts/T3MSRFltTfk', 'https://youtube.com/shorts/UOIPYVxh-XU', 'https://youtube.com/shorts/V24bj9s2iFE',
                        'https://youtube.com/shorts/X1CY9w4ZhR0', 'https://youtube.com/shorts/_Vcrq4FuS18', 'https://youtube.com/shorts/jdkxTgOreTw',
                        'https://youtube.com/shorts/psNY9JBuGA8', 'https://youtube.com/shorts/rfstIp6U0EQ', 'https://youtube.com/shorts/vnoFLq_Kr_c',
                        'https://youtube.com/shorts/2PVXepKrot4', 'https://youtube.com/shorts/BY6L8Au4jXk', 'https://youtube.com/shorts/C0zVzX_vDFM',
                        'https://youtube.com/shorts/KCkIY7y8RKQ', 'https://youtube.com/shorts/R888LJ_r1ck', 'https://youtube.com/shorts/UINRkR9L_Go',
                        'https://youtube.com/shorts/inlGW14Y2vI', 'https://youtube.com/shorts/zjAWS7dJjA0',
                    ],
                ]
            ],
            'PROGRAMME GRATUIT BONUS' => [
                'met' => $met_bonus,
                'sub_categories' => [
                    'ABDOS' => [
                        'https://youtube.com/shorts/FHjjTqxcB8w', 'https://youtube.com/shorts/-C79TR7Rvdc', 'https://youtube.com/shorts/0pSJ6mfzHJE',
                        'https://youtube.com/shorts/1mrDGRnlwmE', 'https://youtube.com/shorts/55D_CHEx7oI', 'https://youtube.com/shorts/5Rl3YZ3Vm-8',
                        'https://youtube.com/shorts/8YCCNcJQrjw', 'https://youtube.com/shorts/8vdQ2WGokxs', 'https://youtube.com/shorts/A4u79ge-bDI',
                        'https://youtube.com/shorts/AeymKJ5KNa0', 'https://youtube.com/shorts/BCnRS8YIG5s', 'https://youtube.com/shorts/DmWpsSJRoUg',
                        'https://youtube.com/shorts/EzXEg8vsXF0', 'https://youtube.com/shorts/Kn0xgs_xKeQ', 'https://youtube.com/shorts/M1WaAJMNGRo',
                        'https://youtube.com/shorts/MhzCOvdFk6k', 'https://youtube.com/shorts/Ms2-saauSB8', 'https://youtube.com/shorts/OgLG8TYScwo',
                        'https://youtube.com/shorts/Qa8_jvhjdco', 'https://youtube.com/shorts/RtNJaY1jhus', 'https://youtube.com/shorts/VEflbldS2W8',
                        'https://youtube.com/shorts/WFBanM956Q8', 'https://youtube.com/shorts/aDW4jV--tlU', 'https://youtube.com/shorts/aHS_y-7DBSQ',
                        'https://youtube.com/shorts/apWeP7KQzss', 'https://youtube.com/shorts/cD13kQFwamQ', 'https://youtube.com/shorts/c_1TrVgeIoo',
                        'https://youtube.com/shorts/dIx_9-1S9OM', 'https://youtube.com/shorts/dxjoSvJIt9c', 'https://youtube.com/shorts/e3vL9NTBOD8',
                        'https://youtube.com/shorts/eKT4xJt_luw', 'https://youtube.com/shorts/fby-Kv5V-cs', 'https://youtube.com/shorts/jCxkLJ7dQQs',
                        'https://youtube.com/shorts/lBaXb6G3qdY', 'https://youtube.com/shorts/lmptWZg6rQE', 'https://youtube.com/shorts/n_zj6m61Rtw',
                        'https://youtube.com/shorts/nwQ4b2gqMBE', 'https://youtube.com/shorts/oAAtI1FPRNU', 'https://youtube.com/shorts/oHgV-9U9CbY',
                        'https://youtube.com/shorts/oO8e3NJ34DA', 'https://youtube.com/shorts/oRBiCQG2Cb8', 'https://youtube.com/shorts/qYzVKdWulns',
                        'https://youtube.com/shorts/sO2fm7K2LFY', 'https://youtube.com/shorts/tyvCo_zhxuo', 'https://youtube.com/shorts/ugV6LCh96bY',
                        'https://youtube.com/shorts/uoa3D_F9Gek', 'https://youtube.com/shorts/xfY8f_DZBFE', 'https://youtube.com/shorts/yx0sg34-2qY',
                        'https://youtube.com/shorts/0uoMY7xJRxg', 'https://youtube.com/shorts/2_j0jNDsybE', 'https://youtube.com/shorts/AQ9-CsvQCNI',
                        'https://youtube.com/shorts/BKIkR5OTrT8', 'https://youtube.com/shorts/FnhGMHMiUF4', 'https://youtube.com/shorts/KE48i0UnNuU',
                        'https://youtube.com/shorts/MDM0ZBNDGRM', 'https://youtube.com/shorts/O0vrS2TMXKM', 'https://youtube.com/shorts/OeRyyZPi0f0',
                        'https://youtube.com/shorts/OmPOUsIgAAI', 'https://youtube.com/shorts/ShC9vHtQ1VE', 'https://youtube.com/shorts/Tm4QIjr0W4w',
                        'https://youtube.com/shorts/Vqc9S60EMjo', 'https://youtube.com/shorts/aVjJJut9n4g', 'https://youtube.com/shorts/dUurkUxIu_k',
                        'https://youtube.com/shorts/eeR0xBPcIA4', 'https://youtube.com/shorts/eph63VK4UIk', 'https://youtube.com/shorts/kyUvs-A8BC4',
                        'https://youtube.com/shorts/oQc-zksDnAQ', 'https://youtube.com/shorts/oyE9EAWJyOc', 'https://youtube.com/shorts/pbFwUVX3-lU',
                        'https://youtube.com/shorts/qyONW6UbHjU', 'https://youtube.com/shorts/tpsdzlN5GZs', 'https://youtube.com/shorts/uuZj_7KleGc',
                    ],
                    'POMPES' => [
                        'https://youtube.com/shorts/dtPHZ0Zd-jE', 'https://youtube.com/shorts/egyQnsDcVUU', 'https://youtube.com/shorts/lUg2kVNNrig',
                        'https://youtube.com/shorts/oaWppeQ8xRQ', 'https://youtube.com/shorts/pO1RcFF0LJ0', 'https://youtube.com/shorts/s5Q_6GcVV0U',
                        'https://youtube.com/shorts/tckP3mUPnag', 'https://youtube.com/shorts/vfkz3PKAPPE', 'https://youtube.com/shorts/wSmLXqmTgbQ',
                        'https://youtube.com/shorts/CAr_ugYl40U', 'https://youtube.com/shorts/F8J5wpXtQgM', 'https://youtube.com/shorts/MRpkhhSwto8',
                        'https://youtube.com/shorts/OZV5x6EdOec', 'https://youtube.com/shorts/UHPyledUqzA', 'https://youtube.com/shorts/VJdyNIk3fxs',
                        'https://youtube.com/shorts/WkHojcyM0Ks', 'https://youtube.com/shorts/X6EsXzkbuGk', 'https://youtube.com/shorts/YaiqzdRurO8',
                        'https://youtube.com/shorts/1UXYPbKSvG4', 'https://youtube.com/shorts/B1cysxnPvo8', 'https://youtube.com/shorts/XvbqpamfnrQ',
                        'https://youtube.com/shorts/ZRjQbKkrlvI', 'https://youtube.com/shorts/ZiuSABKUTTc', 'https://youtube.com/shorts/_YLAj1Tylak',
                        'https://youtube.com/shorts/bq9TRYyk-bw', 'https://youtube.com/shorts/c2kpt2hWsi0',
                    ],
                    'GAINAGES' => [
                        'https://youtube.com/shorts/pMZFuFkykmA', 'https://youtube.com/shorts/0knDxost67c', 'https://youtube.com/shorts/0oXYdSIgzCg',
                        'https://youtube.com/shorts/2Hscb3ZaJCA', 'https://youtube.com/shorts/7J6ekvM5ic0', 'https://youtube.com/shorts/7Zo2wnNBZD0',
                        'https://youtube.com/shorts/9KfQAGQWB-0', 'https://youtube.com/shorts/Cx9DJ4-FPIg', 'https://youtube.com/shorts/DMKlmNtCXOg',
                        'https://youtube.com/shorts/FNzuRYM-NVA', 'https://youtube.com/shorts/HTRA7P4yTls', 'https://youtube.com/shorts/IULIkw7tlWo',
                        'https://youtube.com/shorts/JZnLjft0DfI', 'https://youtube.com/shorts/PwOFsR8pVE0', 'https://youtube.com/shorts/V_16CKCoXi8',
                        'https://youtube.com/shorts/X1aqllvkXYs', 'https://youtube.com/shorts/YS38MiAFOLs', 'https://youtube.com/shorts/bBq2AZcvSQQ',
                        'https://youtube.com/shorts/bXtaueKw5pw', 'https://youtube.com/shorts/cT8FDM5s_ys', 'https://youtube.com/shorts/ff0PdTxT9q4',
                        'https://youtube.com/shorts/kiP-mKbvuFQ', 'https://youtube.com/shorts/l3zgH4Jf5Xc', 'https://youtube.com/shorts/m_27_EBCALw',
                        'https://youtube.com/shorts/pFDCTzfUK_Q', 'https://youtube.com/shorts/qRa5Edo9Z-U', 'https://youtube.com/shorts/rqmY4Kkh-9U',
                        'https://youtube.com/shorts/sBCyP_iGJG4', 'https://youtube.com/shorts/txJ3T9Sj0Ck', 'https://youtube.com/shorts/v9keA20vANA',
                        'https://youtube.com/shorts/v9pQzSq_QA8', 'https://youtube.com/shorts/w42JqLa-ZDQ', 'https://youtube.com/shorts/wZ85ALFy7kU',
                        'https://youtube.com/shorts/xZlGJc_nxmU', 'https://youtube.com/shorts/zEI7gHU_Sg8', 'https://youtube.com/shorts/zHFwLnFmxmU',
                    ],
                ]
            ],
            'CARDIO EN SALLE' => [
                'met' => $met_cardio_salle,
                'sub_categories' => [
                    'VELO ENDURANCE' => ['https://youtube.com/shorts/IL2fF-2G1SA'],
                    'VELO PUISSANCE' => ['https://youtube.com/shorts/0G6vdBt6uys'],
                    'TAPIS RÉSISTANCE' => ['https://youtube.com/shorts/KxiO7DfDuO4'],
                    'TAPIS PUISSANCE' => ['https://youtube.com/shorts/1VFwn0chdOM'],
                    'TAPIS ENDURANCE' => ['https://youtube.com/shorts/yQuD5T7MSIY'],
                    'SPRINT EN COTE' => ['https://youtube.com/shorts/xW0n7BhD80A'],
                    'RAMEUR' => ['https://youtube.com/shorts/PqkRs9SLU8U'],
                    'MARCHE' => ['https://youtube.com/shorts/OEnjPm4zPvE'],
                    'MARCHE EN COTE' => ['https://youtube.com/shorts/d1fzhB1glsc'],
                    'ELLIPTIQUE NORMAL' => ['https://youtube.com/shorts/HwZQ9Cz2Igo'],
                    'ELLIPTIQUE INTENSE' => ['https://youtube.com/shorts/60ktrN58nhg'],
                ]
            ],
            'MUSCULATION' => [
                'met' => $met_muscu,
                'sub_categories' => [
                    'BRAS' => [
                        'https://youtube.com/shorts/bwKQtIsbDlc', 'https://youtube.com/shorts/3ORL_3I_yO8', 'https://youtube.com/shorts/4W1Vxinj7Cc',
                        'https://youtube.com/shorts/KpACz0Quf2Y', 'https://youtube.com/shorts/5PMPmzX-pj0', 'https://youtube.com/shorts/FYAxHurQMc8',
                        'https://youtube.com/shorts/k52Y6HiUMnw', 'https://youtube.com/shorts/a-hQ4YvwO3c', 'https://youtube.com/shorts/Qx3RyJ8Ffeg',
                        'https://youtube.com/shorts/1OryNw0bGPA', 'https://youtube.com/shorts/3TkYPAT6YiU', 'https://youtube.com/shorts/IV4TqOD7TSg',
                        'https://youtube.com/shorts/RLmjB3G6G-A', 'https://youtube.com/shorts/EqgF5tl4lsU', 'https://youtube.com/shorts/Q_Ek61tAKZs',
                        'https://youtube.com/shorts/HvA7CaJwh4w', 'https://youtube.com/shorts/C4i700_0vCQ', 'https://youtube.com/shorts/S-ctXxcCbqY',
                        'https://youtube.com/shorts/tqXI1di-Scs', 'https://youtube.com/shorts/2aozd3ce3ew', 'https://youtube.com/shorts/NT8lw0rbm0g',
                        'https://youtube.com/shorts/qra5gCFCebA', 'https://youtube.com/shorts/shPqDLH1QPM', 'https://youtube.com/shorts/ohR_uESI6YY',
                        'https://youtube.com/shorts/uwEsWGuyJ8c', 'https://youtube.com/shorts/9QUpKxQgGUI', 'https://youtube.com/shorts/AL_RrGhqo8M',
                    ],
                    'DOS' => [
                        'https://youtube.com/shorts/kgtw5d42998', 'https://youtube.com/shorts/HrkT2FNWrrg', 'https://youtube.com/shorts/sUGBt0fY77s',
                        'https://youtube.com/shorts/1-YFlMEmDEs', 'https://youtube.com/shorts/QaWpgYmByTM', 'https://youtube.com/shorts/KpzFeoMhPto',
                        'https://youtube.com/shorts/_eMGcaQ5rzs', 'https://youtube.com/shorts/lmasox6N8VA', 'https://youtube.com/shorts/MvueY8B2q4o',
                        'https://youtube.com/shorts/THoeE7EVLLM', 'https://youtube.com/shorts/fsBMsR0lrfk', 'https://youtube.com/shorts/JRVZ06ZqofU',
                        'https://youtube.com/shorts/xJCJVw8rP1A', 'https://youtube.com/shorts/4Sv7HB0ZzIY', 'https://youtube.com/shorts/IG3rs3XoYvY',
                        'https://youtube.com/shorts/EfacqHYrKOs', 'https://youtube.com/shorts/L_a8GC45PHY', 'https://youtube.com/shorts/LqbtL9DpJmU',
                    ],
                    'ÉPAULES' => [
                        'https://youtube.com/shorts/JSbZ3CkeAQg', 'https://youtube.com/shorts/YsNHINdI2s4', 'https://youtube.com/shorts/HJZ7A0VQs6o',
                        'https://youtube.com/shorts/xgIQYtq4Tio', 'https://youtube.com/shorts/gJdp6Ri_dp8', 'https://youtube.com/shorts/DFNKfMe6dcA',
                        'https://youtube.com/shorts/fHCc5uO07lA', 'https://youtube.com/shorts/ebwsDi4DuCM', 'https://youtube.com/shorts/n5FPulqnuzE',
                        'https://youtube.com/shorts/f9Pkth7EP6g', 'https://youtube.com/shorts/au_SOwOCWg4', 'https://youtube.com/shorts/WX7Z7fZ4094',
                        'https://youtube.com/shorts/erBXdVu6ONo', 'https://youtube.com/shorts/lgnUP3dz1P0', 'https://youtube.com/shorts/w7QO6pOHrbk',
                        'https://youtube.com/shorts/gZouEVOnZcg', 'https://youtube.com/shorts/uzun1E5bu8M', 'https://youtube.com/shorts/KqchQZJHdhA',
                        'https://youtube.com/shorts/3xux5bpIY5o', 'https://youtube.com/shorts/RQCuI8n9c34', 'https://youtube.com/shorts/r1VuoHW2bpI',
                        'https://youtube.com/shorts/1MxqyqJcN2Y', 'https://youtube.com/shorts/68nhsTdg_Fg', 'https://youtube.com/shorts/PQUIzw2Taxs',
                        'https://youtube.com/shorts/Ke1etRLIUNw', 'https://youtube.com/shorts/guXsPqtxG8A',
                    ],
                    'JAMBES' => [
                        'https://youtube.com/shorts/7TopI0LAcOc', 'https://youtube.com/shorts/f198IoNb6pU', 'https://youtube.com/shorts/V5zGzu5SFG0',
                        'https://youtube.com/shorts/Z4o9rnutvdA', 'https://youtube.com/shorts/CASNofbIq5o', 'https://youtube.com/shorts/SXpvJG8dz2o',
                        'https://youtube.com/shorts/udV65CGZFzA', 'https://youtube.com/shorts/LdoaQhFjnus', 'https://youtube.com/shorts/WWU_MVyg1Xs',
                        'https://youtube.com/shorts/sCUaH2SAspQ', 'https://youtube.com/shorts/FUs8XyVFQKY', 'https://youtube.com/shorts/sqvrPCMDvHM',
                        'https://youtube.com/shorts/0oQ98CvcAko', 'https://youtube.com/shorts/hCafqJ2lJA8', 'https://youtube.com/shorts/O4odcNAMYVE',
                        'https://youtube.com/shorts/qWqtUctNBQ4', 'https://youtube.com/shorts/HojeFBRoA4U',
                    ],
                    'PECTORAUX' => [
                        'https://youtube.com/shorts/xoXojBVTN_8', 'https://youtube.com/shorts/H_2XGGh-n8s', 'https://youtube.com/shorts/Ze81cO6uzDM',
                        'https://youtube.com/shorts/kvx-XkAp2QM', 'https://youtube.com/shorts/17c7vOMesd4', 'https://youtube.com/shorts/p5AufLpj53c',
                        'https://youtube.com/shorts/k4MpQYpo2b4', 'https://youtube.com/shorts/tRA7T9nVHLE', 'https://youtube.com/shorts/uJD0cFKLg8g',
                        'https://youtube.com/shorts/vEzVopa4-kE', 'https://youtube.com/shorts/3iyM4LpgkOY', 'https://youtube.com/shorts/u3-lb2YLVD4',
                        'https://youtube.com/shorts/LUZRKX-Pw_w', 'https://youtube.com/shorts/xnb6ug1Br2k', 'https://youtube.com/shorts/vsEIWPRnFkw',
                        'https://youtube.com/shorts/DYWP4yR9iR8', 'https://youtube.com/shorts/wLDF0HaiIF8', 'https://youtube.com/shorts/Sty3Vee4yek',
                        'https://youtube.com/shorts/b7S7hVrDUu0', 'https://youtube.com/shorts/N0qMQIR4W6E', 'https://youtube.com/shorts/Raq9icmnwn8',
                        'https://youtube.com/shorts/ryB1eLke_e8', 'https://youtube.com/shorts/efg0MlRq3Ig', 'https://youtube.com/shorts/mUbY2tVEmt0',
                        'https://youtube.com/shorts/tHompI9e5-c', 'https://youtube.com/shorts/0N6WU8-Ko4E',
                    ],
                ]
            ],
        ];

        foreach ($exerciseGroups as $categoryName => $categoryData) {
            $met = $categoryData['met'];
            foreach ($categoryData['sub_categories'] as $subCategoryName => $videos) {
                foreach ($videos as $index => $videoUrl) {
                    Exercise::create([
                        'name' => $subCategoryName . ' ' . ($index + 1),
                        'category' => $categoryName,
                        'sub_category' => $subCategoryName,
                        'video_url' => $videoUrl,
                        'met_value' => $met,
                    ]);
                }
            }
        }
    }
}
