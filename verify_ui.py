import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    # Scroll incrementally to trigger ScrollReveal entrance animations
    for i in range(10):
        await page.mouse.wheel(0, 500)
        await page.wait_for_timeout(300)
    await page.evaluate("window.scrollTo(0, 0)")
    await page.wait_for_timeout(500)

async def main():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # ---------------- DESKTOP VERIFICATION ----------------
        context_desktop = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/")
        await page_desktop.wait_for_selector(".home__title")

        # Scroll to trigger animations
        await trigger_scroll_animations(page_desktop)

        # Capture initial light mode desktop screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Toggle theme to dark mode
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # Toggle theme back to light mode
        await theme_btn.click()
        await page_desktop.wait_for_timeout(300)

        # Submit contact form in simulation mode
        await page_desktop.locator("#user_name").fill("Budi Santoso")
        await page_desktop.locator("#user_email").fill("budi@example.com")
        await page_desktop.locator("#user_project").fill("Halo Ar Creative, saya tertarik dengan layanan pembuatan website & produk digital Anda.")

        # Append hidden simulation_mode field to form dynamically for testing
        await page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        await page_desktop.locator('#contact-form button[type="submit"]').click()
        await page_desktop.wait_for_selector("#contact-message")
        await page_desktop.wait_for_timeout(1000)

        # Capture form submit screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_form_submitted.png")

        await context_desktop.close()

        # ---------------- MOBILE VERIFICATION ----------------
        context_mobile = await browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True,
            record_video_dir="verification/videos/"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/")
        await page_mobile.wait_for_selector(".home__title")

        # Scroll to trigger animations
        await trigger_scroll_animations(page_mobile)

        # Capture mobile home screenshot
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        # Test mobile menu toggle
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close mobile menu
        nav_close = page_mobile.locator("#nav-close")
        await nav_close.click(force=True)
        await page_mobile.wait_for_timeout(300)

        await context_mobile.close()
        await browser.close()
        print("UI Verification Completed Successfully.")

if __name__ == "__main__":
    asyncio.run(main())
