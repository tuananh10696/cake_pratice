import Util from '../util';

/*
1) これで全クラスからPC/SPかどうかにアクセス出来る。
Util.IS_SP;

2) こうすると切り替わりを監視出来る(onResizeよりスマート)
Util.Dispatcher.addEventListener('DeviceChange', this.onChange);
onChange(); //-  初回

onChange = (): void => {
  console.log(Util.IS_SP);
};
*/

export default class DeviceWatcher {
  constructor() {
    const mediaQueryList: MediaQueryList = window.matchMedia('(min-width: 769px)');

    if (mediaQueryList.addEventListener) {
      mediaQueryList.addEventListener('change', this.onDeviceChange);
    } else {
      mediaQueryList.addListener(this.onDeviceChange);
    }
    this.onDeviceChange(mediaQueryList);
  }

  private onDeviceChange = (event: any) => {
    if (event.matches) {
      Util.IS_SP = false;
    } else {
      Util.IS_SP = true;
    }
    Util.Dispatcher.dispatchEvent('DeviceChange');
  };
}
