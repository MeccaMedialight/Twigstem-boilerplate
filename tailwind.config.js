module.exports = {
    theme: {
        extend: {
            inset: {
                '1/2': '50%',
                '2/5': '40%',
                'full': '100%',
            },
            colors: {
                primary: {
                    default: '#FFE14F',
                    '50': '#fffef6',
                    '100': '#fffced',
                    '200': '#fff8d3',
                    '300': '#fff3b9',
                    '400': '#ffea84',
                    '500': '#ffe14f',
                    '600': '#e6cb47',
                    '700': '#bfa93b',
                    '800': '#99872f',
                    '900': '#7d6e27'
                },
                secondary: {
                    default: '#204341',
                    100: '#90A1A0',
                    200: '#829594',
                    300: '#748A88',
                    400: '#667E7C',
                    500: '#587271',
                    600: '#4A6665',
                    700: '#3C5B59',
                    800: '#2E4F4D',
                    900: '#204341',
                },
                blue: {
                    default: '#2460DA',
                    100: '#F3F9FF',
                    200: '#769CE8',
                    300: '#5B88E3',
                    400: '#3F74DF',
                    500: '#2460DA',
                    600: '#2054BF',
                    700: '#1B48A4',
                    800: '#173C88',
                    900: '#12306D',
                },
            },
            fontFamily: {

            },
            gridTemplateColumns: {
                '38': 'repeat(38, minmax(0, 1fr))',

            },
            animation: {
                fadeIn: "fadeIn .4s ease-in forwards"
            },
            keyframes: {
                fadeIn: {
                    "0%": {opacity: 0},
                    "100%": {opacity: 1}
                }
            },
            spacing: {
                '1/3': '33.333333%',
                '2/3': '66.666667%',
                '9/16': '0.5625'
            }
        },
    },
    variants: {},
    plugins: [
        require('@tailwindcss/typography'),
        //require('@tailwindcss/ui'),
    ],
}
