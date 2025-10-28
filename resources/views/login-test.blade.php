{{--
  VISTA SOLO PARA PRUEBAS EN DESARROLLO.
  No publicar en producción. Elimina este archivo antes de desplegar.
--}}
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Test (Local)</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f6f7fb;margin:0;padding:2rem}
    .card{max-width:640px;margin:auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 4px 18px rgba(0,0,0,.06)}
    .card h1{margin:0;padding:1.25rem 1.5rem;border-bottom:1px solid #eee;font-size:1.125rem}
    form{padding:1rem 1.5rem}
    label{display:block;font-size:.9rem;color:#334155;margin:.5rem 0 .25rem}
    input{width:100%;padding:.65rem .75rem;border:1px solid #d1d5db;border-radius:8px}
    button{padding:.6rem .9rem;border-radius:8px;border:0;background:#1d4ed8;color:#fff;font-weight:600;cursor:pointer}
    button:disabled{opacity:.6;cursor:not-allowed}
    .actions{display:flex;gap:.5rem;align-items:center}
    pre{white-space:pre-wrap;background:#0b1021;color:#e4e7f3;border-radius:10px;padding:1rem;margin:1rem 1.5rem;overflow:auto}
    .note{margin:0 1.5rem 1rem;font-size:.85rem;color:#64748b}
  </style>
</head>
<body>
  <div class="card">
    <h1>Login de prueba (local)</h1>
    <form id="loginForm">
      @csrf
      <div>
        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" placeholder="usuario@demo.edu" required>
      </div>
      <div>
        <label for="contrasena">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena" placeholder="••••••" required>
      </div>
      <div style="margin-top:.25rem">
        <label style="display:block;font-size:.9rem;color:#334155;margin:.5rem 0 .25rem">Tipo</label>
        <label style="margin-right:1rem"><input type="radio" name="tipo" value="usuario" checked> Usuario</label>
        <label><input type="radio" name="tipo" value="docente"> Docente</label>
      </div>
      <div class="actions" style="margin-top:.75rem">
        <button type="submit">POST /api/login</button>
        <button type="button" id="logoutBtn" title="Usa el token guardado en localStorage">POST /api/logout</button>
      </div>
    </form>

    <p class="note">El token se guarda en <code>localStorage.apiToken</code> para usarlo en Logout.</p>
    <p class="note"><a href="/recuperar-test" style="color:#1d4ed8;text-decoration:none">¿Olvidaste tu contraseña?</a></p>
    <pre id="out">Esperando solicitud…</pre>
  </div>

  <script>
    const out = document.getElementById('out');
    function show(obj){ out.textContent = typeof obj === 'string' ? obj : JSON.stringify(obj, null, 2); }

    document.getElementById('loginForm').addEventListener('submit', async (e)=>{
      e.preventDefault();
      const tipo = (document.querySelector('input[name="tipo"]:checked')||{}).value || 'usuario';
      const payload = {
        correo: document.getElementById('correo').value,
        contrasena: document.getElementById('contrasena').value,
        tipo
      };
      show('Enviando…');
      const res = await fetch('/api/login', {
        method:'POST', headers:{ 'Content-Type':'application/json', 'Accept':'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json().catch(()=>({status:res.status,text:'Sin JSON'}));
      show(data);
      if(res.ok && data.token){ localStorage.setItem('apiToken', data.token); }
    });

    document.getElementById('logoutBtn').addEventListener('click', async ()=>{
      const token = localStorage.getItem('apiToken');
      if(!token){ return show({error:'No hay token en localStorage.apiToken'}); }
      show('Enviando logout…');
      const res = await fetch('/api/logout', {
        method:'POST', headers:{ 'Authorization': 'Bearer '+token, 'Accept':'application/json' }
      });
      const data = await res.json().catch(()=>({status:res.status,text:'Sin JSON'}));
      show(data);
    });
  </script>
</body>
</html>
