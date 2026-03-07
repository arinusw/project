const playwright = require('playwright');

(async () => {
  const browser = await playwright.chromium.launch();
  const context = await browser.newContext();
  const page = await context.newPage();

  // Start local server
  const { spawn } = require('child_process');
  const server = spawn('python3', ['-m', 'http.server', '8080'], { cwd: './ar-creative' });

  await new Promise(resolve => setTimeout(resolve, 2000)); // Wait for server to start

  // Desktop screenshot
  await page.setViewportSize({ width: 1280, height: 800 });
  await page.goto('http://localhost:8080');
  await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
  await new Promise(resolve => setTimeout(resolve, 1000));
  await page.screenshot({ path: 'desktop_home.png', fullPage: true });

  // Mobile screenshot
  await page.setViewportSize({ width: 375, height: 667 });
  await page.goto('http://localhost:8080');
  await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
  await new Promise(resolve => setTimeout(resolve, 1000));
  await page.screenshot({ path: 'mobile_home.png', fullPage: true });

  await browser.close();
  server.kill();
})();
