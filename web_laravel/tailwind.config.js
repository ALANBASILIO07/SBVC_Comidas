/**
 * Nombre del archivo        : tailwind.config.js
 * Descripción               : Configuración de Tailwind extendida con paleta SBVC.
 *                            Genera clases como bg-sbvc-primary, text-sbvc-info, etc.
 * Fecha de creación         : 14/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 14/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.0
 * Fecha de mantenimiento    : 14/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : Configuración
 * Descripción del mantenimiento: Crear tokens para paleta corporativa y habilitar darkMode por clase.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

/* PROLOGUE: Configuración Tailwind extendida con paleta SBVC para uso en clases utilitarias. */
/* Archivo: tailwind.config.js */

module.exports = {
  darkMode: 'class', // modo oscuro por clase .dark
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        sbvc: {
          white: '#FFFFFF',
          black: '#000000',
          primary: '#DE6601',   // naranja variante app.css
          success: '#42A958',   // verde
          info: '#241178',      // azul / púrpura
          danger: '#EE0000',    // rojo
          'purple-1': '#241178',
          'purple-2': '#1A0D5A',
          olive: '#272800',
          bg: '#FFFFFF',
          'card-bg': '#F8FAFC',
          text: '#171717',
          muted: '#404040'
        }
      }
    }
  },
  plugins: [],
}