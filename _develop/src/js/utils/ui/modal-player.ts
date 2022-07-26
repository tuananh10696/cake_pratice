/*
  youtubeのモーダル多様するので入れときます。
  .modal-triggerとdata-movieがあれば動く。

  【pug】
  p.modal-trigger(data-movie="YOUTUBE_ID") 再生


  【css(例)】
  closeボタンは
  http://matsumoto.sample.caters.jp/close-maker/
  で作ると楽。

  .modal-player {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    z-index: 9999;
    &__bg {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
    }
    &__window {
      position: relative;
      width: 720px;
      &:before {
        content: '';
        display: block;
        width: 100%;
        padding-top: 56.25%;
      }
      iframe {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
      }
    }
    &__close {
      cursor: pointer;
      position: relative;
      width: 32px;
      height: 32px;
      position: absolute;
      right: 0;
      top: -50px;
      color: #fff;

      &:before,
      &:after {
        content: '';
        display: block;
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -22px;
        margin-top: -1px;
        width: 44px;
        height: 2px;
        background-color: currentColor;
      }

      &:before {
        transform: rotate(45deg);
      }
      &:after {
        transform: rotate(-45deg);
      }
    }
  }
*/

import gsap from 'gsap';
import Util from '../util';

export default class ModalPlayer {
  constructor() {
    const triggers: HTMLElement[] = Array.prototype.slice.call(document.getElementsByClassName('modal-trigger'));
    for (const trigger of triggers) {
      trigger.addEventListener('click', this.openModal, false);
    }
  }

  private openModal = (e: any): void => {
    if (e.currentTarget.dataset.movie) {
      new Modal(String(e.currentTarget.dataset.movie));
    } else {
      Util.warn('modal-player.ts : data-movieが無いです');
    }
  };
}

class Modal {
  private player: HTMLElement | null = null;
  private bg: HTMLElement | null = null;
  private close: HTMLElement | null = null;

  constructor(id: string) {
    const html = `
      <div class="modal-player" id="modal-player">
        <p class="modal-player__bg" id="modal-player__bg"></p>
        <div class="modal-player__window">
          <iframe src="https://www.youtube.com/embed/${id}?autoplay=1&playsinline=1&mute=1&rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          <p class="modal-player__close" id="modal-player__close"></p>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);

    this.player = document.getElementById('modal-player');
    this.bg = document.getElementById('modal-player__bg');
    this.close = document.getElementById('modal-player__close');

    gsap.from(this.player, 0.35, { opacity: 0, ease: 'power1.out' });

    this.bg?.addEventListener('click', this.prepClose, false);
    this.close?.addEventListener('click', this.prepClose, false);
  }

  private prepClose = (): void => {
    gsap.to(this.player, 0.35, { opacity: 0, ease: 'power1.in', onComplete: this.purge });
  };

  private purge = (): void => {
    this.bg?.removeEventListener('click', this.prepClose);
    this.close?.removeEventListener('click', this.prepClose);

    if (this.player) document.body.removeChild(this.player);

    this.player = null;
    this.bg = null;
    this.close = null;
  };
}
