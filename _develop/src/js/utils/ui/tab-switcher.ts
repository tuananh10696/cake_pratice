/* ===================================================================
タブ切り替え

【pug】
.tab-switch
  nav
    ul
      li.tab-switch__trigger 1
      li.tab-switch__trigger 2
      li.tab-switch__trigger 3

  .tab-switch__body
    .tab-switch__content 1
    .tab-switch__content 2
    .tab-switch__content 3


【css】
.tab-switch {
  &__trigger {
    &:hover,
    &.active {
      // active時
    }
    &.active {
      pointer-events:none;
    }
  }

  &__content {
    display:none;
    &.active {
      display:block;
    }
  }
}
*/

/**
 *
 * @export
 * @class TabSwitcher
 */
export default class TabSwitcher {
  constructor() {
    const tabSwitch: HTMLElement[] = Array.prototype.slice.call(document.getElementsByClassName('tab-switch'));
    for (const elem of tabSwitch) {
      new TabSwitchElements(elem);
    }
  }
}

class TabSwitchElements {
  private navigators: HTMLElement[] = [];
  private contents: HTMLElement[] = [];

  constructor(node: HTMLElement) {
    this.navigators = Array.prototype.slice.call(node.getElementsByClassName('tab-switch__trigger'));
    this.contents = Array.prototype.slice.call(node.getElementsByClassName('tab-switch__content'));

    if (this.navigators.length) this.navigators[0].classList.add('active');
    if (this.contents.length) this.contents[0].classList.add('active');

    for (const navi of this.navigators) {
      navi.addEventListener('click', this.tabChange, false);
    }
  }

  private tabChange = (e: any): void => {
    const index: number = Array.prototype.indexOf.call(this.navigators, e.currentTarget);

    for (let i: number = 0; i < this.navigators.length; i++) {
      if (i === index) this.navigators[i].classList.add('active');
      else this.navigators[i].classList.remove('active');
    }

    for (let i: number = 0; i < this.contents.length; i++) {
      if (i === index) this.contents[i].classList.add('active');
      else this.contents[i].classList.remove('active');
    }
  };
}
