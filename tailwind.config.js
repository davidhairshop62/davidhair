/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.php",
    "./medical-wigs.php",
    "./ffa.php",
    "./solutions.php",
    "./diagnosis.php",
    "./stock.php",
    "./salon.php",
    "./transformations.php",
    "./ai-tryon.php",
    "./factory-b2b.php",
    "./faq.php",
    "./includes/**/*.php",
    "./assets/js/**/*.js"
  ],
  theme: {
    screens: {
      'xs': '480px',
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
    },
    extend: {
      colors: {
        brand: {
          darkBg: '#0B132B',
          cardBg: '#1C2541',
          cardInner: '#151E38',
          inner: '#151E38',
          border: '#2E3A59',
          textMain: '#F1F5F9',
          textMuted: '#94A3B8',
          teal: '#0D9488',
          tealLight: '#14B8A6',
          bronze: '#D97706',
          bronzeHover: '#B45309'
        }
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'sans-serif'],
        heading: ['Outfit', 'sans-serif']
      }
    }
  },
  plugins: []
};
