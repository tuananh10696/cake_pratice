const path = require('path');
const paths = require('./paths');
const TerserPlugin = require('terser-webpack-plugin');
const DuplicatePackageCheckerPlugin = require('duplicate-package-checker-webpack-plugin');
const EventHooksPlugin = require('event-hooks-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const shouldUseSourceMap = false;
const Dotenv = require('dotenv-webpack');

const colors = require('./npm_scripts/colors');

const noticeMessage = `
${colors.yellow}****************   ${colors.white}WATCH MODE${colors.yellow}   **********************${colors.red}
    process.env.NODE_ENV : development
    Don't forget ${colors.green}npm run build

    ${colors.yellowBg}${colors.black}【watchでのJSの書換を確認】${colors.reset}

    ${colors.red}アップロード/push前に必ず ${colors.green}npm run build${colors.red}
    を実行すること。
${colors.yellow}******************************************************${colors.reset}
`;

module.exports = (env) => {
  process.env.BABEL_ENV = env.production ? 'production' : 'development';
  process.env.NODE_ENV = env.production ? 'production' : 'development';
  return {
    target: ['web', 'es6'],
    mode: process.env.NODE_ENV,
    bail: false,
    devtool: process.env.NODE_ENV === 'development' ? 'inline-source-map' : false,
    entry: {
      bundle: ['./src/js/main.js']
    },
    output: {
      path: paths.appBuild,
      publicPath: '/' + paths.subDirectory,
      filename: `${paths.assetPath}/js/[name].js`,
      chunkFilename: (pathData) => {
        return pathData.chunk.name.includes('vendor') || pathData.chunk.name.includes('bundle')
          ? `${paths.assetPath}/js/[name].js`
          : process.env.NODE_ENV === 'development'
          ? `${paths.assetPath}/js/[name].js`
          : `${paths.assetPath}/js/[name].[chunkhash:8].js`;
      }
    },
    cache: {
      type: 'filesystem',
      buildDependencies: {
        config: [__filename]
      },
      version: '1.0'
    },
    optimization: {
      minimizer: [
        new TerserPlugin({
          parallel: true,
          extractComments: false,
          terserOptions: {
            parse: {
              ecma: 6
            },
            compress: {
              ecma: 6,
              warnings: false,
              comparisons: false,
              inline: 2
            },
            output: {
              ecma: 6,
              comments: false,
              ascii_only: true
            }
          }
        })
      ],
      splitChunks: {
        name: 'vendor',
        chunks: 'all'
      },
      runtimeChunk: 'single'
    },
    module: {
      strictExportPresence: true,
      rules: [
        { parser: { requireEnsure: false } },
        {
          test: /\.(js|mjs|jsx|ts|tsx)$/,
          include: __dirname,
          use: [
            {
              loader: require.resolve('babel-loader'),
              options: {
                cacheDirectory: true,
                cacheCompression: true,
                compact: true
              }
            }
          ]
        }
      ]
    },
    resolve: {
      extensions: ['.tsx', '.ts', '.js', '.jsx']
    },
    plugins: [
      new Dotenv({
        path: process.env.NODE_ENV === 'development' ? './.env.test' : './.env.prod'
      }),
      new ESLintPlugin({
        extensions: ['.ts', '.js', '.tsx', '.jsx'],
        exclude: 'node_modules/'
      }),
      new DuplicatePackageCheckerPlugin(),
      new EventHooksPlugin({
        done: () => {
          if (process.env.NODE_ENV === 'production') return;
          console.log(noticeMessage);
        }
      })
    ],
    performance: false
  };
};
