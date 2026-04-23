import js from '@eslint/js'
import globals from 'globals'
import reactHooks from 'eslint-plugin-react-hooks'
import reactRefresh from 'eslint-plugin-react-refresh'
import { defineConfig, globalIgnores } from 'eslint/config'

export default defineConfig([
  globalIgnores(['dist']),
  {
    files: ['**/*.{js,jsx}'],
    extends: [
      js.configs.recommended,
      reactHooks.configs.flat.recommended,
      reactRefresh.configs.vite,
    ],
    languageOptions: {
      ecmaVersion: 2020,
      globals: globals.browser,
      parserOptions: {
        ecmaVersion: 'latest',
        ecmaFeatures: { jsx: true },
        sourceType: 'module',
      },
    },
    rules: {
      // Variables non utilisées — ignore les majuscules et 'e' dans les catch
      'no-unused-vars': ['warn', { varsIgnorePattern: '^[A-Z_]', caughtErrors: 'none' }],
      // Ces règles React Hooks sont trop strictes pour notre code fonctionnel
      'react-hooks/set-state-in-effect': 'off',
      'react-hooks/immutability': 'off',
      'react-hooks/exhaustive-deps': 'warn',
      // Fast refresh — warn au lieu d'erreur
      'react-refresh/only-export-components': 'warn',
    },
  },
])