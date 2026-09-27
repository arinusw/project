import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal animations
    for i in range(12):
        await page.mouse.wheel(0, 600)
        await page.wait_for_timeout(150)
    # Instant scroll back to top
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await page.wait_for_timeout(500)

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # --- DESKTOP LIGHT THEME ---
        desktop_context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        desktop_page = await desktop_context.new_page()
        await desktop_page.goto("http://127.0.0.1:8000/")
        await desktop_page.wait_for_timeout(1000)
        await trigger_scroll_animations(desktop_page)

        # Desktop Light Top View
        await desktop_page.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Desktop Dark Top View
        theme_button = desktop_page.locator("#theme-button")
        await theme_button.click()
        await desktop_page.wait_for_timeout(500)
        await desktop_page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Switch back to light theme for section captures
        await theme_button.click()
        await desktop_page.wait_for_timeout(300)

        # --- MOBILE VIEWPORT ---
        mobile_context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1"
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto("http://127.0.0.1:8000/")
        await mobile_page.wait_for_timeout(1000)
        await trigger_scroll_animations(mobile_page)

        # Mobile Light
        await mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

        # Mobile Menu Open
        nav_toggle = mobile_page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await mobile_page.wait_for_timeout(500)
        await mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

        # --- CONTACT FORM VERIFICATION ---
        await desktop_page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await desktop_page.wait_for_timeout(500)

        await desktop_page.fill("#name", "Budi Santoso")
        await desktop_page.fill("#email", "budi@example.com")
        await desktop_page.fill("#subject", "Konsultasi Website Studio")
        await desktop_page.fill("#message", "Halo Ar Creative, saya berminat untuk bekerja sama dalam pembuatan platform digital.")

        submit_btn = desktop_page.locator(".contact__submit-btn")
        await submit_btn.click()

        await desktop_page.wait_for_selector("#contact-message:has-text('Terima kasih')", timeout=5000)
        await desktop_page.screenshot(path="verification/screenshots/contact_form_success.png", full_page=False)

        await browser.close()
        print("Updated verification screenshots successfully!")

if __name__ == "__main__":
    asyncio.run(main())
