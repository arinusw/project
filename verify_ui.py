import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    # Scroll in steps to trigger ScrollReveal animations
    for i in range(10):
        await page.mouse.wheel(0, 400)
        await asyncio.sleep(0.2)
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.5)

async def run_desktop_verification(browser):
    context = await browser.new_context(viewport={"width": 1280, "height": 800})
    page = await context.new_page()
    await page.goto("http://127.0.0.1:8000")
    await asyncio.sleep(1)

    # Scroll through page to activate animations
    await trigger_scroll_animations(page)

    # Dark mode toggle test
    await page.click("#theme-button")
    await asyncio.sleep(0.5)
    await page.screenshot(path="verification/screenshots/desktop_dark_mode.png", full_page=True)

    # Toggle back to light mode
    await page.click("#theme-button")
    await asyncio.sleep(0.5)

    # Contact form simulation submit
    await page.fill("#user_name", "Ahmad Test")
    await page.fill("#user_email", "ahmad@example.com")
    await page.fill("#user_project", "Proyek pembuatan website studio digital")

    # Inject simulation mode flag and submit
    await page.evaluate("""() => {
        const form = document.getElementById('contact-form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'simulation_mode';
        input.value = '1';
        form.appendChild(input);
    }""")
    await page.click("#contact-form button[type='submit']")
    await asyncio.sleep(1)

    await page.screenshot(path="verification/screenshots/desktop_light_mode.png", full_page=True)
    await context.close()

async def run_mobile_verification(browser):
    context = await browser.new_context(viewport={"width": 375, "height": 667})
    page = await context.new_page()
    await page.goto("http://127.0.0.1:8000")
    await asyncio.sleep(1)

    # Open mobile menu
    await page.click("#nav-toggle", force=True)
    await asyncio.sleep(0.5)
    await page.screenshot(path="verification/screenshots/mobile_menu_open.png")

    # Close mobile menu
    await page.click("#nav-close", force=True)
    await asyncio.sleep(0.5)

    # Trigger scroll animations for mobile capture
    await trigger_scroll_animations(page)
    await page.screenshot(path="verification/screenshots/mobile_full_page.png", full_page=True)
    await context.close()

async def main():
    os.makedirs("verification/screenshots", exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        print("Running Desktop UI Verification...")
        await run_desktop_verification(browser)
        print("Running Mobile UI Verification...")
        await run_mobile_verification(browser)
        await browser.close()
    print("UI Verification complete! Screenshots saved in verification/screenshots/")

if __name__ == "__main__":
    asyncio.run(main())
