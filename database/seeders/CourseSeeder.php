<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create levels
        $beginnerLevel = Level::firstOrCreate(
            ['name' => 'Principiante'],
            [
                'name' => 'Principiante',
                'description' => 'Nivel básico para estudiantes que comienzan desde cero',
                'slug' => 'principiante',
                'status' => 'active'
            ]
        );
        $intermediateLevel = Level::firstOrCreate(
            ['name' => 'Intermedio'],
            [
                'name' => 'Intermedio', 
                'description' => 'Nivel intermedio para estudiantes con conocimientos básicos',
                'slug' => 'intermedio',
                'status' => 'active'
            ]
        );
        $advancedLevel = Level::firstOrCreate(
            ['name' => 'Avanzado'],
            [
                'name' => 'Avanzado',
                'description' => 'Nivel avanzado para estudiantes con sólidos conocimientos',
                'slug' => 'avanzado',
                'status' => 'active'
            ]
        );
        $expertLevel = Level::firstOrCreate(
            ['name' => 'Experto'],
            [
                'name' => 'Experto',
                'description' => 'Nivel experto para dominio completo del idioma',
                'slug' => 'experto',
                'status' => 'active'
            ]
        );

        // Get teacher user (assuming teacher role exists)
        $teacher = User::whereHas('roles', function($query) {
            $query->where('name', 'teacher');
        })->first();

        if (!$teacher) {
            // Create a teacher if none exists
            $teacher = User::create([
                'name' => 'Profesor Demo',
                'email' => 'teacher@example.com',
                'password' => bcrypt('password'),
                'country' => 'MX',
                'state' => 'CDMX',
                'city' => 'Ciudad de México'
            ]);
            $teacher->assignRole('teacher');
        }

        // Course 1: Inglés Básico
        $course1 = Course::create([
            'title' => 'Inglés Básico para Principiantes',
            'description' => 'Aprende inglés desde cero con este curso completo para principiantes.',
            'content' => 'Este curso está diseñado para personas que nunca han estudiado inglés o que tienen un nivel muy básico. Aprenderás:

• Alfabeto y pronunciación
• Números y colores
• Saludos y presentaciones
• Vocabulario básico del hogar
• Presente simple
• Construcción de oraciones básicas
• Conversaciones cotidianas

Al finalizar este curso podrás:
- Presentarte en inglés
- Hacer preguntas básicas
- Entender conversaciones simples
- Escribir oraciones cortas
- Contar números y deletrear palabras',
            'level_id' => $beginnerLevel->id,
            'instructor_id' => $teacher->id,
            'status' => 'active',
            'order' => 1,
            'slug' => 'ingles-basico-principiantes',
            'duration' => 600, // 10 hours
            'type' => 'course',
            'completion_points' => 100,
            'price' => 99.99,
            'currency' => 'USD',
            'is_free' => false
        ]);

        // Course 2: Inglés Intermedio
        $course2 = Course::create([
            'title' => 'Inglés Intermedio - Conversación',
            'description' => 'Mejora tu nivel de inglés con conversaciones prácticas y gramática intermedia.',
            'content' => 'Este curso está dirigido a estudiantes que ya tienen conocimientos básicos de inglés y quieren avanzar al siguiente nivel. Cubriremos:

• Tiempos verbales intermedios (pasado, futuro)
• Conversaciones más complejas
• Vocabulario de trabajo y estudios
• Comprensión auditiva avanzada
• Expresiones idiomáticas comunes
• Escritura de párrafos y ensayos cortos

Al completar este curso podrás:
- Mantener conversaciones fluidas
- Entender películas y series básicas
- Escribir textos más elaborados
- Expresar opiniones y sentimientos
- Manejar situaciones cotidianas en inglés',
            'level_id' => $intermediateLevel->id,
            'instructor_id' => $teacher->id,
            'status' => 'active',
            'order' => 2,
            'slug' => 'ingles-intermedio-conversacion',
            'duration' => 720, // 12 hours
            'type' => 'course',
            'completion_points' => 150,
            'price' => 149.99,
            'currency' => 'USD',
            'is_free' => false
        ]);

        // Course 3: Inglés Avanzado
        $course3 = Course::create([
            'title' => 'Inglés Avanzado - Negocios y Académico',
            'description' => 'Domina el inglés para contextos profesionales y académicos.',
            'content' => 'Este curso avanzado está diseñado para estudiantes que buscan perfeccionar su inglés para uso profesional y académico:

• Inglés de negocios y presentaciones
• Escritura académica y reportes
• Negociación y reuniones en inglés
• Comprensión de textos técnicos
• Debate y argumentación
• Preparación para exámenes internacionales

Objetivos del curso:
- Comunicarse efectivamente en entornos profesionales
- Escribir reportes y documentos formales
- Participar en reuniones y conferencias
- Entender literatura y textos académicos
- Prepararse para certificaciones internacionales',
            'level_id' => $advancedLevel->id,
            'instructor_id' => $teacher->id,
            'status' => 'active',
            'order' => 3,
            'slug' => 'ingles-avanzado-negocios-academico',
            'duration' => 900, // 15 hours
            'type' => 'course',
            'completion_points' => 200,
            'price' => 199.99,
            'currency' => 'USD',
            'is_free' => false
        ]);

        // Course 4: Inglés Experto
        $course4 = Course::create([
            'title' => 'Inglés Experto - Maestría del Idioma',
            'description' => 'Alcanza la maestría completa del inglés con este curso especializado.',
            'content' => 'El curso más avanzado de nuestra plataforma, diseñado para estudiantes que buscan la excelencia:

• Literatura inglesa y análisis crítico
• Inglés técnico especializado
• Comunicación intercultural
• Enseñanza del inglés (metodología)
• Traducción e interpretación
• Inglés creativo y escritura avanzada

Al dominar este nivel serás capaz de:
- Enseñar inglés a otros estudiantes
- Trabajar como traductor o intérprete
- Analizar textos literarios complejos
- Comunicarte al nivel de un hablante nativo
- Crear contenido original en inglés
- Preparar a otros para exámenes internacionales',
            'level_id' => $expertLevel->id,
            'instructor_id' => $teacher->id,
            'status' => 'active',
            'order' => 4,
            'slug' => 'ingles-experto-maestria-idioma',
            'duration' => 1200, // 20 hours
            'type' => 'course',
            'completion_points' => 300,
            'price' => 299.99,
            'currency' => 'USD',
            'is_free' => false
        ]);

        echo "4 cursos creados exitosamente.\n";
    }
}
