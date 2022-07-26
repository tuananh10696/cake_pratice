const fs = require('fs');
const path = require('path');
const paths = require('../paths');
const assetCacheBust = require('./cache_buster');
const colors = require('./colors');

const readSubDirSync = (folderPath) => {
  let result = [];
  const readTopDirSync = (folderPath) => {
    let items = fs.readdirSync(folderPath);
    items = items.map((itemName) => {
      return path.join(folderPath, itemName);
    });
    items.forEach((itemPath) => {
      result.push(itemPath);
      if (fs.statSync(itemPath).isDirectory()) {
        readTopDirSync(itemPath);
      }
    });
  };
  readTopDirSync(folderPath);
  return result;
};
const files = readSubDirSync(paths.appDest);
for (const file of files) {
  if (file.includes('.html')) {
    const html = fs.readFileSync(file, { encoding: 'utf-8' });
    assetCacheBust(html, paths.appDest).fork(
      (error) => console.log(error),
      (cacheBustedHtml) => {
        fs.writeFileSync(file, cacheBustedHtml);
      }
    );
  }
}
console.log(`${colors.cyanBg}${colors.black}  Cache Busting Complete  ${colors.reset}`);
