import asyncio
import os
from playwright.async_api import async_playwright

async def run_desktop_verification():
    os.makedirs("verification/screenshots", exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={"width": 1280, "height": 800})
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_timeout(1000)

        # Trigger scroll reveal animations
        for y in range(0, 3000, 500):
            await page.mouse.wheel(0, 500)
            await page.wait_for_timeout(200)

        await page.mouse.wheel(0, -3000)
        await page.wait_for_timeout(500)

        # Desktop screenshot light
        await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Toggle theme to dark
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(500)

        # Desktop screenshot dark
        await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_desktop_verification())
