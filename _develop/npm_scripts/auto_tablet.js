const fs = require('fs');
const path = require('path');
const paths = require('../paths');

const colors = require('./colors');

const readdirRecursively = (dir, files = []) => {
  const paths = fs.readdirSync(dir);
  const dirs = [];
  for (const path of paths) {
    const stats = fs.statSync(`${dir}/${path}`);
    if (stats.isDirectory()) {
      dirs.push(`${dir}/${path}`);
    } else {
      files.push(`${dir}/${path}`);
    }
  }
  for (const d of dirs) {
    files = readdirRecursively(d, files);
  }
  return files;
};

const files = readdirRecursively('./src/css');
for (const file of files) {
  if (file.includes('_pc.css')) {
    let css = fs.readFileSync(file, { encoding: 'utf-8' });
    css = css
      .replace(/@import.*_tb\.css.*/g, '')
      .replace(/(-*[\d.]+)px/g, 'vwTb($1)')
      .replace(/vwTb\(1\)/g, '1px')
      .replace(/rem\(/g, 'vwTb(')
      .replace(/--pc/g, '--tb');
    fs.writeFileSync(file.replace('_pc', '_tb'), css);
    console.log(`${colors.magentaBg}${colors.black}create:${colors.cyanBg}${colors.black}${file.replace('_pc', '_tb')} ${colors.reset}`);
  }
}
