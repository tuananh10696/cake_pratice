import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

export default class MovingText {
  /**
   * Creates an instance of Loading.
   */
  private movingText: HTMLElement | null = document.getElementsByClassName('js-movingText')[0];
  constructor() {
    if (this.movingText) {
      const txtImg = this.movingText.getElementsByClassName('txt-img-wrap')[0];
      gsap.set(txtImg, {
        xPercent: 25
      });
      gsap.to(txtImg, {
        xPercent: 0,
        scrollTrigger: {
          trigger: txtImg,
          start: 'top bottom',
          end: 'bottom center',
          scrub: 1
        }
      });
    }
  }
}
