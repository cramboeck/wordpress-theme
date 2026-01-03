module.exports = {
	env: {
		browser: true,
		es2021: true,
		node: true,
	},
	extends: ['eslint:recommended'],
	parserOptions: {
		ecmaVersion: 'latest',
		sourceType: 'module',
	},
	rules: {
		// Code Style
		indent: ['error', 'tab'],
		'linebreak-style': ['error', 'unix'],
		quotes: ['error', 'single'],
		semi: ['error', 'always'],

		// Best Practices
		'no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
		'no-console': ['warn', { allow: ['warn', 'error'] }],
		eqeqeq: ['error', 'always'],
		curly: ['error', 'all'],

		// ES6+
		'prefer-const': 'error',
		'no-var': 'error',
		'arrow-spacing': 'error',
		'prefer-arrow-callback': 'error',
		'prefer-template': 'error',

		// Spacing
		'space-before-blocks': 'error',
		'keyword-spacing': 'error',
		'comma-spacing': 'error',
		'object-curly-spacing': ['error', 'always'],
		'array-bracket-spacing': ['error', 'never'],
	},
	globals: {
		wp: 'readonly',
	},
};
