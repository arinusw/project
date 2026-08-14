import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    """Scroll down gradually to trigger ScrollReveal animations."""
    for i in range(1, 6):
        await page.mouse.wheel(0, 800)
        await page.wait_for_timeout(300)
    await page.evaluate("window.scrollTo(0, 0)")
    await page.wait_for_timeout(300)

async def run_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification (Light & Dark Theme, Scroll, Form Simulation)
        context_desktop = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/")
        await page_desktop.wait_for_selector("#home")

        # Trigger animations and take Desktop Light screenshot
        await trigger_scroll_animations(page_desktop)
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Toggle Dark Mode
        await page_desktop.click("#theme-button")
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # Submit Contact Form (Simulation Mode)
        await page_desktop.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await page_desktop.wait_for_timeout(300)
        await page_desktop.fill("#user_name", "Budi Santoso")
        await page_desktop.fill("#user_email", "budi@example.com")
        await page_desktop.fill("#user_project", "Halo Ar Creative, kami ingin konsultasi pembuatan website portal edukasi.")

        # Inject simulation_mode into form
        await page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")

        await page_desktop.click("#contact-form button[type='submit']")
        await page_desktop.locator("#contact-message").wait_for(state="visible")
        await page_desktop.wait_for_timeout(1000)
        await page_desktop.screenshot(path="verification/screenshots/contact_form_submitted.png")

        await context_desktop.close()

        # 2. Mobile Verification (375x667 Viewport)
        context_mobile = await browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            record_video_dir="verification/videos"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/")
        await page_mobile.wait_for_selector("#home")

        # Open Mobile Navigation Menu
        await page_mobile.click("#nav-toggle", force=True)
        await page_mobile.locator("#nav-menu").wait_for(state="visible")
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close Mobile Menu and take Mobile Full Page Screenshot
        await page_mobile.click("#nav-close", force=True)
        await page_mobile.wait_for_timeout(500)
        await trigger_scroll_animations(page_mobile)
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        await context_mobile.close()
        await browser.close()
        print("Verification script completed successfully!")

if __name__ == "__main__":
    asyncio.run(run_verification())
