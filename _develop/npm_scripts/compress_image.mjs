import fs from 'fs';
import paths from '../paths.js';
import colors from './colors.js';
import { ImagePool } from '@squoosh/lib';

const fsp = fs.promises;
const IMAGE_DIR = `${paths.appBuild}/${paths.assetPath}/images`;

const jpgEncodeOptions = {
  mozjpeg: { quality: 75 }
};

const pngEncodeOptions = {
  oxipng: {
    effort: 2
  }
};

const preprocessOptions = {
  quant: {
    numColors: 256,
    dither: 0.9
  }
};

const listFiles = (dir) =>
  fs
    .readdirSync(dir, { withFileTypes: true })
    .flatMap((dirent) => (dirent.isFile() ? [`${dir}/${dirent.name}`] : listFiles(`${dir}/${dirent.name}`)));

const imageList = listFiles(IMAGE_DIR);
const compressedTargets = [];

for (const image of imageList) {
  if (image.match(/\.(jpe?g)$/i) || image.match(/\.(png)$/i)) {
    const fileSize = fs.statSync(image).size;
    if (fileSize >= 20000) {
      compressedTargets.push(image);
    }
  }
}

let imagePool = new ImagePool();

const imagePoolList = compressedTargets.map((fileName) => {
  const imageFile = fs.readFileSync(fileName);
  const image = imagePool.ingestImage(imageFile);

  return { name: fileName, image };
});

let doneCount = 0;

await Promise.all(
  imagePoolList.map(async (item) => {
    const { image } = item;
    item.size = fs.statSync(item.name).size;

    if (/\.(jpe?g)$/i.test(item.name)) {
      await image.encode(jpgEncodeOptions);
    } else if (/\.(png)$/i.test(item.name)) {
      await image.decoded;
      await image.preprocess(preprocessOptions);
      await image.encode(pngEncodeOptions);
    }
    doneCount++;
    console.clear();
    console.log(`${colors.cyanBg}${colors.black} Compress images larger than 20KB ${colors.reset}  ${doneCount} / ${imagePoolList.length}`);
  })
);

for (const item of imagePoolList) {
  const {
    name,
    image: { encodedWith },
    size
  } = item;

  let data;

  let compressSize = 0;

  if (encodedWith.mozjpeg) {
    data = await encodedWith.mozjpeg;
    compressSize = data.size;
  } else if (encodedWith.oxipng) {
    data = await encodedWith.oxipng;
    compressSize = data.size;
  }

  if (size - compressSize >= 4000) {
    console.log(
      `${colors.redBg}${colors.black} Compress ${colors.reset} ${colors.green}${name.match(/([^/]+?)?$/)[1]}${colors.reset} ${Math.round(
        size / 1000
      )} KB ${colors.green}=>${colors.reset} ${Math.round(compressSize / 1000)} KB ${colors.cyanBg}${colors.black} ${Math.round(
        (compressSize / size) * 100
      )}% ${colors.reset}`
    );
    await fsp.writeFile(name, data.binary);
  }
}

await imagePool.close();
imagePool = null;

console.log(`\n${colors.greenBg}${colors.black} Image compression Complete ${colors.reset}\n\n`);
