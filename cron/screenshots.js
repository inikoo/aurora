const puppeteer = require('puppeteer');
const devices = require('puppeteer/DeviceDescriptors');

const iPhone = devices['iPhone 4'];
const iPad = devices['iPad'];
const argv = require('minimist')(process.argv.slice(2));
const url = argv.url;
const pageKey = argv.pageKey;
var fs = require('fs');
var dir = './cron/screenshots';
if (!fs.existsSync(dir)){ fs.mkdirSync(dir); }

async function run() {
    let browser = await puppeteer.launch({args: ['--no-sandbox', '--disable-setuid-sandbox']});
    let page = await browser.newPage();
    await page.setViewport({ width: 1920, height: 1080 });
    await page.goto(url , {waitUntil: 'networkidle0'});
    await page.screenshot({ path: `${file_root}_desktop_full_screenshot.jpeg`, type: 'jpeg', fullPage: true});
    await page.screenshot({ path: `${file_root}_desktop_screenshot.jpeg`, type: 'jpeg'});
    await page.close();
    let pageM = await browser.newPage();
    await pageM.emulate(iPhone);
    await pageM.goto(url, {waitUntil: 'networkidle0'});
    await pageM.screenshot({ path: `${file_root}_mobile_screenshot.jpeg`, type: 'jpeg' });
    await pageM.close();
    let pageT = await browser.newPage();
    await pageT.emulate(iPad);
    await pageT.goto(url, {waitUntil: 'networkidle0'});
    await pageT.screenshot({ path: `${file_root}_tablet_screenshot.jpeg`, type: 'jpeg'});
    await pageT.close();
    await browser.close();
}

run();
