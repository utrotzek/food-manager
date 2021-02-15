module.exports = {
    roots: ['resources/js'],
    testRegex: '/test/.*.spec.js$',
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
