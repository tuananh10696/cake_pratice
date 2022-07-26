const spDesignWidth: number = 768;

export const rem = (num: number): string => {
  return `${num / 16}rem`;
};

export const vw = (num: number): string => {
  return `${(num / spDesignWidth) * 100}vw`;
};

export const media = {
  pc: '@media (min-width: 769px)',
  sp: '@media (max-width: 768px)'
};
