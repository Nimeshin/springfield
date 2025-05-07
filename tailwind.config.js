module.exports = {
  content: [
    './**/*.php',
    './js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#e11d24',
      },
    },
  },
  plugins: [
    require('@tailwindcss/aspect-ratio'),
  ],
} 