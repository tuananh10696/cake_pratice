/*
画面内に入ったものに.activeをつける。

【pug】
.intersect-elem

同じ高さにあるものに順に出すようにdelayを付けたい時は、
.intersect-elem(data-delay="0.1")
のように指定。


【css（例】
.intersect-elem {
  opacity: 0;
  transition: opacity 1s ease-out;
  &.active {
    opacity: 1;
  }
}

【加減】
this.io = new IntersectionObserver(this.onIntersect, { rootMargin: '-20% 0% -20% 0%' });
で調節。上の例だと、上20%、下20% = 画面内縦中央60%に入った時
*/
export default class IntersectElements {
  constructor() {
    const intersectElements: HTMLElement[] = Array.prototype.slice.call(document.getElementsByClassName('intersect-elem'));
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
    this.io = new IntersectionObserver(this.onIntersect, { rootMargin: '-20% 0% -20% 0%' });
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
