import asyncio
import os
from playwright.async_api import async_playwright

async def run_ui_tests():
    os.makedirs('verification/screenshots', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Visual Verification
        context_desktop = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto('http://127.0.0.1:8000')
        await page_desktop.wait_for_timeout(1000)

        # Trigger scroll animations
        for _ in range(5):
            await page_desktop.mouse.wheel(0, 800)
            await page_desktop.wait_for_timeout(300)
        await page_desktop.evaluate("window.scrollTo(0, 0)")
        await page_desktop.wait_for_timeout(500)

        await page_desktop.screenshot(path='verification/screenshots/desktop_light.png', full_page=True)

        # Toggle Dark Theme
        theme_btn = page_desktop.locator('#theme-button')
        await theme_btn.click()
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path='verification/screenshots/desktop_dark.png', full_page=True)

        # Form submission simulation
        await page_desktop.fill('#name', 'Budi Santoso')
        await page_desktop.fill('#email', 'budi@example.com')
        await page_desktop.fill('#subject', 'Konsultasi Proyek Web')
        await page_desktop.fill('#message', 'Halo Ar Creative, saya ingin berkonsultasi mengenai pembuatan website portofolio studio.')

        # Add hidden simulation mode field to form for testing
        await page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        await page_desktop.click('.contact__submit-btn')
        await page_desktop.wait_for_selector('#contact-message:has-text("Pesan Anda berhasil terkirim")')
        await page_desktop.screenshot(path='verification/screenshots/desktop_contact_success.png')

        await context_desktop.close()

        # 2. Mobile Visual Verification
        context_mobile = await browser.new_context(viewport={'width': 375, 'height': 667})
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto('http://127.0.0.1:8000')
        await page_mobile.wait_for_timeout(1000)

        # Open Mobile Menu
        nav_toggle = page_mobile.locator('#nav-toggle')
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png')

        # Close Mobile Menu
        nav_close = page_mobile.locator('#nav-close')
        await nav_close.click(force=True)
        await page_mobile.wait_for_timeout(500)

        # Trigger Scroll Animations on Mobile
        for _ in range(5):
            await page_mobile.mouse.wheel(0, 800)
            await page_mobile.wait_for_timeout(300)

        await page_mobile.screenshot(path='verification/screenshots/mobile_light.png', full_page=True)
        await context_mobile.close()

        await browser.close()
        print("UI Verification screenshots captured successfully!")

if __name__ == '__main__':
    asyncio.run(run_ui_tests())
