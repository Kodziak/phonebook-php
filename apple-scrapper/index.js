const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
  const browser = await puppeteer.launch({
    headless: true,
  });

  const addressArr = [];

  const page = await browser.newPage();
  await page.goto('https://www.apple.com/retail/storwelist/');

  const urls = await page.evaluate(() => {
    return Array.from(document.querySelectorAll('.address-lines a')).map(url => url.href);
  });

  for(let i = 0; i < urls.length; i++ ) {
  // for(let i = 0; i < 3; i++ ) {
    await page.goto(urls[i]);

    let address = await page.$eval('address', addr => addr.innerText.split('\n'));

    addressArr.push({
      street: address[0],
      city: address[1].split(',').shift(),
      postCode: address[1].split(',').pop(),
      phoneNumber: address[2],
    });

    console.log({
      street: address[0],
      city: address[1].split(',').shift(),
      postCode: address[1].split(',').pop(),
      phoneNumber: address[2],
    });
  }

  fs.readFile('data.json', 'utf-8', function(err, data) {
    if (err) fs.appendFile('data.json', [], function(err, suc) {
      console.log('file created');
    });

    fs.appendFileSync('data.json', JSON.stringify(addressArr));
  })

  browser.close();
})();