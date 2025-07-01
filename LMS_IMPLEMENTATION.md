# Sistema LMS con Pagos dLocal - EPAP

Este proyecto implementa un sistema completo de Learning Management System (LMS) con integración de pagos através de dLocal para la plataforma EPAP.

## 🚀 Funcionalidades Implementadas

### 💳 Sistema de Pagos con dLocal
- Integración completa con dLocal para procesamiento de pagos
- Soporte para tarjetas de crédito/débito y OXXO
- Webhook para notificaciones de estado de pago
- Historial completo de transacciones
- Manejo de diferentes monedas

### 📚 Sistema LMS Completo
- **4 Cursos de Inglés** con 25 lecciones cada uno:
  1. Inglés Básico para Principiantes ($99.99)
  2. Inglés Intermedio - Conversación ($149.99)  
  3. Inglés Avanzado - Negocios y Académico ($199.99)
  4. Inglés Experto - Maestría del Idioma ($299.99)

### 🎓 Gestión de Cursos y Lecciones
- Visualización de cursos con filtros por nivel y precio
- Lecciones progresivas que se desbloquean secuencialmente
- Sistema de progreso por lección y curso
- Primera lección gratuita como vista previa
- Soporte para videos de YouTube/Vimeo y archivos locales
- Materiales descargables (PDFs, documentos, imágenes)

### 💬 Sistema de Comentarios y Reviews
- Comentarios por lección
- Reviews y calificaciones por curso
- Reviews de instructores
- Sistema de ratings con estrellas

### 📁 Gestión de Materiales
- Subida de archivos para instructores
- Descarga protegida para estudiantes inscritos
- Soporte múltiples formatos (PDF, DOC, PPT, XLS, imágenes, videos)
- Almacenamiento seguro en directorio privado

### 👤 Gestión de Usuarios
- Seguimiento de progreso individual
- Historial de compras
- Cursos completados y en progreso
- Dashboard personalizado por rol

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 12 con PHP 8.2+
- **Base de Datos**: MySQL con migraciones completas
- **Frontend**: Blade templates con TailwindCSS
- **Pagos**: Integración dLocal
- **Storage**: Sistema de archivos Laravel para materiales

## 📦 Estructura de Base de Datos

### Nuevas Tablas Creadas:
- `payments` - Gestión de pagos y transacciones
- `lessons` - Lecciones individuales de los cursos  
- `lesson_user` - Tabla pivot para progreso de lecciones
- `comments` - Sistema de comentarios y reviews
- `lesson_materials` - Materiales descargables por lección

### Campos Agregados:
- `courses`: `price`, `currency`, `is_free`

## 🚀 Instalación y Configuración

1. **Variables de Entorno dLocal:**
```env
DLOCAL_ENVIRONMENT=sandbox
DLOCAL_API_KEY=tu_api_key
DLOCAL_SECRET_KEY=tu_secret_key
DLOCAL_WEBHOOK_SECRET=tu_webhook_secret
```

2. **Ejecutar Migraciones:**
```bash
php artisan migrate
```

3. **Ejecutar Seeders:**
```bash
php artisan db:seed --class=CourseSeeder
php artisan db:seed --class=LessonSeeder
```

## 🎯 Rutas Principales

### Cursos y LMS:
- `GET /courses` - Lista de cursos disponibles
- `GET /courses/{course}` - Detalles del curso
- `GET /courses/{course}/curriculum` - Lista de lecciones
- `GET /courses/{course}/lessons/{lesson}` - Vista de lección individual

### Pagos:
- `GET /courses/{course}/checkout` - Página de pago
- `POST /courses/{course}/payment` - Procesar pago
- `GET /payment/history` - Historial de pagos
- `POST /payment/webhook` - Webhook dLocal

### Materiales:
- `GET /materials/{material}/download` - Descarga de materiales
- `POST /materials/upload` - Subida de materiales (instructores)

## 💡 Características Destacadas

### 🔒 Seguridad
- Control de acceso basado en compras
- Descarga protegida de materiales
- Validación de permisos por rol
- Verificación de webhooks

### 📊 Tracking y Analytics
- Progreso detallado por lección
- Tiempo de finalización de cursos
- Estadísticas de engagement
- Reportes de ventas

### 🎨 UX/UI
- Diseño responsive con TailwindCSS
- Progreso visual con barras animadas
- Interfaz intuitiva para estudiantes
- Dashboard separado por roles

### 🔄 Funcionalidades Avanzadas
- Reproducción automática de progreso en videos
- Navegación secuencial entre lecciones
- Sistema de notificaciones en tiempo real
- Integración con player de YouTube/Vimeo

## 📈 Próximas Mejoras Sugeridas

1. **Certificados de Finalización** - Generar PDFs automáticos
2. **Quizzes y Evaluaciones** - Sistema de examenes
3. **Live Classes** - Integración con Zoom/Teams  
4. **Mobile App** - Aplicación nativa
5. **Gamificación** - Puntos, badges y rankings
6. **Analytics Avanzados** - Dashboard de métricas detalladas

## 🤝 Soporte

Para cualquier duda o problema con la implementación, contacta al equipo de desarrollo.

---

**Desarrollado para EPAP - Sistema de Aprendizaje de Inglés** 🌟
