<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar usuarios de demostración (Administrador y Médicos)
        DB::table('users')->insert([
            [
                'name' => 'Dr. Admin Institucional',
                'email' => 'admin@vigimed.hnm.gob.mx',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Juan Pérez (Pediatría)',
                'email' => 'juan@vigimed.hnm.gob.mx',
                'password' => bcrypt('password'),
                'role' => 'doctor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dra. Elena García (Medicina Interna)',
                'email' => 'elena@vigimed.hnm.gob.mx',
                'password' => bcrypt('password'),
                'role' => 'doctor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Pedro Sánchez (Urgencias)',
                'email' => 'pedro@vigimed.hnm.gob.mx',
                'password' => bcrypt('password'),
                'role' => 'doctor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insertar patologías
        $dengue = DB::table('patologias')->insertGetId([
            'nombre' => 'Dengue',
            'alerta_especifica' => 'Riesgo de hemorragia. Evitar antiinflamatorios no esteroideos (AINEs).',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $migrana = DB::table('patologias')->insertGetId([
            'nombre' => 'Migraña',
            'alerta_especifica' => 'Considerar contraindicaciones cardiovasculares.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar medicamentos
        $paracetamol = DB::table('medicamentos')->insertGetId([
            'nombre' => 'Paracetamol',
            'familia' => 'Analgésico',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $ibuprofeno = DB::table('medicamentos')->insertGetId([
            'nombre' => 'Ibuprofeno',
            'familia' => 'AINE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar alerta de peligro: Dengue + Ibuprofeno
        /**
         * IMPORTANCIA CLÍNICA CRÍTICA:
         * 
         * El Dengue es una enfermedad viral que causa trombocitopenia (disminución de plaquetas)
         * y aumenta significativamente el riesgo de hemorragia. Los AINEs como el Ibuprofeno
         * tienen efecto antiagregante plaquetario, lo que POTENCIA el riesgo hemorrágico.
         * 
         * Esta combinación puede resultar en:
         * - Hemorragia gastrointestinal
         * - Sangrado de mucosas
         * - Hemorragia cerebral (en casos graves)
         * - Shock hemorrágico potencialmente mortal
         * 
         * Por esta razón, el uso de AINEs está ABSOLUTAMENTE CONTRAINDICADO en pacientes
         * con Dengue. El analgésico de elección es el Paracetamol.
         */
        DB::table('alertas_peligro')->insert([
            'patologia_id' => $dengue,
            'medicamento_id' => $ibuprofeno,
            'mensaje_error' => '⚠️ ALERTA CRÍTICA DE SEGURIDAD: El uso de Ibuprofeno (AINE) está CONTRAINDICADO en pacientes con Dengue debido al alto riesgo de hemorragia severa. Use Paracetamol como alternativa segura.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
