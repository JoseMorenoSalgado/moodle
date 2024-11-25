
# WhatsApp Moodle Integration

Este complemento de Moodle permite a los usuarios ponerse en contacto rápidamente a través de WhatsApp. Está diseñado para mejorar la comunicación entre los estudiantes y el personal docente o administrativo.

## Características

- **Enlace directo a WhatsApp:** Los usuarios pueden iniciar una conversación directamente con un número de WhatsApp predefinido desde Moodle.  
- **Icono personalizable:** Utiliza un icono de WhatsApp alojado en un servidor externo.  
- **Fácil integración:** Se puede configurar sin complicaciones desde el panel de administración de Moodle.  
- **Compatible con múltiples temas:** Funciona perfectamente con temas populares como Edwiser RemUI.  
- **Soporte para diferentes países:** El complemento integra el código de país para los números de teléfono de WhatsApp, permitiendo envíos personalizados según la ubicación del usuario.  
- **Mensaje automático:** Se puede configurar un mensaje predeterminado que se envía automáticamente al iniciar la conversación.  

## Requisitos

- **Moodle:** Versión 4.0 o superior.  
- **PHP:** Versión 7.4 o superior.  

## Instalación

1. Descarga el complemento desde este repositorio o clónalo con el siguiente comando:  
   ```bash
   git clone https://github.com/JoseMorenoSalgado/moodle.git
   ```
2. Copia la carpeta del complemento `whatsapp_moodle` a la carpeta `local` en el directorio raíz de Moodle:  
   ```bash
   /path/to/moodle/local/
   ```
3. Accede al sitio como administrador y ve a la página de **Notificaciones** para completar la instalación.  
4. Configura el complemento desde el panel de administración:  
   - Ve a **Administración del sitio > Extensiones > Local plugins > WhatsApp Moodle**.  
   - Ingresa el número de WhatsApp al que se redirigirá la comunicación.  
   - Configura el código de país, el mensaje automático y personaliza el texto del botón y el icono, si es necesario.

## Uso

- Los estudiantes y docentes podrán ver un botón de WhatsApp en las áreas configuradas de Moodle.  
- Al hacer clic en el botón, serán redirigidos a una conversación con el número de WhatsApp especificado, incluyendo un mensaje predefinido.  

## Configuración Avanzada

### Cambiar el icono de WhatsApp
Por defecto, el complemento utiliza el icono disponible en:  
`https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg`.  
Puedes cambiar el enlace desde la configuración del complemento.  

### Personalización del mensaje automático
El complemento permite configurar un mensaje predeterminado que se enviará al iniciar la conversación con WhatsApp. Este mensaje puede incluir información dinámica basada en el usuario o el contexto del curso.

## Contribución

¡Eres bienvenido a contribuir a este proyecto!  
- Reporta problemas o envía solicitudes de características en la [sección de Issues](https://github.com/JoseMorenoSalgado/moodle/issues).  
- Si deseas colaborar, realiza un fork del repositorio, realiza tus cambios y envía un pull request.

## Licencia

Este proyecto está licenciado bajo la [Licencia GPL-3.0](https://www.gnu.org/licenses/gpl-3.0.html).  

## Créditos

Desarrollado por **José Moreno Salgado** como parte de las soluciones de **Elearning Cloud**.  

---

**Nota:** Este complemento es una herramienta no oficial y no está afiliado ni respaldado por WhatsApp Inc.
