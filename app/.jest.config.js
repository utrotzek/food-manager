module.exports = {
    roots: ['resources/js'],
    testRegex: '/test/.*.spec.js$',
    coverageReporters: ["text-summary"],
    coverageThreshold: {
        "global": {
            "branches": 90,
            "functions": 90,
            "lines": 90,
            "statements": -10
        }
    },
    moduleFileExtensions: [
        'js',
        'json',
        'vue'
    ],
    'transform': {
        '^.+\.js$': '<rootDir>/node_modules/babel-jest',
        '.*\.(vue)$': '<rootDir>/node_modules/vue-jest'
    }
}
