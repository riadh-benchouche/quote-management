/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1F2937',
        secondary: '#58A8A0'
      }
    },
  },
  plugins: [
    // ...
    require('@tailwindcss/forms'),
  ],
}
