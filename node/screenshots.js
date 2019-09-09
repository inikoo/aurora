const puppeteer = require('puppeteer');
const devices = require('puppeteer/DeviceDescriptors');

const iPhone = devices['iPhone 8'];
const iPad = devices['iPad landscape'];
const argv = require('minimist')(process.argv.slice(2));
const url = argv.url;
const type = argv.type;

const file_root = argv.file_root;


var fs = require('fs');
var dir = './tmp';
if (!fs.existsSync(dir)){ fs.mkdirSync(dir); }


switch (type) {
    case 'current_screenshots':
        current_screenshots()
        break;
    case 'history_screenshots':
        history_screenshots()
        break;
}




async function current_screenshots() {
    let browser = await puppeteer.launch({args: ['--no-sandbox', '--disable-setuid-sandbox']});
    let page = await browser.newPage();
    await page.setViewport({ width: 1366, height: 1024 });
    await page.goto(url , {timeout: 1200000,waitUntil: 'networkidle0'});
    await page.screenshot({ path: `${file_root}_desktop_screenshot.jpeg`, type: 'jpeg'});
    await page.screenshot({ path: `${file_root}_full_webpage_thumbnail_screenshot.jpeg`, type: 'jpeg', fullPage: true});




    await page.close();
    let pageM = await browser.newPage();
    await pageM.emulate(iPhone);
    await pageM.goto(url, {timeout: 1200000,waitUntil: 'networkidle0'});
    await pageM.screenshot({ path: `${file_root}_mobile_screenshot.jpeg`, type: 'jpeg' });
    await pageM.close();
    let pageT = await browser.newPage();
    await pageT.emulate(iPad);
    await pageT.goto(url, {timeout: 1200000,waitUntil: 'networkidle0'});
    await pageT.screenshot({ path: `${file_root}_tablet_screenshot.jpeg`, type: 'jpeg'});
    await pageT.close();


    await browser.close();
}



async function history_screenshots() {
    let browser = await puppeteer.launch({args: ['--no-sandbox', '--disable-setuid-sandbox']});
    let page = await browser.newPage();
    await page.setViewport({ width: 1366, height: 1024 });
    await page.goto(url , {timeout: 1200000,waitUntil: 'networkidle0'});
    await page.screenshot({ path: `${file_root}_desktop_full_screenshot.jpeg`, type: 'jpeg', fullPage: true});
    await page.close();

    await browser.close();
}

