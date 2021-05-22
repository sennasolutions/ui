module.exports = {
    mode: 'jit',
    important: '.senna-ui',
    purge: [
        './resources/views/**/**/*.blade.php',
        './src/Helpers/*.php',
        './tailwind.safelist.txt'
    ],
    theme: {
        extend: {
            colors: require('./tailwind.colors.js')
        }
    },
    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography')
    ]
};