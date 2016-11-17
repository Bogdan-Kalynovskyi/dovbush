import { DovbushPage } from './app.po';

describe('dovbush App', function() {
  let page: DovbushPage;

  beforeEach(() => {
    page = new DovbushPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
