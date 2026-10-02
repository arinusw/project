import asyncio
import os
import time
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Perform smooth incremental scrolling to trigger ScrollReveal animations
    for offset in range(0, 3500, 400):
        await page.mouse.wheel(0, 400)
        await page.wait_for_timeout(100)
    # Scroll back to top instantly for top-of-page verification screenshots
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await page.wait_for_timeout(200)

async def main():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000")
        await page_desktop.wait_for_load_state("networkidle")

        await trigger_scroll_animations(page_desktop)

        # Capture Desktop Light Mode Viewport
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Toggle Dark Mode
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await page_desktop.wait_for_timeout(300)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Contact Form Submission Simulation
        await page_desktop.evaluate("window.location.hash = '#contact'")
        await page_desktop.wait_for_timeout(300)

        await page_desktop.fill("#name", "Budi Santoso")
        await page_desktop.fill("#email", "budi@example.com")
        await page_desktop.fill("#message", "Halo Ar Creative, saya tertarik menggunakan jasa pembuatan website untuk bisnis saya.")

        # Inject simulation mode hidden input into form
        await page_desktop.evaluate("""
            const form = document.getElementById('contact-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'simulation_mode';
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);
        """)

        await page_desktop.click("#contact-form button[type='submit']")
        await page_desktop.locator("#contact-message").wait_for(state="visible")
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path="verification/screenshots/desktop_contact_success.png", full_page=False)

        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
            record_video_dir="verification/videos/"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000")
        await page_mobile.wait_for_load_state("networkidle")

        await trigger_scroll_animations(page_mobile)
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

        # Open mobile drawer menu
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(300)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

        await context_mobile.close()
        await browser.close()
        print("✅ UI Verification successfully executed!")

if __name__ == "__main__":
    asyncio.run(main())
