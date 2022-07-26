// import Util from '../../../utils/util';

export default class Animation {
  /**
   * Creates an instance of Loading.
   */
  constructor() {
    const intersectElements: HTMLElement[] = Array.prototype.slice.call(document.getElementsByClassName('intersect-elem'));
    // Util.Dispatcher.addEventListener('IS_LOADED', () => {
    //   for (const elem of intersectElements) {
    //     new IntersectElem(elem);
    //   }
    // });
    for (const elem of intersectElements) {
      new IntersectElem(elem);
    }
  }
}
class IntersectElem {
  private node: HTMLElement;
  private io: IntersectionObserver | null = null;

  constructor(node: HTMLElement) {
    this.node = node;
    if (this.node.classList.toString().includes('standby')) {
      return;
    }

    this.node.classList.add('standby'); // 多重掛け防止措置
    this.io = new IntersectionObserver(this.onIntersect, { rootMargin: '0% 0% -20% 0%' });
    this.io.observe(this.node);
  }
  private onIntersect = (changes: IntersectionObserverEntry[]): void => {
    if (changes[0].isIntersecting) {
      const delay: number = this.node.dataset.delay ? Number(this.node.dataset.delay) : 0;

      setTimeout(() => {
        this.node.classList.add('active');
        this.io?.unobserve(this.node);
        this.io = null;
      }, delay * 1000);
    }
  };
}
