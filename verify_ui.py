import asyncio
import os
from playwright.async_api import async_playwright

async def run_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos'
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto('http://127.0.0.1:8000/')
        await page_desktop.wait_for_timeout(1000)

        # Scroll trigger ScrollReveal
        await page_desktop.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await page_desktop.wait_for_timeout(1000)
        await page_desktop.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page_desktop.wait_for_timeout(500)

        # Light mode desktop screenshot
        await page_desktop.screenshot(path='verification/screenshots/desktop_light.png', full_page=False)

        # Switch theme to dark
        theme_btn = page_desktop.locator('#theme-button')
        await theme_btn.click()
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path='verification/screenshots/desktop_dark.png', full_page=False)

        # Contact form test
        await page_desktop.fill('#contact-name', 'Budi Santoso')
        await page_desktop.fill('#contact-email', 'budi@example.com')
        await page_desktop.fill('#contact-subject', 'Konsultasi Digital Studio')
        await page_desktop.fill('#contact-message', 'Halo Ar Creative, saya ingin berkonsultasi mengenai pembuatan website & edukasi teknologi.')

        submit_btn = page_desktop.locator('#contact-form button[type="submit"]')
        await submit_btn.click()

        response_msg = page_desktop.locator('#contact-message-response')
        await response_msg.wait_for(state="visible")
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path='verification/screenshots/contact_success.png', full_page=False)

        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto('http://127.0.0.1:8000/')
        await page_mobile.wait_for_timeout(1000)

        await page_mobile.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await page_mobile.wait_for_timeout(1000)
        await page_mobile.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page_mobile.wait_for_timeout(500)

        await page_mobile.screenshot(path='verification/screenshots/mobile_light.png', full_page=False)

        # Open mobile menu
        nav_toggle = page_mobile.locator('#nav-toggle')
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path='verification/screenshots/mobile_menu.png', full_page=False)

        await context_mobile.close()
        await browser.close()
        print("Verification completed successfully!")

if __name__ == '__main__':
    asyncio.run(run_verification())
