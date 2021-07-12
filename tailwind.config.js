module.exports = {
    purge: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            transitionProperty: {
                width: "width",
                height: "height",
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [],
};
