import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    # Scroll down gradually to trigger ScrollReveal entrance animations
    for i in range(1, 10):
        await page.mouse.wheel(0, 500)
        await page.wait_for_timeout(200)
    # Scroll back to top instantly so initial top screenshots are clean
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await page.wait_for_timeout(300)

async def run_tests():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Test (1280x800)
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/", wait_until="networkidle")

        # Trigger scroll reveal animations
        await trigger_scroll_animations(page_desktop)

        # Desktop Light Theme Screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await page_desktop.wait_for_timeout(500)

        # Desktop Dark Theme Screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Contact Form Test
        await page_desktop.locator("#contact").scroll_into_view_if_needed()
        await page_desktop.wait_for_timeout(300)
        await page_desktop.fill("#name", "Budi Santoso")
        await page_desktop.fill("#email", "budi@example.com")
        await page_desktop.fill("#message", "Halo Ar Creative, saya tertarik untuk menggunakan jasa produk digital studio Anda.")

        # Append hidden simulation_mode input for test safety
        await page_desktop.evaluate("""
            const form = document.getElementById('contact-form');
            const sim = document.createElement('input');
            sim.type = 'hidden';
            sim.name = 'simulation_mode';
            sim.value = '1';
            form.appendChild(sim);
        """)

        await page_desktop.click("#contact-form button[type='submit']")
        msg_locator = page_desktop.locator("#contact-message")
        await msg_locator.wait_for(state="visible")
        await page_desktop.screenshot(path="verification/screenshots/contact_form_success.png", full_page=False)

        await context_desktop.close()

        # 2. Mobile Test (375x667)
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
            has_touch=True,
            record_video_dir="verification/videos/"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/", wait_until="networkidle")

        await trigger_scroll_animations(page_mobile)

        # Mobile Light Theme Screenshot
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

        # Test Mobile Menu Drawer
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

        # Close Mobile Menu Drawer
        nav_close = page_mobile.locator("#nav-close")
        await nav_close.click(force=True)
        await page_mobile.wait_for_timeout(300)

        # Toggle Mobile Dark Theme
        mobile_theme_btn = page_mobile.locator("#theme-button")
        await mobile_theme_btn.click()
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_dark.png", full_page=False)

        await context_mobile.close()
        await browser.close()

    print("UI Verification tests completed successfully!")

if __name__ == "__main__":
    asyncio.run(run_tests())
