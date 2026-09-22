import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger entrance animations
    for i in range(10):
        await page.mouse.wheel(0, 500)
        await asyncio.sleep(0.2)
    # Scroll back to top
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.5)

async def run_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch()

        # Desktop Testing
        context_desktop = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/index.html?simulation_mode=1")
        await page_desktop.wait_for_selector("#home")

        await trigger_scroll_animations(page_desktop)

        # Take light mode desktop screenshot
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Toggle Dark Theme Desktop
        await page_desktop.click("#theme-button")
        await asyncio.sleep(0.5)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Test Contact Form Submission Desktop
        await page_desktop.fill("#name", "Penguji Budi")
        await page_desktop.fill("#email", "budi@example.com")
        await page_desktop.fill("#subject", "Konsultasi Website")
        await page_desktop.fill("#message", "Halo Ar Creative, saya berminat membuat website studio.")
        await page_desktop.click("#contact-form button[type='submit']", force=True)

        await page_desktop.wait_for_selector("#contact-message:has-text('Mode Simulasi')", timeout=5000)
        print("Desktop contact form submission success message verified!")

        await context_desktop.close()

        # Mobile Testing
        context_mobile = await browser.new_context(viewport={'width': 375, 'height': 667})
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/index.html?simulation_mode=1")
        await page_mobile.wait_for_selector("#home")

        await trigger_scroll_animations(page_mobile)

        # Open Mobile Navigation Menu using force click if necessary
        await page_mobile.click("#nav-toggle", force=True)
        await page_mobile.wait_for_selector("#nav-menu.show-menu")
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu.png")

        # Close Mobile Navigation Menu
        await page_mobile.click("#nav-close")
        await asyncio.sleep(0.3)

        # Take light mode mobile screenshot
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

        await context_mobile.close()
        await browser.close()
        print("UI Verification Completed Successfully!")

if __name__ == "__main__":
    asyncio.run(run_verification())
