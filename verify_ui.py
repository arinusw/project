import asyncio
from playwright.async_api import async_playwright
import os

async def verify_portfolio():
    async with async_playwright() as p:
        # Launch browser
        browser = await p.chromium.launch()

        # 1. Desktop Verification
        page = await browser.new_page(viewport={'width': 1280, 'height': 800})
        await page.goto('http://127.0.0.1:8000')

        # Trigger ScrollReveal animations by scrolling incrementally
        for i in range(10):
            await page.mouse.wheel(0, 800)
            await asyncio.sleep(0.5)

        await asyncio.sleep(2)
        await page.screenshot(path='verification/screenshots/desktop_home.png', full_page=True)
        print("Captured desktop home screenshot")

        # Verify Navigation
        await page.click('a[href="#services"]')
        await asyncio.sleep(1)
        # Check if services section is in view or active link is updated
        is_active = await page.eval_on_selector('a[href="#services"]', 'el => el.classList.contains("active-link")')
        print(f"Services link active after click: {is_active}")

        # 2. Mobile Verification
        mobile_page = await browser.new_page(viewport={'width': 375, 'height': 667}, is_mobile=True)
        await mobile_page.goto('http://127.0.0.1:8000')

        # Test Mobile Menu
        await mobile_page.click('#nav-toggle')
        await asyncio.sleep(1)
        menu_visible = await mobile_page.is_visible('#nav-menu.show-menu')
        print(f"Mobile menu visible after toggle: {menu_visible}")
        await mobile_page.screenshot(path='verification/screenshots/mobile_menu.png')

        await mobile_page.click('#nav-close')
        await asyncio.sleep(1)
        menu_visible = await mobile_page.is_visible('#nav-menu.show-menu')
        print(f"Mobile menu hidden after close: {not menu_visible}")

        # 3. Contact Form Submission (Simulation)
        await page.fill('#user_name', 'Test User')
        await page.fill('#user_email', 'test@example.com')
        await page.fill('#user_project', 'This is a test message from Playwright.')

        # We need to add simulation_mode=1 to the form or intercept the request
        # For simplicity, let's just submit and expect success message as our contact.php returns success anyway
        await page.click('#contact-form button[type="submit"]')
        await asyncio.sleep(2)

        success_message = await page.inner_text('#contact-message')
        print(f"Contact form message: {success_message}")
        await page.screenshot(path='verification/screenshots/contact_success.png')

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify_portfolio())
