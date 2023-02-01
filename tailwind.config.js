module.exports = {
    // mode: 'jit',
    important: '.senna-ui',
    darkMode: 'class', // or 'media' or 'class'
    purge: [
        ...require('./tailwind.purge')
    ],
    theme: {
        extend: {
            colors: require('./tailwind.colors')
        }
    },
    plugins: [
        // require('@tailwindcss/forms'),
        // require('@tailwindcss/typography')
    ]
};
