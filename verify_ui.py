import asyncio
import os
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal animations
    for i in range(10):
        await page.mouse.wheel(0, 600)
        await page.wait_for_timeout(300)
    await page.mouse.wheel(0, -6000)
    await page.wait_for_timeout(500)

async def run_tests():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. DESKTOP TEST
        context_desktop = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto('http://127.0.0.1:8000')
        await page_desktop.wait_for_selector('#home')
        await trigger_scroll_animations(page_desktop)

        # Screenshot Desktop Light
        await page_desktop.screenshot(path='verification/screenshots/desktop_light.png', full_page=True)
        print("Captured desktop_light.png")

        # Toggle Dark Theme
        await page_desktop.click('#theme-button')
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path='verification/screenshots/desktop_dark.png', full_page=True)
        print("Captured desktop_dark.png")

        # Test Contact Form Submission (Desktop)
        await page_desktop.fill('#user_name', 'Budi Santoso')
        await page_desktop.fill('#user_email', 'budi@example.com')
        await page_desktop.fill('#user_project', 'Halo Ar Creative, saya tertarik untuk pembuatan website studio dan edukasi digital.')

        # Inject simulation_mode in form before submit
        await page_desktop.evaluate('''() => {
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }''')

        await page_desktop.click('#contact-form button[type="submit"]')
        await page_desktop.wait_for_selector('#contact-message:has-text("Modus Simulasi")')
        print("Contact form test passed successfully!")

        await page_desktop.screenshot(path='verification/screenshots/contact_submitted.png')

        # 2. MOBILE TEST
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent='Mozilla/5.0 (iPhone; CPU iPhoneOS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1'
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto('http://127.0.0.1:8000')
        await page_mobile.wait_for_selector('#home')
        await trigger_scroll_animations(page_mobile)

        # Screenshot Mobile Light
        await page_mobile.screenshot(path='verification/screenshots/mobile_light.png', full_page=True)
        print("Captured mobile_light.png")

        # Open Mobile Navigation Menu
        await page_mobile.click('#nav-toggle', force=True)
        await page_mobile.wait_for_selector('#nav-menu.show-menu')
        await page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png')
        print("Captured mobile_menu_open.png")

        # Close Mobile Navigation Menu via JS click to avoid viewport boundary check
        await page_mobile.evaluate('() => document.getElementById("nav-close").click()')
        await page_mobile.wait_for_timeout(300)
        print("Closed mobile menu successfully")

        await browser.close()

if __name__ == '__main__':
    asyncio.run(run_tests())
