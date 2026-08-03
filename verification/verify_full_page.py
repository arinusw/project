import asyncio
from playwright.async_api import async_playwright

async def verify_and_capture_full_page():
    print("Starting full page scroll capture...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={"width": 1280, "height": 800})
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(1000)

        # Scroll incrementally to trigger ScrollReveal animations
        # As per guidance: perform incremental scrolling to trigger entrance animations before final capture
        for i in range(1, 10):
            await page.mouse.wheel(0, 800)
            await page.wait_for_timeout(400)

        await page.wait_for_timeout(1000)
        await page.screenshot(path="verification/screenshots/desktop_full_revealed.png", full_page=True)
        print("Full revealed page saved.")
        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify_and_capture_full_page())
