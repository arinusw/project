import asyncio
import os
from playwright.async_api import async_playwright

async def verify_viewports():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # Desktop top viewport
        ctx_desk = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page_desk = await ctx_desk.new_page()
        await page_desk.goto('http://127.0.0.1:8000')
        await asyncio.sleep(0.5)
        await page_desk.screenshot(path='verification/screenshots/desktop_top.png')
        await ctx_desk.close()

        # Mobile top viewport
        ctx_mob = await browser.new_context(viewport={'width': 375, 'height': 667})
        page_mob = await ctx_mob.new_page()
        await page_mob.goto('http://127.0.0.1:8000')
        await asyncio.sleep(0.5)
        await page_mob.screenshot(path='verification/screenshots/mobile_top.png')
        await ctx_mob.close()

        await browser.close()

if __name__ == '__main__':
    asyncio.run(verify_viewports())
