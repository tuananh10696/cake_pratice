module.exports = function (api) {
  api.cache(true);

  const targets = '> 2%, last 2 major versions, iOS >= 12, not ie > 0, not ie_mob > 0';
  const presets = [
    ['@babel/preset-env'],
    [
      '@babel/preset-react',
      {
        runtime: 'automatic'
      }
    ],
    '@babel/preset-typescript',
    '@emotion/babel-preset-css-prop'
  ];
  const plugins = [
    ['@babel/plugin-proposal-optional-chaining'],
    ['@babel/plugin-syntax-dynamic-import'],
    [
      '@babel/plugin-proposal-decorators',
      {
        legacy: true
      }
    ],
    ['@babel/plugin-proposal-class-properties'],
    ['@emotion']
  ];

  const env = {};

  return {
    targets,
    presets,
    plugins,
    env
  };
};
