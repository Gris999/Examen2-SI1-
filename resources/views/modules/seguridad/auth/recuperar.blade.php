<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar contraseña</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen grid place-items-center bg-gray-50 p-6">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">
        <h1 class="text-2xl font-bold text-gray-800 text-center">Recuperar contraseña</h1>
        <p class="mt-2 text-center text-gray-600">Ingresa tu correo para recibir el enlace</p>

        <div id="alert" class="hidden mt-6 rounded-xl border p-4 text-sm"></div>

        <form id="recForm" class="mt-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700" for="correo">Correo</label>
                <input class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-4 focus:ring-blue-200" type="email" id="correo" name="correo" required>
            </div>
            <button id="submitBtn" type="submit" class="w-full rounded-xl bg-blue-700 text-white font-semibold py-3 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">Enviar enlace</button>
        </form>

        <p class="mt-4 text-sm text-gray-500">El token y enlace también aparecen en los logs (MAIL_MAILER=log).</p>
        <div id="response" class="mt-6 hidden" aria-live="polite">
            <pre class="whitespace-pre-wrap text-sm font-mono bg-gray-50 p-3 rounded-xl border"></pre>
        </div>
    </div>

    <script>
        $(function(){
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' } });
            function show(kind, msg){ const el = $('#alert'); el.removeClass('hidden border-red-200 bg-red-50 text-red-800 border-green-200 bg-green-50 text-green-800'); if(kind==='ok') el.addClass('border-green-200 bg-green-50 text-green-800'); if(kind==='error') el.addClass('border-red-200 bg-red-50 text-red-800'); el.text(msg); }
            $('#recForm').on('submit', function(e){ e.preventDefault(); const data={ correo: $('#correo').val() }; $.ajax({ url:'/api/recuperar', method:'POST', contentType:'application/json', data: JSON.stringify(data), success: function(resp){ show('ok','Revisa tu correo.'); $('#response').removeClass('hidden').find('pre').text(JSON.stringify(resp,null,2)); }, error: function(xhr){ const msg=(xhr.responseJSON&&(xhr.responseJSON.error||xhr.responseJSON.message))||'No se pudo enviar'; show('error',msg); $('#response').removeClass('hidden').find('pre').text(JSON.stringify(xhr.responseJSON||xhr.responseText,null,2)); } }); });
        });
    </script>
</body>
</html>
