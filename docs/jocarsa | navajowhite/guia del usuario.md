# Guía de Usuario - Video Conferencia (Baja Resolución)

## Introducción
Este proyecto proporciona una plataforma de videoconferencia de baja resolución (80x60 píxeles a 2 FPS) basada en WebRTC y Socket.io. Está diseñado para minimizar el ancho de banda y permitir la comunicación en condiciones de conectividad limitada.

## Requisitos

### Cliente
- Un navegador web compatible con WebRTC (Google Chrome, Mozilla Firefox, Microsoft Edge, Safari).
- Conexión a Internet.

### Servidor
- Node.js instalado.
- Socket.io para la comunicación en tiempo real.
- Un servidor STUN/TURN para la negociación de pares.

## Instalación

1. Clona el repositorio del proyecto:
   ```bash
   git clone https://github.com/tu-repositorio/video-conferencia-baja-resolucion.git
   cd video-conferencia-baja-resolucion
   ```

2. Instala las dependencias necesarias:
   ```bash
   npm install
   ```

3. Inicia el servidor:
   ```bash
   node server.js
   ```

4. Accede a la aplicación en el navegador visitando:
   ```
   http://localhost:3000
   ```

## Uso

### 1. Iniciar Sesión en la Sala
- Abre el navegador y accede a la aplicación.
- La cámara y el micrófono se activarán automáticamente.
- Se unirá a la sala de videoconferencia con un identificador único.

### 2. Conexión con Otros Usuarios
- Cuando otro usuario se conecte, se establecerá una conexión WebRTC entre ambos.
- El video remoto se agregará dinámicamente a la interfaz.

### 3. Desconexión
- Cuando un usuario se desconecta, su video se eliminará automáticamente de la pantalla.
- Puede cerrar la pestaña del navegador para salir de la sala.

## Configuración Personalizada

### Cambiar la Resolución del Video
Si deseas modificar la resolución del video, puedes cambiar los valores en `startStream()` dentro del archivo HTML:

```js
localStream = await navigator.mediaDevices.getUserMedia({
    video: { width: 160, height: 120, frameRate: { max: 5 } },
    audio: true
});
```

### Configurar un Servidor STUN/TURN
El servidor usa por defecto el STUN de Google (`stun:stun.l.google.com:19302`). Para mayor estabilidad, puedes agregar servidores TURN en la configuración:

```js
const peerConnection = new RTCPeerConnection({
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'turn:tu-servidor-turn', username: 'usuario', credential: 'contraseña' }
    ]
});
```

## Solución de Problemas

### No se ve el video
- Asegúrate de haber concedido permisos de cámara y micrófono.
- Verifica que otro usuario esté conectado en la sala.

### No se escucha el audio
- Revisa la configuración de audio en el navegador.
- Asegúrate de que el micrófono esté activado.

### Desconexiones frecuentes
- Puede deberse a problemas de conectividad o al firewall bloqueando WebRTC.
- Intenta usar un servidor TURN en lugar de STUN.

## Contribuir
Si deseas mejorar este proyecto, puedes enviar un pull request o reportar problemas en el repositorio oficial.

## Licencia
Este proyecto está bajo la licencia MIT.
