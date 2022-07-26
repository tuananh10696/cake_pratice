const progress = require('postcss-progress');
const paths = require('./paths');
const baseFontSize = 16; //-  rem計算用

const pcDesignWidth = 1280; //-  vw風にpc計算する用
const pcDesignHeight = 1280; //-  vw風にpc計算する用

const tbDesignWidth = 1280; //-  vw風にtablet計算する用
const tbDesignHeight = 1280; //-  vw風にtablet計算する用

const spDesignWidth = 768; //-  vw計算用
const spDesignHeight = 1000; //-  vh計算用

module.exports = (ctx) => ({
  map:
    ctx.env === 'development'
      ? {
          inline: true
        }
      : false,
  plugins: [
    progress.start(),
    require('postcss-import')(),
    require('stylelint')(),
    require('postcss-normalize')(),
    require('postcss-for'),
    require('postcss-flexbugs-fixes')(),
    require('postcss-extend-rule')(),
    require('postcss-nested')(),
    require('postcss-simple-vars')(),
    require('postcss-custom-media')(),
    require('postcss-gradient-transparency-fix')(),
    require('postcss-center')(),
    require('postcss-mixins')(),
    require('postcss-functions')({
      functions: {
        rem: function (num) {
          return `${num / baseFontSize}rem`;
        },
        pw: function (num, fix = false) {
          if (fix) {
            return `calc( var(--vw) * ${num} )`;
          } else {
            return `calc( var(--vw) * ${(num / pcDesignWidth) * 100} )`;
          }
        },
        ph: function (num, fix = false) {
          if (fix) {
            return `calc( var(--vh) * ${num} )`;
          } else {
            return `calc( var(--vh) * ${(num / pcDesignHeight) * 100} )`;
          }
        },
        vwTb: function (num, fix = false) {
          if (fix) {
            return `calc( var(--vw) * ${num} )`;
          } else {
            return `calc( var(--vw) * ${(num / tbDesignWidth) * 100} )`;
          }
        },
        vhTb: function (num, fix = false) {
          if (fix) {
            return `calc( var(--vh) * ${num} )`;
          } else {
            return `calc( var(--vh) * ${(num / tbDesignHeight) * 100} )`;
          }
        },
        vw: function (num, fix = false) {
          if (fix) {
            return `calc( var(--vw) * ${num} )`;
          } else {
            return `${(num / spDesignWidth) * 100}vw`;
          }
        },
        vh: function (num, fix = false) {
          if (fix) {
            return `calc( var(--vh) * ${num} )`;
          } else {
            return `calc( var(--vh) * ${(num / spDesignHeight) * 100} )`;
          }
        }
      }
    }),
    require('postcss-momentum-scrolling')(['auto', 'scroll']),
    require('postcss-will-change-transition')(),
    require('css-mqpacker')(),
    require('autoprefixer')(),
    require('postcss-cachebuster')({
      type: 'checksum',
      imagesPath: `${paths.appDest}`
    }),
    require('postcss-reporter')({
      clearReportedMessages: true
    }),
    progress.stop()
  ].concat(
    ctx.env === 'development'
      ? []
      : [
          require('cssnano')({
            preset: [
              'default',
              {
                discardComments: {
                  removeAll: true
                },
                autoprefixer: false,
                mergeRules: false
              }
            ]
          })
        ]
  )
});
