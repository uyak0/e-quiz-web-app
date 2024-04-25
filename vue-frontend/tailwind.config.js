/** @type {import('tailwindcss').Config} */
export default {
  darkMode: "selector",
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        'jetBrains': ['JetBrains Mono', 'monospace'],
        'firaSans': ['Fira Sans', 'sans-serif']
      },
      colors: {
        'soft-black': '#181818',
        'soft-white': '#f8f8f8',
        'darkMode': 'rgba(var(--color-darkModeText), 0.64)',
      }
    },
  },
  plugins: [],
}
