import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    0: "#2763b6",
                    50: "#f0f5fb",
                    100: "#d6e3f0",
                    200: "#add2e8",
                    300: "#84c0e0",
                    400: "#5baed8",
                    500: "#329cd0",
                    600: "#276eb6",
                    700: "#1f5a9a",
                    800: "#17467e",
                    900: "#0f3a62",
                    950: "#0a2541",
                },
            },
            spacing: {
                "safe-bottom": "env(safe-area-inset-bottom)",
            },
            keyframes: {
                "fade-in": {
                    "0%": { opacity: "0", transform: "translateY(10px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
            },
            animation: {
                "fade-in": "fade-in 0.3s ease-in-out",
            },
        },
    },

    plugins: [forms],
};
