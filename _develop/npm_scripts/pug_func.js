{
  nodeFs : require('fs'),
  nodeDest : path.join(process.cwd(), process.env.npm_package_config_dest + process.env.npm_package_config_subDirectory),
  nodePath : require('path'),
  nodeSize : require('image-size')
}
