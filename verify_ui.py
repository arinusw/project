import asyncio
import os
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    """Helper function to scroll incrementally and reveal all sections."""
    total_height = await page.evaluate("document.body.scrollHeight")
    step = 400
    for y in range(0, total_height, step):
        await page.mouse.wheel(0, step)
        await asyncio.sleep(0.1)
    # Scroll back to top
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.5)

async def run_verification():
    os.makedirs("verification/screenshots", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification (1280x800)
        desktop_context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await desktop_context.new_page()
        await page.goto("http://127.0.0.1:8000/", wait_until="networkidle")

        # Trigger animations so all elements become visible
        await trigger_scroll_animations(page)

        # Capture Desktop Light Mode
        await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
        print("Captured desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await asyncio.sleep(0.5)

        # Capture Desktop Dark Mode
        await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
        print("Captured desktop_dark.png")

        # Test Contact Form submission on Desktop
        await page.fill("#name", "Penguji Portfolio")
        await page.fill("#email", "tester@example.com")
        await page.fill("#message", "Halo Ar Creative, ini pesan uji coba dari Playwright script.")

        # Submit form
        submit_btn = page.locator("#contact-form button[type='submit']")
        await submit_btn.click()

        # Wait for success message
        contact_msg = page.locator("#contact-message")
        await contact_msg.wait_for(state="visible")
        await asyncio.sleep(0.5)

        await page.screenshot(path="verification/screenshots/desktop_contact_submitted.png")
        print("Captured desktop_contact_submitted.png")

        await desktop_context.close()

        # 2. Mobile Verification (375x667)
        mobile_context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15"
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto("http://127.0.0.1:8000/", wait_until="networkidle")

        await trigger_scroll_animations(mobile_page)

        # Capture Mobile Light Mode
        await mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)
        print("Captured mobile_light.png")

        # Open Mobile Nav Drawer
        nav_toggle = mobile_page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await asyncio.sleep(0.5)

        await mobile_page.screenshot(path="verification/screenshots/mobile_nav_open.png")
        print("Captured mobile_nav_open.png")

        # Toggle Dark Theme on Mobile
        mobile_theme_btn = mobile_page.locator("#theme-button")
        await mobile_theme_btn.click(force=True)
        await asyncio.sleep(0.5)

        await mobile_page.screenshot(path="verification/screenshots/mobile_dark.png", full_page=True)
        print("Captured mobile_dark.png")

        await mobile_context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_verification())
