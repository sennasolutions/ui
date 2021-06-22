module.exports = {
    mode: 'jit',
    important: '.senna-ui',
    purge: [
        ...require('./tailwind.purge')
    ],
    theme: {
        extend: {
            colors: require('./tailwind.colors')
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ]
};
