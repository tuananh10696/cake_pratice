/* ===================================================================
こういう構造で作っておくと、newした時自動でアコーディオンが実装される。

【pug】
.accordion
  .accordion__trigger(開閉ボタン。.activeが付いたり消えたり)
  .accordion__target
    .accordion__inner

【css】
.accordion {
  box-sizing: border-box;
  position: relative;
  &__trigger {
    cursor: pointer;
    &.active {
      active時
    }
  }
  &__target {
    overflow: hidden;
    height: 0;
    box-sizing: border-box;
  }
}
=================================================================== */
import gsap from 'gsap';
import Util from '../util';

/**
 * アコーディオン
 * 上の構造で作ればimportしてnewするだけで使える。 new Accordion();
 * @export
 * @class Accordion
 */
export default class Accordion {
  private accordionInstance: AccordionElem[] | null = [];

  constructor() {
    const accordionList: HTMLElement[] = Array.prototype.slice.call(document.getElementsByClassName('accordion'));
    for (const accordion of accordionList) {
      if (this.accordionInstance) {
        this.accordionInstance.push(new AccordionElem(accordion));
      }
    }
  }

  public destroy = (): void => {
    if (!this.accordionInstance) return;
    for (const elem of this.accordionInstance) {
      elem.destroy();
    }
    this.accordionInstance = null;
  };
}

/* =================================================================== */

class AccordionElem {
  private isOpen: boolean = false;
  private trigger: HTMLElement | null;
  private wrapper: HTMLElement | null;
  private inner: HTMLElement | null;

  constructor(node: HTMLElement) {
    this.trigger = Array.prototype.slice.call(node.getElementsByClassName('accordion__trigger'))[0];
    this.wrapper = Array.prototype.slice.call(node.getElementsByClassName('accordion__target'))[0];
    this.inner = Array.prototype.slice.call(node.getElementsByClassName('accordion__inner'))[0];

    if (!this.trigger || !this.wrapper || !this.inner) {
      Util.warn('nullCheckError: accordion.ts : line58');
      return;
    }

    this.trigger.addEventListener('click', this.toggle, false);
  }

  private toggle = (): void => {
    if (!this.trigger || !this.wrapper || !this.inner) {
      return;
    }
    this.isOpen = !this.isOpen;
    gsap.killTweensOf(this.wrapper);

    if (this.isOpen) {
      this.trigger.classList.add('active');
      const toHeight: number = this.inner.offsetHeight;
      gsap.to(this.wrapper, 0.4, { height: toHeight, ease: 'power1.out' });
    } else {
      this.trigger.classList.remove('active');
      gsap.to(this.wrapper, 0.4, { height: 0, ease: 'power1.in' });
    }
  };

  public destroy = (): void => {
    if (!this.trigger || !this.wrapper || !this.inner) {
      return;
    }

    this.trigger.removeEventListener('click', this.toggle);
    gsap.killTweensOf(this.wrapper);
    this.wrapper.removeAttribute('style');

    this.trigger = null;
    this.wrapper = null;
    this.inner = null;
  };
}
