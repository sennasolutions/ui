const toAbsolutePaths = (list) => list.map(x => x.indexOf("." === 0) ? __dirname + "/" + x : x)

module.exports = {
    mode: 'jit',
    important: '.senna-ui',
    purge: toAbsolutePaths([
        './resources/views/**/**/*.blade.php',
        './src/Helpers/*.php',
        './tailwind.safelist.txt'
    ]),
    theme: {
        extend: {
            colors: {
                "selected-color": "var(--selected-color)",
                "primary-color": "var(--primary-color-100)",
                "secondary-color": "var(--secondary-color-100)",
                "tertiary-color": "var(--tertiary-color-100)",
            
                "error-color": "var(--error-color)",
                "error-color-ring": "var(--error-color-ring)",
            
                "success-color": "var(--success-color)",
                "info-color": "var(--info-color)",
                
                "primary-color-100": "var(--primary-color-100)",
                "primary-color-90": "var(--primary-color-90)",
                "primary-color-80": "var(--primary-color-80)",
                "primary-color-70": "var(--primary-color-70)",
                "primary-color-60": "var(--primary-color-60)",
                "primary-color-50": "var(--primary-color-50)",
                "primary-color-40": "var(--primary-color-40)",
                "primary-color-30": "var(--primary-color-30)",
                "primary-color-20": "var(--primary-color-20)",
                "primary-color-10": "var(--primary-color-10)",
            
                "secondary-color-100": "var(--secondary-color-100)",
                "secondary-color-90": "var(--secondary-color-90)",
                "secondary-color-80": "var(--secondary-color-80)",
                "secondary-color-70": "var(--secondary-color-70)",
                "secondary-color-60": "var(--secondary-color-60)",
                "secondary-color-50": "var(--secondary-color-50)",
                "secondary-color-40": "var(--secondary-color-40)",
                "secondary-color-30": "var(--secondary-color-30)",
                "secondary-color-20": "var(--secondary-color-20)",
                "secondary-color-10": "var(--secondary-color-10)",
            
                "tertiary-color-100": "var(--tertiary-color-100)",
                "tertiary-color-90": "var(--tertiary-color-90)",
                "tertiary-color-80": "var(--tertiary-color-80)",
                "tertiary-color-70": "var(--tertiary-color-70)",
                "tertiary-color-60": "var(--tertiary-color-60)",
                "tertiary-color-50": "var(--tertiary-color-50)",
                "tertiary-color-40": "var(--tertiary-color-40)",
                "tertiary-color-30": "var(--tertiary-color-30)",
                "tertiary-color-20": "var(--tertiary-color-20)",
                "tertiary-color-10": "var(--tertiary-color-10)",
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ]
};
