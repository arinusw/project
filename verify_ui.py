import asyncio
import os
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal animations
    for i in range(1, 6):
        await page.mouse.wheel(0, 800)
        await page.wait_for_timeout(300)
    # Scroll back to top and reset position instantly
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await page.wait_for_timeout(500)

async def run_ui_verification():
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

        # Trigger animations
        await trigger_scroll_animations(page_desktop)

        # Light theme top screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Submit Contact Form
        await page_desktop.locator("#name").fill("Budi Santoso")
        await page_desktop.locator("#email").fill("budi@example.com")
        await page_desktop.locator("#message").fill("Halo Ar Creative, saya tertarik dengan jasa pembuatan website.")

        # Inject simulation mode input
        await page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const sim = document.createElement('input');
            sim.type = 'hidden';
            sim.name = 'simulation_mode';
            sim.value = '1';
            form.appendChild(sim);
        }""")

        await page_desktop.locator("button.contact__submit").click()
        await page_desktop.locator("#contact-message").wait_for(state="visible")
        await page_desktop.wait_for_timeout(1000)
        await page_desktop.screenshot(path="verification/screenshots/desktop_contact_submitted.png", full_page=False)

        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000")
        await page_mobile.wait_for_load_state("networkidle")

        # Mobile menu toggle
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

        # Close mobile menu
        nav_close = page_mobile.locator("#nav-close")
        await nav_close.click(force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

        await context_mobile.close()
        await browser.close()
        print("UI Verification Completed Successfully!")

if __name__ == '__main__':
    asyncio.run(run_ui_verification())
