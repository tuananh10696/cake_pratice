const fs = require('fs');
const cpx = require('cpx');
const paths = require('../paths');

const colors = require('./colors');

cpx.copy(
  'src/static_files/**/*',
  `${paths.appBuild}/${paths.assetPath}/`,
  {
    clean: false
  },
  function () {
    console.log(`\n${colors.magentaBg}${colors.black} All file copy complete. ${colors.reset}\n`);
  }
);
