import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    # Scroll down step-by-step to reveal elements with ScrollReveal
    for _ in range(8):
        await page.mouse.wheel(0, 500)
        await asyncio.sleep(0.3)
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.3)

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        context_desktop = await browser.new_context(viewport={"width": 1280, "height": 800})
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/", wait_until="networkidle")
        await trigger_scroll_animations(page_desktop)

        # Screenshot Desktop Light Theme
        os.makedirs("verification/screenshots", exist_ok=True)
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await asyncio.sleep(0.5)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # Form Submission in simulation mode
        await page_desktop.locator("#user_name").fill("Budi Santoso")
        await page_desktop.locator("#user_email").fill("budi@example.com")
        await page_desktop.locator("#user_project").fill("Saya berminat untuk membuat website portofolio digital studio.")

        # Add hidden field simulation_mode = 1 for test form submission
        await page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")

        await page_desktop.locator("#contact-form button[type='submit']").click()
        await page_desktop.locator("#contact-message").wait_for(state="visible")
        msg_text = await page_desktop.locator("#contact-message").inner_text()
        print("Contact Form Response:", msg_text)
        await page_desktop.screenshot(path="verification/screenshots/desktop_form_success.png")

        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(viewport={"width": 375, "height": 667})
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/", wait_until="networkidle")
        await trigger_scroll_animations(page_mobile)

        # Mobile light screenshot
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        # Open mobile menu
        await page_mobile.locator("#nav-toggle").click(force=True)
        await asyncio.sleep(0.5)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close mobile menu
        await page_mobile.locator("#nav-close").click()
        await asyncio.sleep(0.5)

        await context_mobile.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(main())
