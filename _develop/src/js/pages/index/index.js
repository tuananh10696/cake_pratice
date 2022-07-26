// import Loading from './modules/loading';
import Animation from './modules/animation';
// import MovingText from './modules/moving-text';

export default class Index {
  /**
   * Creates an instance of Index.
   */
  constructor() {
    window.addEventListener('load', () => {
      // new Loading();
      new Animation();
      // new MovingText();
    });
  }
}
