import asyncio
from playwright.async_api import async_playwright

async def run_complete_journey():
    print("Starting Complete User Journey Video recording and capture...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # Create a browser context with desktop viewport and record video
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="/home/jules/verification/videos"
        )
        page = await context.new_page()

        # 1. Navigate to the local PHP server
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(1000)

        # 2. Toggle Dark/Light Theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(1000)
        await theme_btn.click()
        await page.wait_for_timeout(1000)

        # 3. Incrementally scroll to trigger all animations
        for i in range(1, 10):
            await page.mouse.wheel(0, 600)
            await page.wait_for_timeout(500)

        await page.wait_for_timeout(1000)

        # 4. Fill in the contact form
        await page.locator("#user_name").fill("Ari Kreatif")
        await page.wait_for_timeout(500)
        await page.locator("#user_email").fill("ari@arkreatif.com")
        await page.wait_for_timeout(500)
        await page.locator("#user_project").fill("Saya ingin membuat landing page digital studio custom.")
        await page.wait_for_timeout(500)

        # 5. Submit form
        await page.locator('#contact-form button[type="submit"]').click()

        # Wait for success message
        success_msg = page.locator("#contact-message")
        await success_msg.wait_for(state="visible")
        await page.wait_for_timeout(1500)

        # Take final screenshot
        await page.screenshot(path="/home/jules/verification/screenshots/cuj_complete.png", full_page=True)
        print("Final CUJ Screenshot saved.")

        await context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_complete_journey())
