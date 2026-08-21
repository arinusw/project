import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal entrance transitions
    for i in range(10):
        await page.mouse.wheel(0, 800)
        await page.wait_for_timeout(200)
    # Scroll back to top
    await page.evaluate("window.scrollTo(0, 0)")
    await page.wait_for_timeout(300)

async def test_ui():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # ---------------- DESKTOP VERIFICATION ----------------
        context_desktop = await browser.new_context(viewport={"width": 1280, "height": 800})
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/")
        await trigger_scroll_animations(page_desktop)

        # 1. Desktop Light Theme Screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # 2. Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await page_desktop.wait_for_timeout(300)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # 3. Test Form Submission
        await page_desktop.fill("#user_name", "Budi Santoso")
        await page_desktop.fill("#user_email", "budi@example.com")
        await page_desktop.fill("#user_project", "Ingin membuat website portofolio studio kreatif.")

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
        await page_desktop.screenshot(path="verification/screenshots/desktop_form_success.png")

        await context_desktop.close()

        # ---------------- MOBILE VERIFICATION ----------------
        context_mobile = await browser.new_context(viewport={"width": 375, "height": 667})
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/")
        await trigger_scroll_animations(page_mobile)

        # 1. Mobile Home Screenshot
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png")

        # 2. Open Mobile Navigation Menu Drawer
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(400)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close Drawer
        nav_close = page_mobile.locator("#nav-close")
        await nav_close.click(force=True)
        await page_mobile.wait_for_timeout(300)

        await context_mobile.close()
        await browser.close()
        print("UI Verification Completed Successfully!")

if __name__ == "__main__":
    asyncio.run(test_ui())
