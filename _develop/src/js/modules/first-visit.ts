import Static from './static';

export default class FirstVisit {
  /**
   * Creates an instance of FirstVisit.
   */
  constructor() {
    if (sessionStorage.getItem('access')) {
      Static.IS_FIRST_VISIT = false;
    } else {
      sessionStorage.setItem('access', 0);
      Static.IS_FIRST_VISIT = true;
    }
    // ローカル確認用（常に初回）
    if (process.env.NODE_ENV === 'development') {
      Static.IS_FIRST_VISIT = true;
      console.log(`develop console--first visits ${Static.IS_FIRST_VISIT}`);
    }
  }
}
