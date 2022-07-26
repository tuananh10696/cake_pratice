const fs = require('fs');
const cpx = require('cpx');
const path = require('path');
const paths = require('../paths');

const colors = require('./colors');

const fsw = fs.watch(
  `${paths.appSrc}static_files/`,
  {
    persistent: true,
    recursive: true
  },
  function (type, filename) {
    const filePath = filename.split(path.sep).reverse().slice(1).reverse().join(path.sep);
    cpx.copy(`${paths.appSrc}static_files/` + filename, `${paths.appBuild}/${paths.assetPath}/${filePath}`, { clean: true }, function () {
      console.log(
        `${colors.magentaBg}${colors.black} change ${colors.reset} : ${colors.cyan}${filename}${colors.reset} : ${colors.green}done.${colors.reset}`
      );
    });
  }
);
