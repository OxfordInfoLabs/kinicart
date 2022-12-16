/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors');
module.exports = {
    important: true,
    content: [
        './src/**/*.{html,ts}',
        '../../angular/projects/ngx-kinicart/src/**/*.{html,ts}',
        '../../../kiniauth/angular/projects/ng-kiniauth/src/**/*.{html,ts}',
    ],
    theme: {
        extend: {
            blur: {
                xs: '2px',
            },
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                black: colors.black,
                white: colors.white,
                gray: colors.neutral,
                indigo: colors.indigo,
                red: colors.rose,
                yellow: colors.amber,
                blue: colors.blue,
                orange: colors.orange
            },
            zIndex: {
                '-10': '-10',
            },
            textColor: {
                'primary': '#3f51b5',
                'secondary': '#ff4081',
                'danger': '#f44336',
                'success': '#4ec257',
                'cta': '#3f51b5'
            },
            backgroundColor: {
                'primary': '#3f51b5',
                'secondary': '#ff4081',
                'danger': '#f44336',
                'success': '#4ec257'
            },
            borderColor: {
                'primary': '#3f51b5',
                'secondary': '#ff4081',
                'danger': '#f44336',
                'success': '#4ec257'
            },
            strokeWidth: {
                '3': '3',
                '4': '4',
            }
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms')
    ],
}
