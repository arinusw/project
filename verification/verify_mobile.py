import asyncio
import os
from playwright.async_api import async_playwright

async def run_mobile_verification():
    os.makedirs("verification/screenshots", exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={"width": 375, "height": 667})
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_timeout(1000)

        # Mobile drawer open
        nav_toggle = page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Mobile drawer close
        nav_close = page.locator("#nav-close")
        await nav_close.click(force=True)
        await page.wait_for_timeout(300)

        # Trigger animations
        for y in range(0, 3500, 400):
            await page.mouse.wheel(0, 400)
            await page.wait_for_timeout(200)

        await page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_mobile_verification())
