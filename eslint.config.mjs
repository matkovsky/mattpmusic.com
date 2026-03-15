import globals from 'globals';
import js from '@eslint/js';

export default [js.configs.recommended, {
    languageOptions: {
        globals: {
            ...globals.browser,
        },
    },

    rules: {
        indent: ['error', 4],
        'linebreak-style': ['error', 'unix'],
        quotes: ['error', 'single'],
        semi: ['error', 'always'],
    },
}];
