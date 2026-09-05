import asyncio
from playwright.async_api import async_playwright

async def run_verification():
    async with async_playwright() as p:
        # Launch Chromium browser
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification (Light & Dark Theme, Scroll, Form Submission)
        context_desktop = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = await context_desktop.new_page()
        await page.goto("http://127.0.0.1:8000/index.html")
        await page.wait_for_load_state("networkidle")

        # Incremental scrolling to trigger ScrollReveal animations
        for y in range(0, 3000, 400):
            await page.mouse.wheel(0, 400)
            await page.wait_for_timeout(200)

        await page.evaluate("window.scrollTo(0, 0)")
        await page.wait_for_timeout(300)

        # Capture Desktop Light Screenshot
        await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
        print("Desktop light screenshot captured.")

        # Test Theme Toggle to Dark
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
        print("Desktop dark screenshot captured.")

        # Switch back to Light
        await theme_btn.click()
        await page.wait_for_timeout(300)

        # Contact Form Submission Simulation
        await page.locator("#contact").scroll_into_view_if_needed()
        await page.wait_for_timeout(300)

        await page.fill("#name", "Budi Santoso")
        await page.fill("#email", "budi@example.com")
        await page.fill("#message", "Halo Ar Creative, saya tertarik untuk mendiskusikan pembuatan website studio dan materi edukasi digital.")

        await page.click("#contact-btn")
        await page.wait_for_timeout(1000)

        msg_text = await page.locator("#contact-message").inner_text()
        print("Contact form response message:", msg_text)

        await context_desktop.close()

        # 2. Mobile Viewport Verification (Drawer Navigation)
        context_mobile = await browser.new_context(
            viewport={"width": 375, "height": 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/index.html")
        await page_mobile.wait_for_load_state("networkidle")

        # Open mobile drawer navigation
        await page_mobile.click("#nav-toggle", force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path="verification/screenshots/mobile_drawer.png")
        print("Mobile drawer screenshot captured.")

        # Close mobile drawer
        await page_mobile.click("#nav-close")
        await page_mobile.wait_for_timeout(300)
        await page_mobile.screenshot(path="verification/screenshots/mobile_home.png")
        print("Mobile home screenshot captured.")

        await context_mobile.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_verification())
