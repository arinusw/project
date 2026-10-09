import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    for i in range(10):
        await page.mouse.wheel(0, 800)
        await page.wait_for_timeout(200)

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch()

        # Desktop Light
        page_desktop = await browser.new_page(viewport={"width": 1280, "height": 800})
        await page_desktop.goto("http://127.0.0.1:8000/")
        await page_desktop.wait_for_load_state("networkidle")
        await trigger_scroll_animations(page_desktop)
        await page_desktop.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page_desktop.wait_for_timeout(300)
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Desktop Dark
        await page_desktop.click("#theme-button")
        await page_desktop.wait_for_timeout(300)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Contact Form Simulation test
        await page_desktop.evaluate("document.getElementById('contact').scrollIntoView()")
        await page_desktop.wait_for_timeout(300)
        await page_desktop.fill("#name", "Budi Santoso")
        await page_desktop.fill("#email", "budi@example.com")
        await page_desktop.fill("#message", "Halo Ar Creative, saya tertarik dengan jasa pembuatan website.")

        # Inject simulation mode flag into form dynamically for test
        await page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")
        await page_desktop.click("#contact-form button[type='submit']")
        await page_desktop.wait_for_selector("#contact-message:has-text('Mode Simulasi')", timeout=5000)
        await page_desktop.screenshot(path="verification/screenshots/contact_form_success.png", full_page=False)

        await page_desktop.close()

        # Mobile Light
        page_mobile = await browser.new_page(viewport={"width": 375, "height": 667})
        await page_mobile.goto("http://127.0.0.1:8000/")
        await page_mobile.wait_for_load_state("networkidle")
        await trigger_scroll_animations(page_mobile)
        await page_mobile.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page_mobile.wait_for_timeout(300)
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

        # Test Mobile Menu
        await page_mobile.click("#nav-toggle")
        await page_mobile.wait_for_timeout(300)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu.png", full_page=False)

        await page_mobile.close()
        await browser.close()
        print("UI Verification completed successfully!")

if __name__ == "__main__":
    asyncio.run(main())
