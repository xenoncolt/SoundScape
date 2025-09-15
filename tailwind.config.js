/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./public/**/*.{php,html,js,css}",
        "./src/**/*.{php,html,js,css}",
        "./src/UI/**/*.php",
        "./src/UI/*.php",
        "./styles/input.css"
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class'
        })
    ]
}