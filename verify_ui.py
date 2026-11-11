import asyncio
import os
from playwright.async_api import async_playwright

async def run():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch()

        # 1. Desktop Test
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = await context_desktop.new_page()
        await page.goto('http://127.0.0.1:8000/index.html')
        await page.wait_for_load_state('networkidle')

        # Screenshot Desktop Hero (Light Mode)
        await page.screenshot(path='verification/screenshots/desktop_light.png')
        print("Captured desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page.locator('#theme-button')
        await theme_btn.click()
        await page.wait_for_timeout(400)
        await page.screenshot(path='verification/screenshots/desktop_dark.png')
        print("Captured desktop_dark.png")

        # Scroll down to services
        await page.locator('#nav-menu a[href="#services"]').click()
        await page.wait_for_timeout(600)
        await page.screenshot(path='verification/screenshots/desktop_services.png')
        print("Captured desktop_services.png")

        # Scroll to contact and fill form
        await page.locator('#nav-menu a[href="#contact"]').click()
        await page.wait_for_timeout(600)

        await page.locator('#contact-name').fill('Andi Pratama')
        await page.locator('#contact-email').fill('andi@example.com')
        await page.locator('#contact-subject').select_option('Jasa Kreatif Digital')
        await page.locator('#contact-message').fill('Halo Ar Creative, saya tertarik untuk mendesain identitas brand dan website portofolio.')

        # Submit form
        await page.locator('#contact-button').click()
        await page.locator('#contact-response-message.success').wait_for(state='visible', timeout=5000)
        await page.screenshot(path='verification/screenshots/desktop_contact_submitted.png')
        print("Captured desktop_contact_submitted.png")

        await context_desktop.close()

        # 2. Mobile Test (375x667)
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto('http://127.0.0.1:8000/index.html')
        await page_mobile.wait_for_load_state('networkidle')

        # Capture Mobile Hero
        await page_mobile.screenshot(path='verification/screenshots/mobile_hero.png')
        print("Captured mobile_hero.png")

        # Open mobile nav drawer
        await page_mobile.locator('#nav-toggle').click(force=True)
        await page_mobile.wait_for_timeout(400)
        await page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png')
        print("Captured mobile_menu_open.png")

        # Close mobile menu or navigate to services
        await page_mobile.locator('#nav-close').click(force=True)
        await page_mobile.wait_for_timeout(400)
        await page_mobile.goto('http://127.0.0.1:8000/index.html#services')
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path='verification/screenshots/mobile_services.png')
        print("Captured mobile_services.png")

        await context_mobile.close()
        await browser.close()
        print("Verification completed successfully!")

if __name__ == '__main__':
    asyncio.run(run())
