import asyncio
from playwright.async_api import async_playwright

async def run_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # ---------------- DESKTOP VERIFICATION ----------------
        desktop_context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        desktop_page = await desktop_context.new_page()
        await desktop_page.goto("http://127.0.0.1:8000")
        await desktop_page.wait_for_selector("#home")

        # Scroll incrementally to trigger ScrollReveal animations
        for _ in range(8):
            await desktop_page.mouse.wheel(0, 500)
            await asyncio.sleep(0.3)

        await desktop_page.evaluate("window.scrollTo(0, 0)")
        await asyncio.sleep(0.5)

        # Take desktop light theme screenshot
        await desktop_page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Toggle dark theme
        await desktop_page.click("#theme-button")
        await asyncio.sleep(0.5)
        await desktop_page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # Contact form submission test
        await desktop_page.fill("#name", "Budi Santoso")
        await desktop_page.fill("#email", "budi@example.com")
        await desktop_page.fill("#subject", "Pengembangan Website Studio")
        await desktop_page.fill("#message", "Halo Ar Creative, saya tertarik untuk menggunakan jasa pembuatan website.")

        await desktop_page.click("#contact-form button[type='submit']")
        await desktop_page.wait_for_selector("#contact-message.success")
        await desktop_page.screenshot(path="verification/screenshots/contact_success.png")

        await desktop_context.close()

        # ---------------- MOBILE VERIFICATION ----------------
        mobile_context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
            record_video_dir="verification/videos/"
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto("http://127.0.0.1:8000")
        await mobile_page.wait_for_selector("#home")

        # Open mobile navigation menu
        await mobile_page.click("#nav-toggle", force=True)
        await asyncio.sleep(0.5)
        await mobile_page.screenshot(path="verification/screenshots/mobile_menu.png")

        # Close mobile menu
        await mobile_page.click("#nav-close")
        await asyncio.sleep(0.3)

        # Scroll incrementally to trigger ScrollReveal animations on mobile
        for _ in range(10):
            await mobile_page.mouse.wheel(0, 400)
            await asyncio.sleep(0.3)

        await mobile_page.evaluate("window.scrollTo(0, 0)")
        await asyncio.sleep(0.5)

        await mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        await mobile_context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_verification())
