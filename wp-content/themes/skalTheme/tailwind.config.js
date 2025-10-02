/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./src/**/*.{html,js,css}",
    "./assets/**/*.{html,js,css}",
    // Include WooCommerce templates
    "./woocommerce/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        // Add your custom colors here
        primary: {
          50: '#fef7ee',
          100: '#fdedd3',
          500: '#f97316',
          600: '#ea580c',
          700: '#c2410c',
        }
      },
      fontFamily: {
        // Add custom fonts if needed
        'sans': ['Inter', 'system-ui', 'sans-serif'],
        'roboto': ['Roboto', 'sans-serif'], 
      }
    },
  },
  plugins: [
    // Add useful plugins for WordPress/WooCommerce
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ],
}
