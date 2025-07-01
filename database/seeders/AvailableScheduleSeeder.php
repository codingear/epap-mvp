<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AvailableSchedule;
use App\Models\User;
use Carbon\Carbon;

class AvailableScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ya no necesitamos crear horarios por defecto porque se generan dinámicamente
        // Solo creamos algunos bloqueos de ejemplo para demostrar la funcionalidad
        
        $tomorrow = Carbon::tomorrow();
        $dayAfterTomorrow = Carbon::tomorrow()->addDay();

        // Crear algunos bloqueos de ejemplo
        \App\Models\ScheduleBlock::create([
            'date' => $tomorrow->format('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'type' => 'time_range',
            'reason' => 'Reunión de equipo',
            'notes' => 'Reunión semanal del equipo docente'
        ]);

        \App\Models\ScheduleBlock::create([
            'date' => $dayAfterTomorrow->format('Y-m-d'),
            'start_time' => '10:00:00',
            'type' => 'single_slot',
            'reason' => 'Mantenimiento técnico',
            'notes' => 'Mantenimiento programado del sistema'
        ]);

        $this->command->info('Sistema de horarios dinámicos configurado exitosamente');
        $this->command->info('Horarios disponibles: Lunes a Viernes de 9:00 AM a 7:00 PM');
        $this->command->info('Se crearon algunos bloqueos de ejemplo para demostración');
    }
}
