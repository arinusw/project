import asyncio
import os
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    for i in range(10):
        await page.mouse.wheel(0, 500)
        await page.wait_for_timeout(200)
    await page.evaluate("window.scrollTo(0, 0)")
    await page.wait_for_timeout(300)

async def run_desktop_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page(viewport={"width": 1280, "height": 800})

        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(1000)

        await trigger_scroll_animations(page)

        os.makedirs("verification/screenshots", exist_ok=True)
        await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
        print("Desktop light screenshot saved.")

        theme_button = page.locator("#theme-button")
        await theme_button.click()
        await page.wait_for_timeout(500)

        await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
        print("Desktop dark screenshot saved.")

        await browser.close()

async def run_mobile_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page(viewport={"width": 375, "height": 667})

        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(1000)

        await trigger_scroll_animations(page)

        nav_toggle = page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page.wait_for_timeout(500)

        await page.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Mobile menu open screenshot saved.")

        nav_close = page.locator("#nav-close")
        await nav_close.click(force=True)
        await page.wait_for_timeout(500)

        await page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)
        print("Mobile light screenshot saved.")

        await page.fill("#name", "Jules Tester")
        await page.fill("#email", "jules@example.com")
        await page.fill("#subject", "Konsultasi Layanan Web")
        await page.fill("#message", "Halo Ar Creative, saya berminat dengan jasa pembuatan website.")

        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")

        contact_button = page.locator("#contact-button")
        await contact_button.click()

        contact_message = page.locator("#contact-message")
        await contact_message.wait_for(state="visible", timeout=5000)

        await page.screenshot(path="verification/screenshots/contact_form_submitted.png")
        print("Contact form submitted screenshot saved.")

        await browser.close()

async def main():
    await run_desktop_verification()
    await run_mobile_verification()

if __name__ == "__main__":
    asyncio.run(main())
