import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger entrance animations
    for y in range(0, 3000, 400):
        await page.evaluate(f"window.scrollTo(0, {y})")
        await asyncio.sleep(0.1)
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.3)

async def run_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Test (1280x800)
        print("Running Desktop Verification...")
        desktop_context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        desktop_page = await desktop_context.new_page()
        await desktop_page.goto('http://127.0.0.1:8000/')

        await trigger_scroll_animations(desktop_page)

        # Capture Desktop Light Mode Full Page Screenshot
        await desktop_page.screenshot(path='verification/screenshots/desktop_light.png', full_page=True)
        print("Saved verification/screenshots/desktop_light.png")

        # Toggle Dark Mode
        theme_btn = desktop_page.locator('#theme-button')
        await theme_btn.click()
        await asyncio.sleep(0.3)
        await desktop_page.screenshot(path='verification/screenshots/desktop_dark.png', full_page=True)
        print("Saved verification/screenshots/desktop_dark.png")

        # Test Contact Form Submission in Simulation Mode
        await desktop_page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await asyncio.sleep(0.3)

        # Append simulation_mode hidden input or send form data
        await desktop_page.fill('#user_name', 'Budi Santoso')
        await desktop_page.fill('#user_email', 'budi@example.com')
        await desktop_page.fill('#user_project', 'Ingin membuat website profil perusahaan dan sistem pendaftaran online.')

        # Submit form
        await desktop_page.click('#contact-form button[type="submit"]')
        await asyncio.sleep(1)

        await desktop_page.screenshot(path='verification/screenshots/contact_submitted.png')
        print("Saved verification/screenshots/contact_submitted.png")

        await desktop_context.close()

        # 2. Mobile Test (375x667)
        print("Running Mobile Verification...")
        mobile_context = await browser.new_context(viewport={'width': 375, 'height': 667})
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto('http://127.0.0.1:8000/')

        await trigger_scroll_animations(mobile_page)

        # Capture Mobile Light Home
        await mobile_page.screenshot(path='verification/screenshots/mobile_light.png', full_page=True)
        print("Saved verification/screenshots/mobile_light.png")

        # Open Mobile Navigation Menu Drawer
        nav_toggle = mobile_page.locator('#nav-toggle')
        await nav_toggle.click(force=True)
        await asyncio.sleep(0.5)

        await mobile_page.screenshot(path='verification/screenshots/mobile_menu_open.png')
        print("Saved verification/screenshots/mobile_menu_open.png")

        await mobile_context.close()
        await browser.close()
        print("Verification completed successfully!")

if __name__ == '__main__':
    asyncio.run(run_verification())
