{{--
  VISTA SOLO PARA PRUEBAS EN DESARROLLO.
  No publicar en producción. Elimina este archivo antes de desplegar.
--}}
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recuperación Test (Local)</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f6f7fb;margin:0;padding:2rem}
    .card{max-width:640px;margin:auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 4px 18px rgba(0,0,0,.06)}
    .card h1{margin:0;padding:1.25rem 1.5rem;border-bottom:1px solid #eee;font-size:1.125rem}
    form{padding:1rem 1.5rem}
    label{display:block;font-size:.9rem;color:#334155;margin:.5rem 0 .25rem}
    input{width:100%;padding:.65rem .75rem;border:1px solid #d1d5db;border-radius:8px}
    button{padding:.6rem .9rem;border-radius:8px;border:0;background:#1d4ed8;color:#fff;font-weight:600;cursor:pointer}
    pre{white-space:pre-wrap;background:#0b1021;color:#e4e7f3;border-radius:10px;padding:1rem;margin:1rem 1.5rem;overflow:auto}
  </style>
</head>
<body>
  <div class="card">
    <h1>Recuperación de contraseña (local)</h1>
    <form id="recForm">
      @csrf
      <div>
        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" placeholder="usuario@demo.edu" required>
      </div>
      <div style="margin-top:.75rem">
        <button type="submit">POST /api/recuperar</button>
      </div>
    </form>
    <pre id="out">Esperando solicitud…</pre>
    <p id="backToLogin" style="display:none;margin:0 1.5rem 1rem">
      <a href="/login-test" style="color:#1d4ed8;text-decoration:none">Volver al login</a>
    </p>
  </div>

  <script>
    const out = document.getElementById('out');
    function show(obj){ out.textContent = typeof obj === 'string' ? obj : JSON.stringify(obj, null, 2); }
    document.getElementById('recForm').addEventListener('submit', async (e)=>{
      e.preventDefault();
      const payload = { correo: document.getElementById('correo').value };
      show('Enviando…');
      const res = await fetch('/api/recuperar', {
        method:'POST', headers:{ 'Content-Type':'application/json', 'Accept':'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json().catch(()=>({status:res.status,text:'Sin JSON'}));
      show(data);
      if(res.ok){ document.getElementById('backToLogin').style.display='block'; }
    });
  </script>
</body>
</html>
