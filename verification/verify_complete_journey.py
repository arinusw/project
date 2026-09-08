import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal animations
    for i in range(0, 3000, 400):
        await page.mouse.wheel(0, 400)
        await page.wait_for_timeout(200)
    await page.evaluate("window.scrollTo(0, 0);")
    await page.wait_for_timeout(300)

async def run_verification():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Test
        desktop_context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        desktop_page = await desktop_context.new_page()
        await desktop_page.goto("http://127.0.0.1:8000")
        await desktop_page.wait_for_load_state("networkidle")

        await trigger_scroll_animations(desktop_page)
        await desktop_page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Toggle Dark Mode
        await desktop_page.click("#theme-button")
        await desktop_page.wait_for_timeout(300)
        await desktop_page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # Form Submission in Simulation Mode
        await desktop_page.evaluate("document.getElementById('simulation-input').value = '1';")
        await desktop_page.fill("#name", "Budi Santoso")
        await desktop_page.fill("#email", "budi@example.com")
        await desktop_page.fill("#message", "Halo Ar Creative, saya tertarik untuk berkonsultasi mengenai pembuatan produk digital.")

        # Click submit button
        await desktop_page.click("#contact-form button[type='submit']")

        # Wait for success message locator to be visible
        success_msg = desktop_page.locator("#contact-message.success")
        await success_msg.wait_for(state="visible", timeout=5000)

        await desktop_page.screenshot(path="verification/screenshots/form_success.png")
        await desktop_context.close()

        # 2. Mobile Test
        mobile_context = await browser.new_context(
            viewport={"width": 375, "height": 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15",
            record_video_dir="verification/videos/"
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto("http://127.0.0.1:8000")
        await mobile_page.wait_for_load_state("networkidle")

        # Open mobile drawer
        await mobile_page.click("#nav-toggle", force=True)
        await mobile_page.wait_for_timeout(400)
        await mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Navigate to Services using section hash directly for reliability
        await mobile_page.goto("http://127.0.0.1:8000#services")
        await trigger_scroll_animations(mobile_page)
        await mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        await mobile_context.close()
        await browser.close()

    print("Verification completed successfully!")

if __name__ == "__main__":
    asyncio.run(run_verification())
