import Util from './util';

/**
 * body スクロール固定 : モーダル時など。
 * @export
 * @class ScrollController
 *
 * どのクラスからでも
 * ロック: Util.Dispatcher.dispatchEvent('SCROLL_LOCK');
 * 開放: Util.Dispatcher.dispatchEvent('SCROLL_RELEASE');
 */
export default class ScrollController {
  private prevTop: number = 0;
  private isLock: boolean = false;
  private isToggle: boolean = false;

  constructor() {
    Util.Dispatcher.addEventListener('SCROLL_LOCK', this.lock);
    Util.Dispatcher.addEventListener('SCROLL_RELEASE', this.release);
  }
  /**
   * ロックする
   */
  public lock = (): void => {
    if (!this.isToggle && this.isLock) return;
    this.isLock = true;
    this.prevTop = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
    document.body.style.position = 'fixed';
    document.body.style.left = '0';
    document.body.style.top = `${-this.prevTop}px`;
    document.body.style.width = '100%';
  };

  /**
   * 開放
   */
  public release = (): void => {
    if (!this.isToggle && !this.isLock) return;
    this.isLock = false;
    document.body.removeAttribute('style');
    window.scrollTo(0, this.prevTop);
  };

  /**
   * 交互に切替
   */
  public toggle = (): void => {
    this.isToggle = true;
    this.isLock = !this.isLock;
    this.isLock ? this.lock() : this.release();
    this.isToggle = false;
  };
}

/*
fixじゃなくてイベントキャンセル版。
スクロールバーの表示・非表示でガク付くのが気になる場合こっち
*/
/*
export default class ScrollController {
  private isLock: boolean = false;
  private isToggle: boolean = false;

  private scrollCancel = (event: any): void => {
    event.preventDefault();
  };

  public lock = (): void => {
    if (!this.isToggle && this.isLock) return;
    this.isLock = true;
    document.addEventListener('touchmove', this.scrollCancel, { passive: false });
    document.addEventListener('mousewheel', this.scrollCancel, { passive: false });
  };

  public release = (): void => {
    if (!this.isToggle && !this.isLock) return;
    this.isLock = false;
    document.removeEventListener('touchmove', this.scrollCancel);
    document.removeEventListener('mousewheel', this.scrollCancel);
  };

  public toggle = (): void => {
    this.isToggle = true;
    this.isLock = !this.isLock;
    this.isLock ? this.lock() : this.release();
    this.isToggle = false;
  };
}
*/
