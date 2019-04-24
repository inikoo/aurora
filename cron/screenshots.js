const puppeteer = require('puppeteer');
const devices = require('puppeteer/DeviceDescriptors');
const iPhone = devices[ 'iPhone 4' ];
const iPad = devices[ 'iPad' ];
const argv = require('minimist')(process.argv.slice(2));
const url = argv.url;
const pageKey = argv.pageKey;

async function run() {
    let browser = await puppeteer.launch({args: ['--no-sandbox', '--disable-setuid-sandbox']});
    let page = await browser.newPage();
    await page.setViewport({ width: 1920, height: 1080 });
    await page.goto(url , {waitUntil: 'domcontentloaded'});
    await page.screenshot({ path: `./cron/images/${pageKey}F.jpg`, type: 'jpeg', fullPage: true});
    await page.screenshot({ path: `./cron/images/${pageKey}D.jpg`, type: 'jpeg'});
    await page.close();
    let pageM = await browser.newPage();
    await pageM.emulate(iPhone);
    await pageM.goto(url, {waitUntil: 'domcontentloaded'});
    await pageM.screenshot({ path: `./cron/images/${pageKey}M.jpg`, type: 'jpeg' });
    await pageM.close();
    let pageT = await browser.newPage();
    await pageT.emulate(iPad);
    await pageT.goto(url, {waitUntil: 'domcontentloaded'});
    await pageT.screenshot({ path: `./cron/images/${pageKey}T.jpg`, type: 'jpeg'});
    await pageT.close();
    await browser.close();
}

run();