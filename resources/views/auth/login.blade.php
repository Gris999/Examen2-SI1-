<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Universitario - Acceso</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Crimson+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --brand: #1e40af; --brand-2: #0ea5e9; }
        body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"; }
        .bg-grid {
            background-image: radial-gradient(rgba(255,255,255,.12) 1px, transparent 1px);
            background-size: 18px 18px;
            background-position: -10px -10px;
        }
        .glass {
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.6);
        }
        .logo-badge {
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.15);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 relative overflow-x-hidden">
    <!-- Decoración -->
    <div class="pointer-events-none absolute inset-0 bg-grid opacity-40"></div>
    <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 -right-24 w-[28rem] h-[28rem] rounded-full bg-sky-400/20 blur-3xl"></div>

    <main class="relative z-10 mx-auto max-w-6xl px-4 py-10">
        <div class="grid lg:grid-cols-2 gap-8 items-stretch">
            <!-- Panel visual -->
            <section class="hidden lg:flex flex-col justify-between rounded-3xl p-10 border border-white/10 glass text-white">
                <div>
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 rounded-2xl bg-white text-[1.6rem] font-extrabold text-blue-800 grid place-items-center logo-badge">UNI</div>
                        <div>
                            <h1 class="text-2xl font-semibold tracking-tight">Portal Universitario</h1>
                            <p class="text-white/80">Sistema Académico y Servicios</p>
                        </div>
                    </div>
                    <p class="mt-8 text-white/90 leading-relaxed">
                        Accede a tu cuenta para gestionar cursos, horarios, evaluaciones y más.
                        Mantén tu información segura y actualizada.
                    </p>
                </div>
                <ul class="mt-8 space-y-3 text-white/90">
                    <li class="flex items-center gap-2"><span class="text-sky-200">•</span> Autenticación segura con tokens</li>
                    <li class="flex items-center gap-2"><span class="text-sky-200">•</span> Compatible con dispositivos móviles</li>
                    <li class="flex items-center gap-2"><span class="text-sky-200">•</span> Soporte técnico 24/7</li>
                </ul>
                <div class="mt-8 text-xs text-white/70">© {{ date('Y') }} Universidad Nacional. Todos los derechos reservados.</div>
            </section>

            <!-- Panel de login -->
            <section class="glass rounded-3xl border border-white/20 shadow-2xl p-8 sm:p-10">
                <div class="mx-auto max-w-md">
                    <div class="flex items-center justify-center">
                        <div class="h-20 w-20 rounded-full bg-white text-3xl font-extrabold text-blue-800 grid place-items-center logo-badge">UNI</div>
                    </div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-800">Bienvenido(a)</h2>
                    <p class="mt-1 text-center text-gray-600">Acceso al Sistema Académico</p>

                    <!-- Alerts -->
                    <div id="alert" class="hidden mt-6 rounded-xl border p-4 text-sm"></div>

                    <form id="loginForm" class="mt-6 space-y-5" autocomplete="on">
                        @csrf
                        <div>
                            <label for="correo" class="block text-sm font-medium text-gray-700">Correo institucional</label>
                            <div class="relative mt-2">
                                <input type="email" id="correo" name="correo" required
                                       class="peer w-full rounded-xl border border-gray-300 bg-gray-50/80 px-4 py-3 pr-11 text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-200"
                                       placeholder="usuario@universidad.edu">
                                <svg class="pointer-events-none absolute right-3 top-3.5 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label for="contrasena" class="block text-sm font-medium text-gray-700">Contraseña</label>
                            <div class="relative mt-2">
                                <input type="password" id="contrasena" name="contrasena" required
                                       class="peer w-full rounded-xl border border-gray-300 bg-gray-50/80 px-4 py-3 pr-11 text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-200"
                                       placeholder="••••••••">
                                <button type="button" id="togglePass" class="absolute right-3 top-3.5 h-6 w-6 text-gray-400 hover:text-gray-600" aria-label="Mostrar u ocultar contraseña">
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-600 select-none">
                                <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                Recordar sesión
                            </label>
                            <a href="/reset-password" class="text-sm font-medium text-blue-700 hover:text-blue-800">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" id="submitBtn"
                                class="group relative inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-700 to-sky-600 px-4 py-3 text-base font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:from-blue-800 hover:to-sky-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                            <span>Iniciar Sesión</span>
                            <svg class="h-5 w-5 transition-transform group-hover:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </button>

                        <p class="text-center text-xs text-gray-500">Al continuar aceptas las políticas de uso del sistema.</p>
                    </form>

                    <!-- Respuesta -->
                    <div id="response" class="mt-6 hidden" aria-live="polite">
                        <pre class="whitespace-pre-wrap text-sm font-mono bg-gray-50 p-3 rounded-xl border"></pre>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        $(function() {
            // CSRF + headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            });

            // Mostrar / ocultar contraseña
            $('#togglePass').on('click', function(){
                const input = $('#contrasena');
                const isPwd = input.attr('type') === 'password';
                input.attr('type', isPwd ? 'text' : 'password');
                $('#eyeIcon').html(isPwd
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.51-4.205M6.98 6.98A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.043 5.066M15 12a3 3 0 00-3-3"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
                );
            });

            function showAlert(kind, message){
                const box = $('#alert');
                box.removeClass('hidden border-red-200 bg-red-50 text-red-800 border-green-200 bg-green-50 text-green-800');
                if(kind === 'error') box.addClass('border-red-200 bg-red-50 text-red-800');
                if(kind === 'ok') box.addClass('border-green-200 bg-green-50 text-green-800');
                box.text(message);
            }

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#submitBtn');
                btn.prop('disabled', true).addClass('opacity-80 cursor-not-allowed').prepend('<span id="ld" class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/60 border-t-transparent"></span>');

                const data = {
                    correo: $('#correo').val(),
                    contrasena: $('#contrasena').val()
                };

                $.ajax({
                    url: '/api/login',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(response) {
                        showAlert('ok', 'Inicio de sesión correcto.');
                        $('#response').removeClass('hidden').find('pre').text(JSON.stringify(response, null, 2));
                    },
                    error: function(xhr) {
                        const msg = (xhr.responseJSON && (xhr.responseJSON.error || xhr.responseJSON.message)) || 'No se pudo iniciar sesión.';
                        showAlert('error', msg);
                        $('#response').removeClass('hidden').find('pre').text(JSON.stringify(xhr.responseJSON || xhr.responseText, null, 2));
                    },
                    complete: function(){
                        $('#ld').remove();
                        btn.prop('disabled', false).removeClass('opacity-80 cursor-not-allowed');
                    }
                });
            });
        });
    </script>
</body>
</html>
