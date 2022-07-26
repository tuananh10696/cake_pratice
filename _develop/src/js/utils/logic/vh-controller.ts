import Util from '../util';

if (window.MSInputMethodContext && document.documentMode) {
  const script = document.createElement('script');
  script.src = 'https://cdn.jsdelivr.net/gh/nuxodin/ie11CustomProperties@4.1.0/ie11CustomProperties.min.js';
  document.body.appendChild(script);
  script.onload = () => {
    try {
      const evt = window.document.createEvent('UIEvents');
      evt.initUIEvent('resize', true, false, window, 0);
      window.dispatchEvent(evt);
    } catch (e) {}
  };
}

interface ISize {
  width: number;
  height: number;
}

export default class VhController {
  private isOnlyWidth: boolean = false;
  private refSize: ISize = { width: 0, height: 0 };
  private currentSize: ISize = { width: 0, height: 0 };

  constructor(isOnlyWidth: boolean = false) {
    this.isOnlyWidth = isOnlyWidth;
    const bodyWidth: number = (document.body && document.body.clientWidth) || 0;
    this.currentSize.width = bodyWidth;
    this.currentSize.height = window.innerHeight;

    window.addEventListener('resize', this.onResize, Util.isPassive);
    this.onResize();
  }

  private onResize = (): void => {
    const bodyWidth: number = (document.body && document.body.clientWidth) || 0;

    if (this.isOnlyWidth) {
      if (Util.IS_SP) {
        if (bodyWidth === this.refSize.width) return;
      }
    }

    this.refSize.width = bodyWidth;
    this.refSize.height = window.innerHeight;

    document.documentElement.style.setProperty('--vw', `${this.refSize.width / 100}px`);
    document.documentElement.style.setProperty('--vh', `${this.refSize.height / 100}px`);
  };
}
