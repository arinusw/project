import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 375, 'height': 667})
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_selector("#home")

        # Take mobile home screenshot
        await page.screenshot(path="verification/screenshots/mobile_home.png")

        # Open nav menu
        await page.click("#nav-toggle")
        await page.wait_for_timeout(300)
        await page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close nav menu
        await page.click("#nav-close")
        await page.wait_for_timeout(300)

        # Toggle theme in mobile view
        await page.click("#theme-button")
        await page.wait_for_timeout(300)
        await page.screenshot(path="verification/screenshots/mobile_dark.png")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
