<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restablecer contraseña</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen grid place-items-center bg-gray-50 p-6">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">
        <h1 class="text-2xl font-bold text-gray-800 text-center">Restablecer contraseña</h1>
        <p class="mt-2 text-center text-gray-600">Ingresa tu nueva contraseña</p>

        <div id="alert" class="hidden mt-6 rounded-xl border p-4 text-sm"></div>

        <form id="resetForm" class="mt-6 space-y-5">
            @csrf
            <input type="hidden" id="token" name="token" value="{{ request('token') }}">
            <input type="hidden" id="email" name="email" value="{{ request('email') }}">

            <div>
                <label class="block text-sm font-medium text-gray-700" for="contrasena">Nueva contraseña</label>
                <input class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-4 focus:ring-blue-200" type="password" id="contrasena" name="contrasena" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="contrasena_confirmation">Confirmar contraseña</label>
                <input class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-4 focus:ring-blue-200" type="password" id="contrasena_confirmation" name="contrasena_confirmation" required>
            </div>

            <button id="submitBtn" type="submit" class="w-full rounded-xl bg-blue-700 text-white font-semibold py-3 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">Actualizar contraseña</button>
        </form>

        <div id="response" class="mt-6 hidden" aria-live="polite">
            <pre class="whitespace-pre-wrap text-sm font-mono bg-gray-50 p-3 rounded-xl border"></pre>
        </div>
    </div>

    <script>
        $(function(){
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' } });
            function show(kind, msg){
                const el = $('#alert');
                el.removeClass('hidden border-red-200 bg-red-50 text-red-800 border-green-200 bg-green-50 text-green-800');
                if(kind==='ok') el.addClass('border-green-200 bg-green-50 text-green-800');
                if(kind==='error') el.addClass('border-red-200 bg-red-50 text-red-800');
                el.text(msg);
            }

            $('#resetForm').on('submit', function(e){
                e.preventDefault();
                const data = {
                    token: $('#token').val(),
                    email: $('#email').val(),
                    contrasena: $('#contrasena').val(),
                    contrasena_confirmation: $('#contrasena_confirmation').val()
                };
                $.ajax({
                    url: '/api/reset-password',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(resp){
                        show('ok','Contraseña actualizada. Ya puedes iniciar sesión.');
                        $('#response').removeClass('hidden').find('pre').text(JSON.stringify(resp,null,2));
                    },
                    error: function(xhr){
                        const msg = (xhr.responseJSON && (xhr.responseJSON.error||xhr.responseJSON.message)) || 'No se pudo actualizar la contraseña';
                        show('error',msg);
                        $('#response').removeClass('hidden').find('pre').text(JSON.stringify(xhr.responseJSON || xhr.responseText, null, 2));
                    }
                });
            });
        });
    </script>
</body>
</html>
