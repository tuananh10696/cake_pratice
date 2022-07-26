const Task = require('data.task');
const Maybe = require('data.maybe');
const htmlParser = require('htmlparser2');
const urlUtil = require('url');
const path = require('path');
const fingerPrinter = require('fingerprinting');
const fs = require('fs');
const { List } = require('immutable-ext');

const always = (thing) => (never) => thing;
const id = (x) => x;

const extractAssetUrlsFromHtml = (html) => {
  return new Task((reject, resolve) => {
    const styleHrefs = [];
    const parser = new htmlParser.Parser({
      onopentag(tagName, attrs) {
        switch (tagName) {
          case 'img':
            styleHrefs.push(attrs.src);
            break;
          case 'link':
            styleHrefs.push(attrs.href);
            break;
          case 'script':
            styleHrefs.push(attrs.src);
            break;
          default:
            return;
        }
      },
      onend() {
        const rowList = Array.from(new Set(styleHrefs));
        resolve(List(rowList));
      },
      onerror: reject
    });
    parser.parseComplete(html);
  });
};

const taskFromNullable = (rejectVal) => (nullable) => {
  if (nullable == null) return Task.rejected(rejectVal);
  return Task.of(nullable);
};

const extractFileNameFromUrl = (url) => {
  return new Task((reject, resolve) => (!url ? reject({ message: 'url cannot be null' }) : resolve(urlUtil.parse(url).pathname)));
};

const mergeRootWithFileName = (root) => (fileName) => {
  return path.join(root, fileName);
};

const getObjectProperty = (prop) => (obj) => {
  if (prop == null || obj == null || obj[prop] == null) return Maybe.Nothing();
  return Maybe.Just(obj[prop]);
};

const readFile = (fileName) =>
  new Task((reject, resolve) => fs.readFile(fileName, 'utf-8', (err, contents) => (err ? reject(err) : resolve(contents))));

const readAssetFile = () => (fileName) => {
  return readFile(fileName)
    .map((fileContents) => {
      return fileContents;
    })
    .rejectedMap((err) => {
      return err;
    });
};

const createFileFingerPrint = (fileName) => (assetRootReplacement) => (contents) => {
  const url = assetRootReplacement
    .chain((newRoot) => {
      if (typeof newRoot !== 'string') return Maybe.Nothing();
      return Maybe.Just(urlUtil.resolve(newRoot, fileName));
    })
    .getOrElse(fileName);

  const parsedUrl = urlUtil.parse(url, true);
  parsedUrl.search = null;

  const fingerPrint = fingerPrinter(fileName, {
    format: '{hash}',
    content: contents
  });
  parsedUrl.query.v = fingerPrint.file;
  return {
    original: fileName,
    fingerPrinted: urlUtil.format(parsedUrl)
  };
};

const cacheBustHtml = (html, assetsRoot, options) => {
  return taskFromNullable({ message: 'The asset root cannot be null or undefined' })(assetsRoot).chain((assetsRoot) =>
    extractAssetUrlsFromHtml(html)
      .chain((styleHrefs) =>
        styleHrefs.traverse(Task.of, (styleHref) =>
          extractFileNameFromUrl(styleHref)
            .map(mergeRootWithFileName(assetsRoot))
            .chain(readAssetFile(getObjectProperty('logger')(options))) // TODO: How do I pull this out so I can unit test this properly?
            .map(createFileFingerPrint(styleHref)(getObjectProperty('replaceAssetRoot')(options)))
            .orElse(always(Task.of()))
        )
      )
      .map((fingerPrints) => fingerPrints.filter(id))
      .map((fingerPrints) =>
        fingerPrints.reduce((acc, fingerPrint) => acc.split(fingerPrint.original).join(fingerPrint.fingerPrinted), html)
      )
  );
};

module.exports = cacheBustHtml;
