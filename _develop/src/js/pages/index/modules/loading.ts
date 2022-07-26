import Static from '../../../modules/static';
import gsap from 'gsap';
import Animation from './animation';
import Util from '../../../utils/util';

export default class Loading {
  /**
   * Creates an instance of Loading.
   */
  private root: HTMLElement | null = document.getElementById('root');
  private loading: HTMLElement | null = document.getElementById('loading');
  constructor() {
    new Animation();
    if (Static.IS_FIRST_VISIT) {
      this.opening();
    } else {
      this.root?.classList.add('loaded');
      Util.Dispatcher.dispatchEvent('IS_LOADED');
    }
  }

  private opening = () => {
    const logoSrc = document.querySelector('.header__logo img')?.getAttribute('src');
    const openingLogo = document.createElement('img');
    openingLogo.src = logoSrc;
    openingLogo.classList.add('loading__logo');
    this.loading?.after(openingLogo);
    gsap.fromTo(
      openingLogo,
      {
        opacity: 0
      },
      {
        opacity: 1,
        delay: 0.2,
        duration: 0.8,
        onComplete: () => {
          this.root?.classList.add('first-loaded');
          this.fadeOutLogo(openingLogo);
        }
      }
    );
  };
  private fadeOutLogo = (openingLogo) => {
    gsap.to(openingLogo, {
      opacity: 0,
      delay: 0.6,
      onComplete: () => {
        Util.Dispatcher.dispatchEvent('IS_LOADED');
      }
    });
  };
}
