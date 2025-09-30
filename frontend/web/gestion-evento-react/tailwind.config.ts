import type { Config } from "tailwindcss"

const config: Config = {
  darkMode: "class", // 👈 importante
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}", // 👈 aquí incluimos ts y tsx
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

export default config