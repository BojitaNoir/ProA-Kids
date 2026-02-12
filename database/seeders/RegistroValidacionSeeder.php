<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegistroValidacion;
use App\Models\User;
use Carbon\Carbon;

class RegistroValidacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin)
            return;

        $logs = [
            [
                'patologia' => 'NEUMONÍA ADQUIRIDA EN LA COMUNIDAD',
                'medicamento' => 'LEVOFLOXACINO 750MG',
                'clcr' => 85.4,
                'resultado' => 'EXITOSO',
                'mensaje_resultado' => 'Terapia segura según criterios institucionales.',
                'created_at' => Carbon::now()->subMinutes(12)
            ],
            [
                'patologia' => 'DENGUE CLÁSICO',
                'medicamento' => 'IBUPROFENO 400MG',
                'clcr' => 92.1,
                'resultado' => 'CRÍTICO',
                'mensaje_resultado' => 'RIESGO DE HEMORRAGIA GRAVE. Los AINEs están contraindicados en Dengue.',
                'created_at' => Carbon::now()->subMinutes(25)
            ],
            [
                'patologia' => 'INSUFICIENCIA RENAL CRÓNICA',
                'medicamento' => 'GENTAMICINA 80MG',
                'clcr' => 12.5,
                'resultado' => 'CRÍTICO',
                'mensaje_resultado' => 'CONTRAINDICADO. Paciente en falla renal terminal.',
                'created_at' => Carbon::now()->subHours(1)
            ],
            [
                'patologia' => 'INFECCIÓN DE VÍAS URINARIAS',
                'medicamento' => 'CEFTRIAXONA 1G',
                'clcr' => 45.2,
                'resultado' => 'ALERTA',
                'mensaje_resultado' => 'Función renal moderadamente disminuida. Verificar dosis.',
                'created_at' => Carbon::now()->subHours(2)
            ],
            [
                'patologia' => 'ABSCESO DENTAL',
                'medicamento' => 'CLINDAMICINA 300MG',
                'clcr' => 105.0,
                'resultado' => 'EXITOSO',
                'mensaje_resultado' => 'Terapia segura según criterios institucionales.',
                'created_at' => Carbon::now()->subHours(3)
            ],
            [
                'patologia' => 'FARINGOAMIGDALITIS BACTERIANA',
                'medicamento' => 'AMOXICILINA + ÁCIDO CLAVULÁNICO',
                'clcr' => 88.7,
                'resultado' => 'EXITOSO',
                'mensaje_resultado' => 'Terapia segura según criterios institucionales.',
                'created_at' => Carbon::now()->subDays(1)
            ],
            [
                'patologia' => 'DIABETES MELLITUS + PIE DIABÉTICO',
                'medicamento' => 'VANCOMICINA 1G',
                'clcr' => 38.5,
                'resultado' => 'ALERTA',
                'mensaje_resultado' => 'Función renal moderadamente disminuida. Ajuste de dosis requerido.',
                'created_at' => Carbon::now()->subDays(1)->subHours(5)
            ],
            [
                'patologia' => 'HIPERTENSIÓN ARTERIAL',
                'medicamento' => 'ENALAPRIL 10MG',
                'clcr' => 74.2,
                'resultado' => 'EXITOSO',
                'mensaje_resultado' => 'Terapia segura.',
                'created_at' => Carbon::now()->subDays(2)
            ]
        ];

        foreach ($logs as $log) {
            RegistroValidacion::create(array_merge($log, ['user_id' => $admin->id]));
        }
    }
}
