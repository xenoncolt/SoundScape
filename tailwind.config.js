/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./public/**/*.{php,html,js}",
        "./src/**/*.{php,html,js}",
        "./src/UI/**/*.php"
    ],
    theme: {
        extend: {
            colors: {
                'music-primary': "#1db954",
                'music-dark': "#191414",
                'music-gray': "#535353"
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class'
        })
    ]
}