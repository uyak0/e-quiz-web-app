/** @type {import('tailwindcss').Config} */
export default {
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
    },
  },
  plugins: [],
}