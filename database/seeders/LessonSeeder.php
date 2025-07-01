<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            // Create 25 lessons for each course
            for ($i = 1; $i <= 25; $i++) {
                $lesson = Lesson::create([
                    'course_id' => $course->id,
                    'title' => $this->getLessonTitle($course->id, $i),
                    'description' => $this->getLessonDescription($course->id, $i),
                    'content' => $this->getLessonContent($course->id, $i),
                    'video_url' => $this->getVideoUrl($i),
                    'order' => $i,
                    'duration' => rand(15, 45), // 15-45 minutes per lesson
                    'status' => 'active',
                    'is_free' => $i === 1 // First lesson is always free for preview
                ]);
            }
        }

        echo "Lecciones creadas para todos los cursos.\n";
    }

    private function getLessonTitle($courseId, $lessonNumber)
    {
        $titles = [
            1 => [ // Inglés Básico
                1 => 'Introducción al Inglés - El Alfabeto',
                2 => 'Números del 1 al 100',
                3 => 'Colores y Formas Básicas',
                4 => 'Saludos y Despedidas',
                5 => 'Presentaciones Personales',
                6 => 'La Familia en Inglés',
                7 => 'Partes del Cuerpo',
                8 => 'Ropa y Accesorios',
                9 => 'Comida y Bebidas',
                10 => 'La Casa y sus Habitaciones',
                11 => 'Días de la Semana y Meses',
                12 => 'El Tiempo y el Clima',
                13 => 'Presente Simple - Verbo To Be',
                14 => 'Presente Simple - Verbos Regulares',
                15 => 'Artículos A, An, The',
                16 => 'Adjetivos Básicos',
                17 => 'Preguntas con WH (What, Where, When)',
                18 => 'Plurales en Inglés',
                19 => 'Posesivos (My, Your, His, Her)',
                20 => 'Preposiciones de Lugar',
                21 => 'Expresiones de Tiempo',
                22 => 'En el Supermercado',
                23 => 'En el Restaurante',
                24 => 'Direcciones y Ubicaciones',
                25 => 'Repaso General y Evaluación'
            ],
            2 => [ // Inglés Intermedio
                1 => 'Repaso de Fundamentos',
                2 => 'Pasado Simple - Verbos Regulares',
                3 => 'Pasado Simple - Verbos Irregulares',
                4 => 'Pasado Continuo',
                5 => 'Futuro Simple (Will)',
                6 => 'Futuro con Going To',
                7 => 'Presente Perfecto',
                8 => 'Comparativos y Superlativos',
                9 => 'Modales: Can, Could, May, Might',
                10 => 'Modales: Should, Must, Have To',
                11 => 'Condicionales Tipo 1',
                12 => 'Condicionales Tipo 2',
                13 => 'Voz Pasiva - Presente',
                14 => 'Voz Pasiva - Pasado',
                15 => 'Phrasal Verbs Comunes',
                16 => 'Vocabulario de Trabajo',
                17 => 'Entrevistas de Trabajo',
                18 => 'Viajes y Turismo',
                19 => 'Tecnología y Redes Sociales',
                20 => 'Salud y Ejercicio',
                21 => 'Entretenimiento y Hobbies',
                22 => 'Noticias y Eventos Actuales',
                23 => 'Expresando Opiniones',
                24 => 'Conversación Telefónica',
                25 => 'Proyecto Final de Conversación'
            ],
            3 => [ // Inglés Avanzado
                1 => 'Evaluación de Nivel',
                2 => 'Inglés de Negocios - Reuniones',
                3 => 'Presentaciones Profesionales',
                4 => 'Negociación en Inglés',
                5 => 'Emails Formales',
                6 => 'Reportes y Documentos',
                7 => 'Análisis de Datos',
                8 => 'Marketing y Ventas',
                9 => 'Recursos Humanos',
                10 => 'Finanzas y Contabilidad',
                11 => 'Escritura Académica - Ensayos',
                12 => 'Escritura Académica - Investigación',
                13 => 'Comprensión de Textos Técnicos',
                14 => 'Debates Formales',
                15 => 'Argumentación y Retórica',
                16 => 'Literatura en Inglés',
                17 => 'Análisis Crítico',
                18 => 'Preparación TOEFL',
                19 => 'Preparación IELTS',
                20 => 'Inglés Científico',
                21 => 'Inglés Médico',
                22 => 'Inglés Legal',
                23 => 'Inglés de Ingeniería',
                24 => 'Proyecto de Investigación',
                25 => 'Defensa de Tesis'
            ],
            4 => [ // Inglés Experto
                1 => 'Evaluación Exhaustiva',
                2 => 'Historia de la Lengua Inglesa',
                3 => 'Variaciones Dialectales',
                4 => 'Inglés Británico vs Americano',
                5 => 'Literatura Clásica',
                6 => 'Literatura Contemporánea',
                7 => 'Poesía y Análisis Métrico',
                8 => 'Teatro y Drama',
                9 => 'Ensayo Crítico Avanzado',
                10 => 'Teoría de la Traducción',
                11 => 'Práctica de Traducción',
                12 => 'Interpretación Simultánea',
                13 => 'Interpretación Consecutiva',
                14 => 'Metodología de Enseñanza',
                15 => 'Desarrollo de Curriculum',
                16 => 'Evaluación y Assessment',
                17 => 'Tecnología Educativa',
                18 => 'Inglés Creativo - Escritura',
                19 => 'Inglés Creativo - Narrativa',
                20 => 'Comunicación Intercultural',
                21 => 'Inglés para Propósitos Específicos',
                22 => 'Investigación Lingüística',
                23 => 'Tesis de Maestría',
                24 => 'Proyecto Final Integrador',
                25 => 'Certificación de Maestría'
            ]
        ];

        return $titles[$courseId][$lessonNumber] ?? "Lección $lessonNumber";
    }

    private function getLessonDescription($courseId, $lessonNumber)
    {
        $descriptions = [
            1 => "Descripción detallada de la lección $lessonNumber del curso básico de inglés.",
            2 => "Contenido intermedio para fortalecer tus habilidades en inglés - Lección $lessonNumber.",
            3 => "Lección avanzada $lessonNumber enfocada en inglés profesional y académico.",
            4 => "Contenido de nivel experto para el dominio completo del idioma - Lección $lessonNumber."
        ];

        return $descriptions[$courseId] ?? "Descripción de la lección $lessonNumber.";
    }

    private function getLessonContent($courseId, $lessonNumber)
    {
        $baseContent = [
            1 => "En esta lección de nivel básico aprenderás conceptos fundamentales del inglés. Practicaremos pronunciación, vocabulario esencial y estructuras gramaticales simples.",
            2 => "Esta lección intermedia te ayudará a consolidar conocimientos previos mientras introduces nuevos conceptos más complejos. Incluye ejercicios prácticos y conversaciones.",
            3 => "Lección avanzada que cubre temas especializados del inglés profesional. Desarrollarás habilidades de comunicación formal y análisis de textos complejos.",
            4 => "Contenido de nivel experto que te permitirá alcanzar la maestría del idioma. Incluye análisis profundo, práctica avanzada y aplicaciones especializadas."
        ];

        $content = $baseContent[$courseId] ?? "Contenido de la lección.";
        
        return $content . "\n\nObjetivos de la lección:\n• Dominar el vocabulario específico\n• Practicar estructuras gramaticales\n• Desarrollar habilidades de comprensión\n• Aplicar conocimientos en contextos reales\n\nActividades incluidas:\n• Ejercicios interactivos\n• Práctica de pronunciación\n• Comprensión auditiva\n• Ejercicios escritos";
    }

    private function getVideoUrl($lessonNumber)
    {
        // Demo YouTube videos for each lesson
        $videoIds = [
            1 => 'dQw4w9WgXcQ', // Rick Roll for demo
            2 => 'kJQP7kiw5Fk',
            3 => 'BaW_jenozKc',
            4 => 'fJ9rUzIMcZQ',
            5 => 'GtL1huin9EE'
        ];
        
        $videoId = $videoIds[($lessonNumber % 5) + 1] ?? $videoIds[1];
        
        return "https://www.youtube.com/watch?v=$videoId";
    }
}
