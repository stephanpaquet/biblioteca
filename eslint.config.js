import js from '@eslint/js';
import vue from 'eslint-plugin-vue';
import prettier from '@vue/eslint-config-prettier';
import { route } from 'ziggy-js';

export default [
  // Apply recommended rules to all files
  js.configs.recommended,

  // Vue 3 recommended rules for .vue files
  ...vue.configs['flat/recommended'],

  // Prettier config (should be last)
  prettier,

  {
    // Configuration for all files
    languageOptions: {
      ecmaVersion: 2021,
      sourceType: 'module',
      globals: {
        // Browser globals
        window: 'readonly',
        document: 'readonly',
        console: 'readonly',
        // Node.js globals
        process: 'readonly',
        Buffer: 'readonly',
        __dirname: 'readonly',
        __filename: 'readonly',
        module: 'readonly',
        require: 'readonly',
        exports: 'readonly',
        global: 'readonly',
        route: 'readonly', // Ziggy route helper
        fetch: 'readonly', // For fetch API in browser
        axios: 'readonly', // For axios usage in Vue components
      },
    },

    rules: {
      'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'vue/require-default-prop': 'off',
      'vue/multi-word-component-names': 'off',
    },
  },

  {
    // Configuration specifically for .vue files
    files: ['**/*.vue'],
    rules: {
      'vue/component-name-in-template-casing': ['error', 'PascalCase'],
      'vue/html-self-closing': [
        'error',
        {
          html: {
            void: 'always',
            normal: 'always',
            component: 'always',
          },
        },
      ],
    },
  },

  {
    // Files to ignore
    ignores: [
      'node_modules/',
      'vendor/',
      'public/build/',
      'public/hot/',
      'public/storage/',
      'storage/',
      'bootstrap/cache/',
      'resources/js/ziggy.js',
      'dist/',
      '.git/',
    ],
  },
];
