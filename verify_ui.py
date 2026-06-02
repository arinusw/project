import asyncio
from playwright.async_api import async_playwright
import os

async def verify_website():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800}
        )
        page = await context.new_page()

        # Navigate to the site
        await page.goto('http://localhost:8000')

        # Wait for ScrollReveal animations
        await asyncio.sleep(3)

        # Take desktop screenshot
        await page.screenshot(path='verification/screenshots/desktop_home.png', full_page=True)

        # Verify theme toggle
        await page.click('#theme-button')
        await asyncio.sleep(1)
        await page.screenshot(path='verification/screenshots/desktop_dark_mode.png')

        # Verify navigation scroll
        await page.click('a[href="#services"]')
        await asyncio.sleep(1)
        await page.screenshot(path='verification/screenshots/desktop_services.py.png')

        # Mobile verification
        mobile_context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto('http://localhost:8000')
        await asyncio.sleep(3)
        await mobile_page.screenshot(path='verification/screenshots/mobile_home.png')

        # Open mobile menu
        await mobile_page.click('#nav-toggle')
        await asyncio.sleep(1)
        await mobile_page.screenshot(path='verification/screenshots/mobile_menu.png')

        # Test Contact Form (Simulation Mode)
        await page.fill('input[name="user_name"]', 'Test User')
        await page.fill('input[name="user_email"]', 'test@example.com')
        await page.fill('input[name="user_subject"]', 'Test Subject')
        await page.fill('textarea[name="user_message"]', 'This is a test message.')
        await page.click('button[type="submit"]')

        await asyncio.sleep(2)
        await page.screenshot(path='verification/screenshots/contact_success.png')

        await browser.close()

if __name__ == '__main__':
    asyncio.run(verify_website())
