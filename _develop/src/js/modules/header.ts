export default class Header {
  /**
   * Creates an instance of Header.
   */
  private header: HTMLElement | null = document.getElementById('header');
  private flag: boolean = false;
  private headerBtn: HTMLElement | null = this.header?.getElementsByClassName('js-headerBtn')[0];
  // eslint-disable-next-line no-unsafe-optional-chaining
  private headerLinks: HTMLElement[] | null = [...this.header?.getElementsByClassName('header__nav-link')];

  constructor() {
    this.headerBtn?.addEventListener('click', this.toggle, false);
    this.headerLinks?.forEach((elem) => {
      elem.addEventListener('click', this.close, false);
    });
  }

  private toggle = () => {
    if (this.flag) {
      this.close();
    } else {
      this.open();
    }
  };

  private open = () => {
    this.header?.classList.add('open');
    this.flag = true;
  };

  private close = () => {
    this.header?.classList.remove('open');
    this.flag = false;
  };
}
