<!doctype html>
<html lang="en">
  {ignore}
  <head>
    <style>
        body{font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;}
        a {color: #32BBED;font-weight:600; text-decoration: none;}
        a:hover{color: #0094D4;}
        .hero{background-color: #32BBED;color:#fff; padding: 2rem 1rem 2rem 2rem;}
        .body{padding: 1.5rem 1rem 2rem 1rem; line-height: 2rem; color: #50535C; font-size: .9rem;}
        .footer{background-color: #464952;padding: 2rem;align-items: center; display: flex;}
        .pr-1{padding-right: 2rem;}
        img{width: auto;height: auto;}
        hr{border: #e0e0e0 solid 1px; margin: 2rem 0;}
    </style>
  </head>
  {/ignore}
  <body>
    <div class="hero">
      <img src="{base_url}api/assets/img/logo-mae.png">
    </div>
    <div class="body">
      <h2>Hola {name},</h2>
      <h3 class="mt1">
          Estás recibiendo este mail porque el Director de Seguimiento de tu Organismo ha creado un usuario en la plataforma <span style="white-space: pre-wrap;display: inline-block;">mapaaccionestatal.jefatura.gob.ar</span> con tus datos.
      </h3>
        <p>Para activar tu cuenta haz click, solo una vez, en el siguiente link: <a href="{base_url}user/register/activar/{token}" target="_blank" style="white-space: pre-wrap;display: inline-block;"><b>{base_url}user/register/activar/{token}</b></a>
        </p>
        <p>
          Si el link no te funciona, simplemente copia la direccion en el navegador y completa los pasos.
        </p>
        <p>
          Para el primer acceso ingresa en Usuario tu número de DNI y tu mismo DNI como contraseña. Luego de este primer acceso, el sistema te solicitará el cambio de la contraseña por una más segura.
        </p>
         <p>
            Si tenes alguna duda sobre el cambio de contraseña te dejamos el link al instructivo de ayuda:  <a href="https://mapaaccionestatal.jefatura.gob.ar/ayuda/primer-inicio-de-sesion" target="_blank" style="white-space: pre-wrap;display: inline-block;">https://mapaaccionestatal.jefatura.gob.ar/ayuda/primer-inicio-de-sesion</a>
        </p>
        <hr class="mt2 mb2">
      <b>Gracias por utilizar el Mapa de la Accion Estatal y bienvenido</b>
    </div>
    <div class="footer">
        <img src="{base_url}api/assets/img/logo-subse.png" class="pr-1">
        <img src="{base_url}api/assets/img/logo-jgm.png" class="pr-1">
    </div>
  </body>
</html>
