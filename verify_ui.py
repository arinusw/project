import asyncio
import time
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll through the page to trigger ScrollReveal animations
    scroll_height = await page.evaluate("document.body.scrollHeight")
    for i in range(0, scroll_height, 400):
        await page.mouse.wheel(0, 400)
        await asyncio.sleep(0.1)
    await asyncio.sleep(0.5)
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.3)

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        desktop_context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        desktop_page = await desktop_context.new_page()
        await desktop_page.goto("http://127.0.0.1:8000")
        await trigger_scroll_animations(desktop_page)

        # Capture Desktop Light Theme
        await desktop_page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Toggle Dark Theme on Desktop
        theme_btn = desktop_page.locator("#theme-button")
        await theme_btn.click()
        await asyncio.sleep(0.3)
        await desktop_page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # Contact Form Submission Simulation
        await desktop_page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await asyncio.sleep(0.3)
        await desktop_page.fill("#user_name", "Budi Santoso")
        await desktop_page.fill("#user_email", "budi@example.com")
        await desktop_page.fill("#user_project", "Halo Ar Creative, saya tertarik untuk membuat website studio portofolio.")

        # Add hidden field simulation_mode=1
        await desktop_page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        await desktop_page.click("#contact-form button[type='submit']")
        await asyncio.sleep(1.0)
        await desktop_page.screenshot(path="verification/screenshots/desktop_contact_success.png")

        await desktop_context.close()

        # 2. Mobile Verification
        mobile_context = await browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            record_video_dir="verification/videos/"
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto("http://127.0.0.1:8000")
        await trigger_scroll_animations(mobile_page)

        # Capture Mobile Light Theme
        await mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        # Open Mobile Menu
        nav_toggle = mobile_page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await asyncio.sleep(0.5)
        await mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close Mobile Menu via Nav link
        nav_menu = mobile_page.locator("#nav-menu")
        nav_about = nav_menu.locator("a[href='#about']")
        await nav_about.click()
        await asyncio.sleep(0.5)

        # Toggle Mobile Dark Theme
        mobile_theme_btn = mobile_page.locator("#theme-button")
        await mobile_theme_btn.click(force=True)
        await asyncio.sleep(0.3)
        await mobile_page.screenshot(path="verification/screenshots/mobile_dark.png", full_page=True)

        await mobile_context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(main())
