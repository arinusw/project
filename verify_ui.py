import asyncio
import os
import subprocess
import time
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal animations
    for i in range(10):
        await page.mouse.wheel(0, 500)
        await page.wait_for_timeout(200)
    # Scroll back to top
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await page.wait_for_timeout(300)

async def run_verification():
    print("Starting verification process...")

    # Ensure verification directories exist
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Test
        print("Testing Desktop View (1280x800)...")
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos"
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/")
        await page_desktop.wait_for_selector("#home")

        # Scroll to trigger animations
        await trigger_scroll_animations(page_desktop)

        # Capture Desktop Light Theme Screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)
        print("Captured desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await page_desktop.wait_for_timeout(500)

        # Capture Desktop Dark Theme Screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)
        print("Captured desktop_dark.png")

        await context_desktop.close()

        # 2. Mobile Test
        print("Testing Mobile View (375x667)...")
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            record_video_dir="verification/videos"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/")
        await page_mobile.wait_for_selector("#home")

        # Open Nav Menu
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu.png")
        print("Captured mobile_menu.png")

        # Close Menu by clicking a link
        about_link = page_mobile.locator("#nav-menu a[href='#about']")
        await about_link.click(force=True)
        await page_mobile.wait_for_timeout(500)

        # Navigate to Contact Form
        await page_mobile.goto("http://127.0.0.1:8000/#contact")
        await page_mobile.wait_for_timeout(500)

        # Fill Contact Form
        await page_mobile.fill("#name", "Budi Santoso")
        await page_mobile.fill("#email", "budi@example.com")
        await page_mobile.fill("#subject", "Konsultasi Website Studio")
        await page_mobile.fill("#message", "Halo Ar Creative, saya berminat untuk membuat website portofolio studio.")

        # Append hidden simulation input to test backend submission
        await page_mobile.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        # Submit Form
        await page_mobile.click("button[type='submit']")
        await page_mobile.wait_for_selector(".contact__message.success", timeout=5000)

        # Capture Mobile View / Contact Submission Success
        await page_mobile.screenshot(path="verification/screenshots/mobile_view.png")
        print("Captured mobile_view.png")

        await context_mobile.close()
        await browser.close()
        print("Verification completed successfully!")

if __name__ == "__main__":
    asyncio.run(run_verification())
