{{--
  VISTA SOLO PARA PRUEBAS EN DESARROLLO.
  No publicar en producción. Elimina este archivo antes de desplegar.
--}}
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password Test (Local)</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f6f7fb;margin:0;padding:2rem}
    .card{max-width:720px;margin:auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 4px 18px rgba(0,0,0,.06)}
    .card h1{margin:0;padding:1.25rem 1.5rem;border-bottom:1px solid #eee;font-size:1.125rem}
    form{padding:1rem 1.5rem}
    label{display:block;font-size:.9rem;color:#334155;margin:.5rem 0 .25rem}
    input{width:100%;padding:.65rem .75rem;border:1px solid #d1d5db;border-radius:8px}
    button{padding:.6rem .9rem;border-radius:8px;border:0;background:#1d4ed8;color:#fff;font-weight:600;cursor:pointer}
    .row{display:grid;gap:.75rem;grid-template-columns:1fr 1fr}
    pre{white-space:pre-wrap;background:#0b1021;color:#e4e7f3;border-radius:10px;padding:1rem;margin:1rem 1.5rem;overflow:auto}
  </style>
</head>
<body>
  <div class="card">
    <h1>Restablecer contraseña (local)</h1>
    <form id="resetForm">
      @csrf
      <div class="row">
        <div>
          <label for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="usuario@demo.edu" required>
        </div>
        <div>
          <label for="token">Token</label>
          <input id="token" name="token" placeholder="pega aquí el token del correo" required>
        </div>
      </div>
      <div class="row">
        <div>
          <label for="contrasena">Nueva contraseña</label>
          <input id="contrasena" name="contrasena" type="password" required>
        </div>
        <div>
          <label for="contrasena_confirmation">Confirmación</label>
          <input id="contrasena_confirmation" name="contrasena_confirmation" type="password" required>
        </div>
      </div>
      <div style="margin-top:.75rem">
        <button type="submit">POST /api/reset-password</button>
      </div>
    </form>
    <pre id="out">Esperando solicitud…</pre>
    <p id="backLogin" style="display:none;margin:0 1.5rem 1rem">
      <a href="/login-test" style="color:#1d4ed8;text-decoration:none">Volver al login</a>
    </p>
  </div>

  <script>
    const out = document.getElementById('out');
    function show(obj){ out.textContent = typeof obj === 'string' ? obj : JSON.stringify(obj, null, 2); }
    document.getElementById('resetForm').addEventListener('submit', async (e)=>{
      e.preventDefault();
      const payload = {
        token: document.getElementById('token').value,
        email: document.getElementById('email').value,
        contrasena: document.getElementById('contrasena').value,
        contrasena_confirmation: document.getElementById('contrasena_confirmation').value,
      };
      show('Enviando…');
      const res = await fetch('/api/reset-password', {
        method:'POST', headers:{ 'Content-Type':'application/json', 'Accept':'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json().catch(()=>({status:res.status,text:'Sin JSON'}));
      show(data);
      if(res.ok){ document.getElementById('backLogin').style.display='block'; }
    });
  </script>
</body>
</html>
