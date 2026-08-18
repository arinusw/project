import asyncio
from playwright.async_api import async_playwright

async def run_journey():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = await context.new_page()

        await page.goto('http://127.0.0.1:8000/')
        await asyncio.sleep(1)

        # Smooth scroll through sections
        for y in range(0, 2500, 300):
            await page.evaluate(f"window.scrollTo(0, {y})")
            await asyncio.sleep(0.3)

        # Toggle dark mode
        theme_btn = page.locator('#theme-button')
        await theme_btn.click()
        await asyncio.sleep(1)

        # Scroll back up
        await page.evaluate("window.scrollTo(0, 0)")
        await asyncio.sleep(1)

        await context.close()
        await browser.close()
        print("Recorded CUJ video successfully!")

if __name__ == '__main__':
    asyncio.run(run_journey())
