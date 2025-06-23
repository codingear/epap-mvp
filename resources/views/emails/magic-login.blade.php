@component('mail::message')
# ¡Hola {{ $user->name }}! ✨

¡Buenas noticias! Aquí tienes tu enlace mágico para acceder a tu cuenta sin necesidad de contraseña.

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Entrar con Magia ✨
@endcomponent

Este enlace será válido por 1 hora. Si no has solicitado este enlace, puedes ignorar este mensaje.

Saludos mágicos,<br>
{{ config('app.name') }}

<small>Este enlace expira en 60 minutos.</small>
@endcomponent
