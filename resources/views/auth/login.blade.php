<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Test</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Tailwind CDN para estilos rápidos -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login Test</h2>
            
            <!-- Formulario -->
            <form id="loginForm" class="space-y-4">
                @csrf
                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700">Correo</label>
                    <input type="email" id="correo" name="correo" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="contrasena" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Iniciar Sesión
                </button>
            </form>

            <!-- Área para mostrar respuestas -->
            <div id="response" class="mt-4 p-4 rounded-md hidden">
                <pre class="whitespace-pre-wrap text-sm"></pre>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Configure CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                xhrFields: {
                    withCredentials: true
                }
            });

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                // Obtener datos del formulario
                const data = {
                    correo: $('#correo').val(),
                    contrasena: $('#contrasena').val()
                };

                // Realizar la petición AJAX
                $.ajax({
                    url: '/api/login',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(response) {
                        $('#response')
                            .removeClass('hidden bg-red-100')
                            .addClass('bg-green-100')
                            .find('pre')
                            .text(JSON.stringify(response, null, 2));
                    },
                    error: function(xhr) {
                        $('#response')
                            .removeClass('hidden bg-green-100')
                            .addClass('bg-red-100')
                            .find('pre')
                            .text(JSON.stringify(xhr.responseJSON || xhr.responseText, null, 2));
                    }
                });
            });
        });
    </script>
</body>
</html>