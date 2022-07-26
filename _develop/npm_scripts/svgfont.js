const svgtofont = require('svgtofont');
const path = require('path');
const colors = require('./colors');

svgtofont({
  src: path.resolve(process.cwd(), 'src/fonts'),
  dist: path.resolve(process.cwd(), 'src/static_files/fonts'),
  fontName: 'glyphs',
  outSVGPath: false,
  css: {
    output: 'src/css/_config/',
    fontSize: '1em',
    cssPath: '../fonts/',
    fileName: '_fonts',
    include: '\\.(css)$'
  },
  startUnicode: 0xea01,
  svgicons2svgfont: {
    normalize: true,
    centerHorizontally: true,
    centerVertically: true,
    fontHeight: 32
  },
  svg2ttf: {
    ts: '0'
  },
  website: null
}).then(() => {
  console.log(`${colors.magentaBg}${colors.black} IconFont SUCCESS.${colors.reset}`);
});
