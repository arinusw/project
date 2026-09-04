import asyncio
from playwright.async_api import async_playwright
import os

async def run_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch()

        # 1. Desktop Verification
        desktop_page = await browser.new_page(viewport={'width': 1280, 'height': 800})
        await desktop_page.goto('http://127.0.0.1:8000')
        await desktop_page.wait_for_load_state('networkidle')

        # Incremental scroll to trigger animations
        for _ in range(5):
            await desktop_page.mouse.wheel(0, 800)
            await asyncio.sleep(0.3)

        # Scroll back top
        await desktop_page.evaluate('window.scrollTo(0, 0)')
        await asyncio.sleep(0.5)

        # Capture Desktop Light
        await desktop_page.screenshot(path='verification/screenshots/desktop_light.png', full_page=True)
        print("Captured desktop_light.png")

        # Toggle Dark Theme
        theme_btn = desktop_page.locator('#theme-button')
        await theme_btn.click()
        await asyncio.sleep(0.5)
        await desktop_page.screenshot(path='verification/screenshots/desktop_dark.png', full_page=True)
        print("Captured desktop_dark.png")

        # Test Form Submission in Desktop
        await desktop_page.fill('#name', 'Budi Santoso')
        await desktop_page.fill('#email', 'budi@example.com')
        await desktop_page.fill('#subject', 'Permintaan Penawaran Website')
        await desktop_page.fill('#message', 'Halo Ar Creative, saya ingin berkonsultasi mengenai pembuatan website portofolio.')

        # Inject simulation mode input into form for test
        await desktop_page.evaluate('''() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }''')

        await desktop_page.click('.contact__form-button')
        await desktop_page.wait_for_selector('#contact-message.success', timeout=5000)
        msg_text = await desktop_page.inner_text('#contact-message')
        print(f"Desktop Form Response: {msg_text}")
        await desktop_page.screenshot(path='verification/screenshots/desktop_contact_success.png')

        # 2. Mobile Verification
        mobile_page = await browser.new_page(viewport={'width': 375, 'height': 667})
        await mobile_page.goto('http://127.0.0.1:8000')
        await mobile_page.wait_for_load_state('networkidle')

        # Capture Mobile Light Hero
        await mobile_page.screenshot(path='verification/screenshots/mobile_light_hero.png')
        print("Captured mobile_light_hero.png")

        # Open Mobile Navigation Drawer
        await mobile_page.click('#nav-toggle')
        await asyncio.sleep(0.5)
        await mobile_page.screenshot(path='verification/screenshots/mobile_menu_open.png')
        print("Captured mobile_menu_open.png")

        # Close Mobile Nav Drawer
        await mobile_page.click('#nav-close')
        await asyncio.sleep(0.5)

        # Scroll down mobile
        for _ in range(6):
            await mobile_page.mouse.wheel(0, 600)
            await asyncio.sleep(0.3)

        await mobile_page.screenshot(path='verification/screenshots/mobile_full_page.png', full_page=True)
        print("Captured mobile_full_page.png")

        await browser.close()

if __name__ == '__main__':
    asyncio.run(run_verification())
