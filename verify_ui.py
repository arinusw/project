import asyncio
import os
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll in increments to trigger ScrollReveal entrance animations
    for _ in range(5):
        await page.mouse.wheel(0, 800)
        await asyncio.sleep(0.3)
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.3)

async def run_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        # Launch browser
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto('http://127.0.0.1:8000')
        await trigger_scroll_animations(page_desktop)

        # Full page desktop screenshot (light theme)
        await page_desktop.screenshot(path='verification/screenshots/desktop_light.png', full_page=True)

        # Toggle Dark Theme
        theme_btn = page_desktop.locator('#theme-button')
        await theme_btn.click()
        await asyncio.sleep(0.3)
        await page_desktop.screenshot(path='verification/screenshots/desktop_dark.png', full_page=True)

        # Contact Form Test (Desktop)
        contact_section = page_desktop.locator('#contact')
        await contact_section.scroll_into_view_if_needed()
        await page_desktop.fill('#name', 'Budi Santoso')
        await page_desktop.fill('#email', 'budi@example.com')
        await page_desktop.fill('#subject', 'Diskusi Proyek Web')
        await page_desktop.fill('#message', 'Halo Ar Creative, kami berminat untuk menggunakan jasa pemuatan web.')

        # Enable simulation mode on window
        await page_desktop.evaluate("window.simulation_mode = '1';")
        await page_desktop.click('#contact-form button[type="submit"]')

        success_msg = page_desktop.locator('#contact-message')
        await success_msg.wait_for(state='visible')
        print("Contact Form Message:", await success_msg.text_content())

        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            record_video_dir='verification/videos/'
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto('http://127.0.0.1:8000')
        await trigger_scroll_animations(page_mobile)

        await page_mobile.screenshot(path='verification/screenshots/mobile_light.png', full_page=True)

        # Open mobile navigation menu
        nav_toggle = page_mobile.locator('#nav-toggle')
        await nav_toggle.click(force=True)
        await asyncio.sleep(0.3)
        await page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png')

        # Close mobile menu
        nav_close = page_mobile.locator('#nav-close')
        await nav_close.click(force=True)
        await asyncio.sleep(0.3)

        await context_mobile.close()
        await browser.close()

if __name__ == '__main__':
    asyncio.run(run_verification())
