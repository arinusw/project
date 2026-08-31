import asyncio
import os
from playwright.async_api import async_playwright

async def run_verification():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. DESKTOP VERIFICATION
        context_desktop = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000")
        await page_desktop.wait_for_timeout(1000)

        # Trigger ScrollReveal animations
        await page_desktop.mouse.wheel(0, 800)
        await page_desktop.wait_for_timeout(500)
        await page_desktop.mouse.wheel(0, 800)
        await page_desktop.wait_for_timeout(500)
        await page_desktop.mouse.wheel(0, 800)
        await page_desktop.wait_for_timeout(500)
        await page_desktop.evaluate("window.scrollTo(0, 0)")
        await page_desktop.wait_for_timeout(500)

        # Screenshot Desktop Light Mode
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Toggle Dark Mode
        await page_desktop.click("#theme-button")
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # Test Contact Form Submission in simulation mode
        await page_desktop.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await page_desktop.wait_for_timeout(500)
        await page_desktop.fill("#user_name", "Budi Santoso")
        await page_desktop.fill("#user_email", "budi@example.com")
        await page_desktop.fill("#user_project", "Ingin membuat website portofolio studio kreatif.")
        await page_desktop.click("#contact-submit-btn")
        await page_desktop.wait_for_selector("#contact-message.success", timeout=5000)
        await page_desktop.screenshot(path="verification/screenshots/desktop_contact_success.png")

        await context_desktop.close()

        # 2. MOBILE VERIFICATION
        context_mobile = await browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            record_video_dir="verification/videos"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000")
        await page_mobile.wait_for_timeout(1000)

        # Open mobile navigation menu
        await page_mobile.click("#nav-toggle")
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close mobile navigation menu
        await page_mobile.click("#nav-close")
        await page_mobile.wait_for_timeout(500)

        # Trigger ScrollReveal and capture Mobile Light
        await page_mobile.mouse.wheel(0, 1000)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.mouse.wheel(0, 1000)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.evaluate("window.scrollTo(0, 0)")
        await page_mobile.wait_for_timeout(500)

        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        await context_mobile.close()
        await browser.close()
        print("UI Verification successfully completed!")

if __name__ == "__main__":
    asyncio.run(run_verification())
