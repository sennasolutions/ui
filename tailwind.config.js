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
            colors: {
                "selected-color": "var(--selected-color)",
                "primary-color": "var(--primary-color-100)",
                "secondary-color": "var(--secondary-color-100)",
                
                "gradient-color1": "var(--gradient-color1)",
                "gradient-color2": "var(--gradient-color2)",
               
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

                "danger-color": "var(--danger-color)",
                "success-color": "var(--success-color)",
                "info-color": "var(--info-color)",
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography')
    ]
};