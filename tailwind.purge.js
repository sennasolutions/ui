const toAbsolutePaths = (list) => list.map(x => x.indexOf("." === 0) ? __dirname + "/" + x : x)

module.exports = toAbsolutePaths([
    './resources/views/**/**/*.blade.php',
    './src/Helpers/*.php',
    './tailwind.safelist.txt'
])