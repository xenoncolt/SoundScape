/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./public/**/*.{php,html,js}",
        "./src/**/*.{php,html,js}",
        "./src/UI/**/*.php",
        "./src/UI/*.php",
        "./styles/input.css"
    ],
    theme: {
        extend: {
            colors: {
                'cus-primary': '#1db954',
                'cus-dark': '#191414',
                'cus-gray': '#535353',
                'cus-light-gray': '#b3b3b3',
                'cus-hover': '#1ed760',
                'cus-black': '#000000',
                'cus-card': '#181818',
                'cus-sidebar': '#0a0a0a',
            },
            backgroundImage: {
                'gradient-cus': 'linear-gradient(135deg, #1db954 0%, #1ed760 100%)',
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
                'bounce-gentle': 'bounceGentle 2s infinite',
                'pulse-slow': 'pulse 3s infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                bounceGentle: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                }
            },
            boxShadow: {
                'cus': '0 4px 60px rgba(29, 185, 84, 0.3)',
                'cus-lg': '0 10px 80px rgba(29, 185, 84, 0.4)',
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class'
        })
    ]
}