import asyncio
import time
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal entrance animations
    for i in range(10):
        await page.mouse.wheel(0, 500)
        await asyncio.sleep(0.2)
    # Scroll back to top instantly for top screenshots
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await asyncio.sleep(0.5)

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch()

        # --- Desktop Verification ---
        context_desktop = await browser.new_context(viewport={"width": 1280, "height": 800})
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/", wait_until="networkidle")

        await trigger_scroll_animations(page_desktop)

        # Light theme desktop screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Toggle dark theme
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await asyncio.sleep(0.3)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Contact form submission test
        await page_desktop.fill("#name", "Ahmad Tester")
        await page_desktop.fill("#email", "ahmad@example.com")
        await page_desktop.fill("#message", "Halo Ar Creative, ini adalah tes konsultasi proyek digital.")

        # Append simulation mode hidden field or submit directly
        await page_desktop.evaluate("""
            const form = document.getElementById('contact-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'simulation_mode';
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);
        """)

        await page_desktop.click("#contact-form button[type='submit']")
        await page_desktop.wait_for_selector("#contact-message:has-text('Terima kasih')")
        await page_desktop.locator("#contact").scroll_into_view_if_needed()
        await page_desktop.screenshot(path="verification/screenshots/contact_form_success.png")

        await context_desktop.close()

        # --- Mobile Verification ---
        context_mobile = await browser.new_context(viewport={"width": 375, "height": 667})
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/", wait_until="networkidle")

        await trigger_scroll_animations(page_mobile)

        # Initial mobile light screenshot
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

        # Open mobile navigation menu
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await asyncio.sleep(0.3)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")

        await context_mobile.close()

        await browser.close()
        print("UI Verification Completed Successfully!")

if __name__ == "__main__":
    asyncio.run(main())
