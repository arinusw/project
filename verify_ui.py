import asyncio
import os
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down step-by-step to trigger ScrollReveal
    for i in range(1, 10):
        await page.mouse.wheel(0, 400)
        await asyncio.sleep(0.15)
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await asyncio.sleep(0.3)

async def run_ui_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        # Desktop Verification
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        print("Navigating to local site...")
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_selector("#home")

        await trigger_scroll_animations(page)

        # Light Theme Screenshot
        await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Dark Theme Screenshot
        await page.click("#theme-button")
        await asyncio.sleep(0.3)
        await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Contact Form Test (Simulation)
        await page.evaluate("window.scrollTo({top: document.body.scrollHeight, behavior: 'instant'})")
        await asyncio.sleep(0.3)

        await page.fill("#name", "Budi Santoso")
        await page.fill("#email", "budi@example.com")
        await page.fill("#subject", "Konsultasi Website Studio")
        await page.fill("#message", "Halo Ar Creative, saya tertarik untuk menggunakan jasa pembuatan website.")

        await page.click("#contact-form button[type='submit']")
        await page.locator("#contact-message").wait_for(state="visible")
        await asyncio.sleep(0.5)

        await page.screenshot(path="verification/screenshots/desktop_contact_response.png", full_page=False)

        await context.close()
        await browser.close()

        # Mobile Verification
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent='Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15',
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_selector("#home")

        # Open mobile drawer
        await page.click("#nav-toggle", force=True)
        await asyncio.sleep(0.3)
        await page.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

        # Close mobile drawer
        await page.click("#nav-close", force=True)
        await asyncio.sleep(0.3)

        await trigger_scroll_animations(page)
        await page.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

        await context.close()
        await browser.close()

        print("UI Verification completed successfully!")

if __name__ == '__main__':
    asyncio.run(run_ui_verification())
