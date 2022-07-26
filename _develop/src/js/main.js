import { pathToRegexp } from 'path-to-regexp';
import VhController from './utils/logic/vh-controller';
import AnchorLink from './utils/ui/anchor-link';
import DeviceWatcher from './utils/logic/device-watcher';
import ScrollController from './utils/scroll-controller';

import Header from './modules/header';
import FirstVisit from './modules/first-visit';

/*
 スクロールバー考慮vh計算。監視を幅だけに限定したい時はtrueを入れる。
 （MVだけが100vhな時にresizeで監視するとアドレスバーの出入りでガタつく事があるため）
*/
new VhController();

// break-pointによるPC/SP判別機能。 詳細はdevice-watcher.tsを見てみよう。
new DeviceWatcher();

// モーダル等で下のウインドウが動かないようにする。scroll-controller.ts参照
new ScrollController();

/**
 * ページによってcode-splitされたJSを振り分ける仕組み
 * @return {any} - module
 */
const getComponent = async () => {
  const pathname = window.location.pathname;

  // webpackChunkNameは重要で、その名前のjsが書き出されるのでページ毎に指定して下さい。
  // 例） news/ なら webpackChunkName: "news"
  if (pathToRegexp('/').exec(pathname)) {
    await import(/* webpackChunkName:"index" */ './pages/index/index').then((module) => {
      new module.default();
    });
  }

  /* example
   * https://github.com/pillarjs/path-to-regexp
   * http://forbeslindesay.github.io/express-route-tester/
   * :aaa は何かしか変数のように入るの意。 e.g. /news/1/ => /news/:id/
   * :aaa? は何かしか変数が入るがoptional. e.g. /news/ or /news/1/ => /news/:id?/
   */

  /* ***.htmlも含みたい場合、単純に配列にするか
   * pathToRegexp(['/', '/index.html', '/index_2.html']).exec(pathname);
   * 正規表現で対応する。 */
  // pathToRegexp(/\/.*(\.html)*/).exec(pathname)
};

export default class Main {
  constructor() {
    /*
      // よく使うので入れておきます。演出等で必ず一番上から始めたい時はこちら(Chrome等の位置記憶を破棄)
      if ('scrollRestoration' in window.history) {
        window.history.scrollRestoration = 'manual';
        window.scrollTo(0,0);
      };
    */

    // アンカーリンク。固定ヘッダー分引くとかにも対応している。
    // 使い方はanchor-link.ts参照。
    new AnchorLink('#header');
    new Header();
    new FirstVisit();
    getComponent();
  }
}

window.addEventListener('DOMContentLoaded', () => {
  new Main();
});

window.addEventListener('load', () => {
  new AnchorLink('#header');
});
