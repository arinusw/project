import asyncio
from playwright.async_api import async_playwright

async def run_mobile_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 375, 'height': 667}, is_mobile=True)
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_timeout(300)

        # 1. Capture Mobile Home View
        await page.screenshot(path="verification/screenshots/mobile_home.png", full_page=False)

        # 2. Toggle Mobile Navigation Menu
        nav_toggle = page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page.wait_for_timeout(300)
        await page.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

        # Close menu
        nav_close = page.locator("#nav-close")
        await nav_close.click(force=True)
        await page.wait_for_timeout(300)

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_mobile_verification())
