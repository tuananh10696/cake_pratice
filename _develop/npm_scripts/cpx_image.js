const fs = require('fs');
const cpx = require('cpx');
const paths = require('../paths');

const colors = require('./colors');

cpx.copy(
  'src/images/**/*',
  `${paths.appBuild}/${paths.assetPath}/images/`,
  {
    clean: true
  },
  function () {
    console.log(`\n${colors.cyanBg}${colors.black} All image copy complete. ${colors.reset}\n`);
  }
);
