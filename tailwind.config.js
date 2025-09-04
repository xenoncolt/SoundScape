/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./public/**/*.php",
        "./src/**/*.php",
        "./public/**/*.html",
        "./public/**/*.js"
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
        require('@tailwindcss/forms')
    ]
}