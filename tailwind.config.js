/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                poppins: "'Poppins', sans-serif",
            },
            colors: {
                "theme-primary": "#112042",
                "theme-secondary": "#112042",
                "theme-body": "#F9FAFB",
                "theme-text": "#555555",
            },
        },
    },
    plugins: [],
};
